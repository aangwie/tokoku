<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class ShippingService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.co.id';
    
    public function __construct()
    {
        $this->apiKey = Setting::get('shipping_api_key', env('SHIPPING_API_KEY'));
    }

    /**
     * Calculate shipping cost from origin to destination
     *
     * @param string $originCityCode
     * @param string $destinationCityCode
     * @param int $weight Weight in grams
     * @param string $courier Courier code (jne, tiki, pos, etc.)
     * @return array|null
     */
    public function calculateCost($originCityCode, $destinationCityCode, $weight, $courier = 'jne')
    {
        if (!$this->apiKey) {
            Log::error('Shipping API key not configured');
            return null;
        }

        // Cache key for this specific calculation
        $cacheKey = "shipping_cost_{$originCityCode}_{$destinationCityCode}_{$weight}_{$courier}";
        
        // Try to get from cache (valid for 1 hour)
        return Cache::remember($cacheKey, 3600, function () use ($originCityCode, $destinationCityCode, $weight, $courier) {
            try {
                $response = Http::withHeaders([
                    'x-api-co-id' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->timeout(30)
                ->post($this->baseUrl . '/v1/shipping/cost', [
                    'origin' => $originCityCode,
                    'destination' => $destinationCityCode,
                    'weight' => $weight,
                    'courier' => $courier,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data;
                }

                Log::error('Shipping API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return null;
            } catch (\Exception $e) {
                Log::error('Shipping API exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Calculate total shipping cost for cart items
     *
     * @param array $cartItems
     * @param string $destinationCityCode
     * @return int Total shipping cost in Rupiah
     */
    public function calculateCartShipping($cartItems, $destinationCityCode)
    {
        // Get store origin from settings
        $originCityCode = Setting::get('store_city_code');
        $preferredCourier = Setting::get('preferred_courier', 'jne');

        if (!$originCityCode) {
            Log::warning('Store origin city not configured');
            return 0;
        }

        if (!$destinationCityCode) {
            Log::warning('Destination city not provided');
            return 0;
        }

        $totalShippingCost = 0;
        $totalWeight = 0;

        // Calculate total weight for items that require shipping
        foreach ($cartItems as $item) {
            // Check if product has shipping (is_free_shipping = false means has shipping)
            if (isset($item['is_free_shipping']) && !$item['is_free_shipping']) {
                $itemWeight = $item['weight'] ?? 0;
                $quantity = $item['quantity'] ?? 1;
                $totalWeight += ($itemWeight * $quantity);
            }
        }

        // If no items require shipping, return 0
        if ($totalWeight == 0) {
            return 0;
        }

        // Get shipping cost from API
        $result = $this->calculateCost($originCityCode, $destinationCityCode, $totalWeight, $preferredCourier);

        if ($result && isset($result['data']['costs'])) {
            // Get the first available service cost
            $costs = $result['data']['costs'];
            if (!empty($costs) && isset($costs[0]['cost'][0]['value'])) {
                $totalShippingCost = $costs[0]['cost'][0]['value'];
            }
        }

        return $totalShippingCost;
    }

    /**
     * Get available couriers
     *
     * @return array
     */
    public function getAvailableCouriers()
    {
        return [
            'jne' => 'JNE',
            'tiki' => 'TIKI',
            'pos' => 'POS Indonesia',
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'anteraja' => 'AnterAja',
        ];
    }

    /**
     * Check if shipping service is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiKey) && !empty(Setting::get('store_city_code'));
    }
}
