<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Province;
use App\Models\City;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingTestController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Show the shipping API test page
     */
    public function index()
    {
        // Get store location from settings
        $storeProvinceCode = Setting::get('store_province_code', '');
        $storeCityCode = Setting::get('store_city_code', '');
        $shippingApiKey = Setting::get('shipping_api_key', '');
        $preferredCourier = Setting::get('preferred_courier', 'jne');

        // Get province and city names
        $storeProvince = Province::where('code', $storeProvinceCode)->first();
        $storeCity = City::where('code', $storeCityCode)->first();

        // Get all provinces and cities for destination selection
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        // Check if API is configured
        $isConfigured = $this->shippingService->isConfigured();

        return view('admin.shipping.test', compact(
            'storeProvince',
            'storeCity',
            'provinces',
            'cities',
            'isConfigured',
            'shippingApiKey',
            'preferredCourier'
        ));
    }

    /**
     * Test the shipping API
     */
    public function test(Request $request)
    {
        $request->validate([
            'destination_city_code' => 'required|string',
            'weight' => 'required|numeric|min:1',
        ]);

        $destinationCityCode = $request->destination_city_code;
        $weight = $request->weight;

        // Get destination city info
        $destinationCity = City::where('code', $destinationCityCode)->first();
        $destinationProvince = $destinationCity ? Province::where('code', substr($destinationCityCode, 0, 2))->first() : null;

        // Test the API
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
            'debug' => [],
        ];

        try {
            if (!$this->shippingService->isConfigured()) {
                $result['error'] = 'API Shipping belum dikonfigurasi. Silakan isi API Key dan lokasi toko di Pengaturan.';
            } else {
                // Get store origin city code
                $originCityCode = Setting::get('store_city_code');
                $preferredCourier = Setting::get('preferred_courier', 'jne');
                $apiKey = Setting::get('shipping_api_key');

                // Add debug info
                $result['debug'] = [
                    'origin_city' => $originCityCode,
                    'destination_city' => $destinationCityCode,
                    'weight' => $weight,
                    'courier' => $preferredCourier,
                    'api_key_length' => strlen($apiKey ?? ''),
                ];

                // Call the shipping service
                $apiResponse = $this->shippingService->calculateCost(
                    $originCityCode,
                    $destinationCityCode,
                    $weight,
                    $preferredCourier
                );

                // Store raw response for debugging
                $result['debug']['raw_response'] = $apiResponse;

                if ($apiResponse && isset($apiResponse['data']['costs'])) {
                    // Extract shipping cost from API response
                    $costs = $apiResponse['data']['costs'];
                    if (!empty($costs) && isset($costs[0]['cost'][0]['value'])) {
                        $shippingCost = $costs[0]['cost'][0]['value'];
                        $serviceName = $costs[0]['service'] ?? 'Regular';
                        
                        $result['success'] = true;
                        $result['data'] = [
                            'shipping_cost' => $shippingCost,
                            'formatted_cost' => 'Rp ' . number_format($shippingCost, 0, ',', '.'),
                            'weight' => $weight,
                            'destination' => $destinationCity->name . ', ' . $destinationProvince->name,
                            'service' => $serviceName,
                            'courier' => strtoupper($preferredCourier),
                        ];
                    } else {
                        $result['error'] = 'API mengembalikan response tanpa data biaya. Periksa konfigurasi kurir.';
                        $result['debug']['costs_data'] = $costs ?? 'No costs data';
                    }
                } else {
                    $result['error'] = 'API mengembalikan response kosong atau error. Periksa API Key dan koneksi internet.';
                    if ($apiResponse === null) {
                        $result['debug']['note'] = 'API response is NULL - kemungkinan API Key salah atau endpoint tidak valid';
                    }
                }
            }
        } catch (\Exception $e) {
            $result['error'] = 'Error: ' . $e->getMessage();
            $result['debug']['exception'] = $e->getTraceAsString();
        }

        // Get store location for display
        $storeProvinceCode = Setting::get('store_province_code', '');
        $storeCityCode = Setting::get('store_city_code', '');
        $storeProvince = Province::where('code', $storeProvinceCode)->first();
        $storeCity = City::where('code', $storeCityCode)->first();

        // Get all provinces and cities for form
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $isConfigured = $this->shippingService->isConfigured();
        $shippingApiKey = Setting::get('shipping_api_key', '');
        $preferredCourier = Setting::get('preferred_courier', 'jne');

        return view('admin.shipping.test', compact(
            'storeProvince',
            'storeCity',
            'provinces',
            'cities',
            'isConfigured',
            'shippingApiKey',
            'preferredCourier',
            'result',
            'destinationCity',
            'destinationProvince',
            'weight'
        ));
    }
}
