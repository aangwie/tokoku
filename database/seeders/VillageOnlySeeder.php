<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\District;
use App\Models\Village;
use App\Models\Setting;

class VillageOnlySeeder extends Seeder
{
    protected $baseUrl = 'https://use.api.co.id/regional/indonesia';
    protected $apiKey;

    public function __construct()
    {
        // Get API key from settings or environment
        $this->apiKey = Setting::get('shipping_api_key', env('SHIPPING_API_KEY'));
    }

    /**
     * Run the database seeds.
     * Import villages only (desa/kelurahan saja)
     */
    public function run(): void
    {
        if (!$this->apiKey) {
            $this->command->error('❌ API Key tidak ditemukan! Silakan set SHIPPING_API_KEY di .env atau di Pengaturan Toko.');
            $this->command->info('Contoh: SHIPPING_API_KEY=your_api_key_here');
            return;
        }

        $this->command->info('🌏 Mulai import data DESA/KELURAHAN saja dari use.api.co.id...');
        $this->command->info('🔑 Menggunakan API Key: ' . substr($this->apiKey, 0, 10) . '...');
        
        // Import Villages only
        $this->importVillages();
        
        $this->command->info('✅ Selesai! Data desa/kelurahan berhasil diimport.');
    }

    /**
     * Import all villages for each district
     * Menggunakan updateOrCreate untuk menghindari duplikasi
     */
    private function importVillages()
    {
        $this->command->info('🏡 Mengimport data desa/kelurahan...');
        $this->command->warn('⚠️  Proses ini SANGAT LAMA (bisa 30-60 menit), mohon bersabar dan jangan tutup terminal...');
        
        // Get all districts from database
        $districts = District::all();
        
        if ($districts->isEmpty()) {
            $this->command->error('❌ Tidak ada data kecamatan di database!');
            $this->command->warn('💡 Pastikan data provinsi, kota/kabupaten, dan kecamatan sudah ada di database.');
            return;
        }
        
        $this->command->info('📊 Total kecamatan yang akan diproses: ' . $districts->count());
        
        $totalVillages = 0;
        $newVillages = 0;
        $updatedVillages = 0;
        $processedDistricts = 0;
        $failedDistricts = 0;
        
        foreach ($districts as $district) {
            try {
                $response = Http::withHeaders([
                    'x-api-co-id' => $this->apiKey,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($this->baseUrl . '/districts/' . $district->code . '/villages');
                
                if (!$response->successful()) {
                    throw new \Exception('API Error: ' . $response->status());
                }
                
                $data = $response->json();
                $villages = $data['data'] ?? $data;
                
                if (empty($villages)) {
                    $this->command->warn("  ⚠️  {$district->name}: Tidak ada data desa");
                    $processedDistricts++;
                    continue;
                }
                
                foreach ($villages as $village) {
                    // Check if village already exists
                    $existingVillage = Village::where('code', $village['code'])->first();
                    
                    if ($existingVillage) {
                        // Update existing village
                        $existingVillage->update([
                            'district_id' => $district->id,
                            'name' => $village['name']
                        ]);
                        $updatedVillages++;
                    } else {
                        // Create new village
                        Village::create([
                            'code' => $village['code'],
                            'district_id' => $district->id,
                            'name' => $village['name']
                        ]);
                        $newVillages++;
                    }
                    
                    $totalVillages++;
                }
                
                $processedDistricts++;
                
                // Show progress every 50 districts
                if ($processedDistricts % 50 == 0) {
                    $this->command->info("  ✓ Progress: {$processedDistricts}/" . $districts->count() . " kecamatan diproses");
                    $this->command->info("    📊 Total: {$totalVillages} desa | Baru: {$newVillages} | Update: {$updatedVillages}");
                }
                
                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                $failedDistricts++;
                $this->command->error("  ✗ Gagal import desa untuk {$district->name}: " . $e->getMessage());
            }
        }
        
        $this->command->newLine();
        $this->command->info("📊 RINGKASAN IMPORT:");
        $this->command->info("✓ Total desa/kelurahan diproses: {$totalVillages}");
        $this->command->info("✓ Desa baru ditambahkan: {$newVillages}");
        $this->command->info("✓ Desa diupdate: {$updatedVillages}");
        $this->command->info("✓ Kecamatan berhasil: {$processedDistricts}");
        
        if ($failedDistricts > 0) {
            $this->command->warn("⚠️  Kecamatan gagal: {$failedDistricts}");
        }
    }
}
