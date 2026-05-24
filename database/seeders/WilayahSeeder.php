<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\City;

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
}
