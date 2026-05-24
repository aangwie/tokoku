<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\City;
use App\Models\District;
use App\Models\Village;

class SeedDistrictsVillages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:seed-districts-villages 
                            {--districts-only : Only seed districts}
                            {--villages-only : Only seed villages}
                            {--province= : Seed for specific province code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data kecamatan dan desa/kelurahan dari API ke database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌏 Mulai import data kecamatan dan desa/kelurahan...');
        $this->info('⚠️  Proses ini akan memakan waktu 15-30 menit. Harap bersabar...');
        $this->newLine();
        
        $districtsOnly = $this->option('districts-only');
        $villagesOnly = $this->option('villages-only');
        $provinceCode = $this->option('province');
        
        if (!$villagesOnly) {
            $this->seedDistricts($provinceCode);
        }
        
        if (!$districtsOnly) {
            $this->seedVillages($provinceCode);
        }
        
        $this->newLine();
        $this->info('✅ Selesai! Data berhasil diimport.');
        
        // Show statistics
        $this->showStatistics();
    }

    /**
     * Seed districts for all cities
     */
    private function seedDistricts($provinceCode = null)
    {
        $this->info('📍 Mengimport data kecamatan...');
        
        $query = City::query();
        
        if ($provinceCode) {
            $query->whereHas('province', function($q) use ($provinceCode) {
                $q->where('code', $provinceCode);
            });
        }
        
        $cities = $query->with('province')->get();
        $totalDistricts = 0;
        
        $bar = $this->output->createProgressBar($cities->count());
        $bar->start();
        
        foreach ($cities as $city) {
            try {
                // Check if already seeded
                $existingCount = District::where('city_id', $city->id)->count();
                if ($existingCount > 0) {
                    $bar->advance();
                    continue;
                }
                
                $response = Http::timeout(30)->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$city->code}.json");
                
                if ($response->successful()) {
                    $districts = $response->json();
                    
                    foreach ($districts as $district) {
                        District::updateOrCreate(
                            ['code' => $district['id']],
                            [
                                'city_id' => $city->id,
                                'name' => $district['name']
                            ]
                        );
                        $totalDistricts++;
                    }
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("\n✗ Gagal import kecamatan untuk {$city->name}: " . $e->getMessage());
            }
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("✓ Total {$totalDistricts} kecamatan berhasil diimport");
    }

    /**
     * Seed villages for all districts
     */
    private function seedVillages($provinceCode = null)
    {
        $this->info('🏘️  Mengimport data desa/kelurahan...');
        $this->warn('⚠️  Ini akan memakan waktu paling lama (sekitar 20-30 menit)...');
        
        $query = District::query();
        
        if ($provinceCode) {
            $query->whereHas('city.province', function($q) use ($provinceCode) {
                $q->where('code', $provinceCode);
            });
        }
        
        $districts = $query->with('city')->get();
        $totalVillages = 0;
        
        $bar = $this->output->createProgressBar($districts->count());
        $bar->start();
        
        foreach ($districts as $district) {
            try {
                // Check if already seeded
                $existingCount = Village::where('district_id', $district->id)->count();
                if ($existingCount > 0) {
                    $bar->advance();
                    continue;
                }
                
                $response = Http::timeout(30)->get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$district->code}.json");
                
                if ($response->successful()) {
                    $villages = $response->json();
                    
                    foreach ($villages as $village) {
                        Village::updateOrCreate(
                            ['code' => $village['id']],
                            [
                                'district_id' => $district->id,
                                'name' => $village['name']
                            ]
                        );
                        $totalVillages++;
                    }
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                // Silent fail to avoid cluttering output
            }
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("✓ Total {$totalVillages} desa/kelurahan berhasil diimport");
    }

    /**
     * Show statistics
     */
    private function showStatistics()
    {
        $this->newLine();
        $this->info('📊 Statistik Data Wilayah:');
        $this->table(
            ['Jenis', 'Jumlah'],
            [
                ['Provinsi', \App\Models\Province::count()],
                ['Kota/Kabupaten', City::count()],
                ['Kecamatan', District::count()],
                ['Desa/Kelurahan', Village::count()],
            ]
        );
    }
}
