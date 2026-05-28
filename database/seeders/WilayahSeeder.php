<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use App\Models\Setting;

class WilayahSeeder extends Seeder
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
     */
    public function run(): void
    {
        if (!$this->apiKey) {
            $this->command->error('❌ API Key tidak ditemukan! Silakan set SHIPPING_API_KEY di .env atau di Pengaturan Toko.');
            $this->command->info('Contoh: SHIPPING_API_KEY=your_api_key_here');
            return;
        }

        $this->command->info('🌏 Mulai import data wilayah Indonesia dari use.api.co.id...');
        $this->command->info('🔑 Menggunakan API Key: ' . substr($this->apiKey, 0, 10) . '...');
        
        // Import Provinces
        $this->importProvinces();
        
        // Import Cities (Regencies)
        $this->importCities();
        
        // Import Districts
        $this->importDistricts();
        
        // Import Villages
        $this->importVillages();
        
        $this->command->info('✅ Selesai! Data wilayah berhasil diimport.');
    }

    /**
     * Import all provinces from API
     */
    private function importProvinces()
    {
        $this->command->info('📍 Mengimport data provinsi...');
        
        try {
            $response = Http::withHeaders([
                'x-api-co-id' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($this->baseUrl . '/provinces');
            
            if (!$response->successful()) {
                throw new \Exception('API Error: ' . $response->status() . ' - ' . $response->body());
            }
            
            $data = $response->json();
            $provinces = $data['data'] ?? $data; // Handle both wrapped and unwrapped responses
            
            if (empty($provinces)) {
                throw new \Exception('No provinces data returned from API');
            }
            
            $bar = $this->command->getOutput()->createProgressBar(count($provinces));
            $bar->start();
            
            foreach ($provinces as $province) {
                Province::updateOrCreate(
                    ['code' => $province['code']],
                    ['name' => $province['name']]
                );
                $bar->advance();
            }
            
            $bar->finish();
            $this->command->newLine();
            $this->command->info('✓ ' . count($provinces) . ' provinsi berhasil diimport');
            
        } catch (\Exception $e) {
            $this->command->error('✗ Gagal import provinsi: ' . $e->getMessage());
            $this->command->warn('💡 Pastikan API Key valid dan endpoint tersedia');
        }
    }

    /**
     * Import all cities for each province
     */
    private function importCities()
    {
        $this->command->info('🏙️  Mengimport data kota/kabupaten...');
        
        $provinces = Province::all();
        $totalCities = 0;
        
        foreach ($provinces as $province) {
            try {
                $response = Http::withHeaders([
                    'x-api-co-id' => $this->apiKey,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($this->baseUrl . '/provinces/' . $province->code . '/regencies');
                
                if (!$response->successful()) {
                    throw new \Exception('API Error: ' . $response->status());
                }
                
                $data = $response->json();
                $cities = $data['data'] ?? $data;
                
                foreach ($cities as $city) {
                    City::updateOrCreate(
                        ['code' => $city['code']],
                        [
                            'province_id' => $province->id,
                            'name' => $city['name']
                        ]
                    );
                    $totalCities++;
                }
                
                $this->command->info("  ✓ {$province->name}: " . count($cities) . " kota/kabupaten");
                
            } catch (\Exception $e) {
                $this->command->error("  ✗ Gagal import kota untuk {$province->name}: " . $e->getMessage());
            }
        }
        
        $this->command->info("✓ Total {$totalCities} kota/kabupaten berhasil diimport");
    }

    /**
     * Import all districts for each city
     */
    private function importDistricts()
    {
        $this->command->info('🏘️  Mengimport data kecamatan...');
        $this->command->warn('⚠️  Proses ini membutuhkan waktu cukup lama, mohon bersabar...');
        
        $cities = City::all();
        $totalDistricts = 0;
        $processedCities = 0;
        
        foreach ($cities as $city) {
            try {
                $response = Http::withHeaders([
                    'x-api-co-id' => $this->apiKey,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($this->baseUrl . '/regencies/' . $city->code . '/districts');
                
                if (!$response->successful()) {
                    throw new \Exception('API Error: ' . $response->status());
                }
                
                $data = $response->json();
                $districts = $data['data'] ?? $data;
                
                foreach ($districts as $district) {
                    District::updateOrCreate(
                        ['code' => $district['code']],
                        [
                            'city_id' => $city->id,
                            'name' => $district['name']
                        ]
                    );
                    $totalDistricts++;
                }
                
                $processedCities++;
                if ($processedCities % 10 == 0) {
                    $this->command->info("  ✓ Progress: {$processedCities}/" . count($cities) . " kota/kabupaten diproses");
                }
                
            } catch (\Exception $e) {
                $this->command->error("  ✗ Gagal import kecamatan untuk {$city->name}: " . $e->getMessage());
            }
        }
        
        $this->command->info("✓ Total {$totalDistricts} kecamatan berhasil diimport");
    }

    /**
     * Import all villages for each district
     */
    private function importVillages()
    {
        $this->command->info('🏡 Mengimport data desa/kelurahan...');
        $this->command->warn('⚠️  Proses ini SANGAT LAMA (bisa 30-60 menit), mohon bersabar dan jangan tutup terminal...');
        
        $districts = District::all();
        $totalVillages = 0;
        $processedDistricts = 0;
        
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
                
                foreach ($villages as $village) {
                    Village::updateOrCreate(
                        ['code' => $village['code']],
                        [
                            'district_id' => $district->id,
                            'name' => $village['name']
                        ]
                    );
                    $totalVillages++;
                }
                
                $processedDistricts++;
                if ($processedDistricts % 50 == 0) {
                    $this->command->info("  ✓ Progress: {$processedDistricts}/" . count($districts) . " kecamatan diproses ({$totalVillages} desa)");
                }
                
            } catch (\Exception $e) {
                $this->command->error("  ✗ Gagal import desa untuk {$district->name}: " . $e->getMessage());
            }
        }
        
        $this->command->info("✓ Total {$totalVillages} desa/kelurahan berhasil diimport");
    }
}
