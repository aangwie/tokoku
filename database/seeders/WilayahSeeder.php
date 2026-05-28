<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌏 Mulai import data wilayah Indonesia...');
        
        // Import Provinces
        $this->importProvinces();
        
        // Import Cities
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
            $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
            $provinces = $response->json();
            
            $bar = $this->command->getOutput()->createProgressBar(count($provinces));
            $bar->start();
            
            foreach ($provinces as $province) {
                Province::updateOrCreate(
                    ['code' => $province['id']],
                    ['name' => $province['name']]
                );
                $bar->advance();
            }
            
            $bar->finish();
            $this->command->newLine();
            $this->command->info('✓ ' . count($provinces) . ' provinsi berhasil diimport');
            
        } catch (\Exception $e) {
            $this->command->error('✗ Gagal import provinsi: ' . $e->getMessage());
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
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$province->code}.json");
                $cities = $response->json();
                
                foreach ($cities as $city) {
                    City::updateOrCreate(
                        ['code' => $city['id']],
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
                $response = Http::timeout(30)->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$city->code}.json");
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
                $response = Http::timeout(30)->get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$district->code}.json");
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
