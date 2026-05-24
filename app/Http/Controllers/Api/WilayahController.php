<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;

class WilayahController extends Controller
{
    /**
     * Get all provinces from database
     */
    public function getProvinces()
    {
        try {
            $provinces = Province::select('id', 'code', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($province) {
                    return [
                        'id' => $province->code,
                        'name' => $province->name
                    ];
                });
            
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    /**
     * Get cities by province code from database
     */
    public function getCities($provinceCode)
    {
        try {
            $province = Province::where('code', $provinceCode)->first();
            
            if (!$province) {
                return response()->json(['error' => 'Province not found'], 404);
            }
            
            $cities = City::where('province_id', $province->id)
                ->select('id', 'code', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($city) {
                    return [
                        'id' => $city->code,
                        'name' => $city->name
                    ];
                });
            
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    /**
     * Get districts by city code (lazy loading from API if not in database)
     */
    public function getDistricts($cityCode)
    {
        try {
            $city = City::where('code', $cityCode)->first();
            
            if (!$city) {
                return response()->json(['error' => 'City not found'], 404);
            }
            
            // Check if districts already in database
            $districts = District::where('city_id', $city->id)->get();
            
            // If not in database, fetch from API and save
            if ($districts->isEmpty()) {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$cityCode}.json");
                $apiDistricts = $response->json();
                
                foreach ($apiDistricts as $district) {
                    District::create([
                        'city_id' => $city->id,
                        'code' => $district['id'],
                        'name' => $district['name']
                    ]);
                }
                
                $districts = District::where('city_id', $city->id)->get();
            }
            
            $result = $districts->map(function ($district) {
                return [
                    'id' => $district->code,
                    'name' => $district->name
                ];
            });
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch districts'], 500);
        }
    }

    /**
     * Get villages by district code (lazy loading from API if not in database)
     */
    public function getVillages($districtCode)
    {
        try {
            $district = District::where('code', $districtCode)->first();
            
            if (!$district) {
                return response()->json(['error' => 'District not found'], 404);
            }
            
            // Check if villages already in database
            $villages = Village::where('district_id', $district->id)->get();
            
            // If not in database, fetch from API and save
            if ($villages->isEmpty()) {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$districtCode}.json");
                $apiVillages = $response->json();
                
                foreach ($apiVillages as $village) {
                    Village::create([
                        'district_id' => $district->id,
                        'code' => $village['id'],
                        'name' => $village['name']
                    ]);
                }
                
                $villages = Village::where('district_id', $district->id)->get();
            }
            
            $result = $villages->map(function ($village) {
                return [
                    'id' => $village->code,
                    'name' => $village->name
                ];
            });
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch villages'], 500);
        }
    }
}
