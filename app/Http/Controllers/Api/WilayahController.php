<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    /**
     * Get cities/regencies by province code
     */
    public function getCities($provinceCode)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceCode}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    /**
     * Get districts by city/regency code
     */
    public function getDistricts($cityCode)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityCode}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch districts'], 500);
        }
    }

    /**
     * Get villages by district code
     */
    public function getVillages($districtCode)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtCode}.json");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch villages'], 500);
        }
    }
}