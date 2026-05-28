<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Province;
use App\Models\City;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Show the settings edit form.
     */
    public function edit()
    {
        $settings = [
            'store_name' => Setting::get('store_name', config('app.name')),
            'store_whatsapp' => Setting::get('store_whatsapp', '6281234567890'),
            'store_logo' => Setting::get('store_logo'),
            'payment_method' => Setting::get('payment_method', 'paymentgateway'),

            // Bank Transfer settings
            'bank_accounts' => json_decode(Setting::get('bank_accounts', '[]'), true) ?: [],

            // Midtrans settings
            'midtrans_client_key' => Setting::get('midtrans_client_key', config('services.midtrans.client_key', '')),
            'midtrans_server_key' => Setting::get('midtrans_server_key', config('services.midtrans.server_key', '')),
            'midtrans_is_production' => Setting::get('midtrans_is_production', config('services.midtrans.is_production', false) ? '1' : '0'),

            // Xendit settings
            'xendit_api_key' => Setting::get('xendit_api_key', config('services.xendit.api_key', '')),
            'xendit_callback_token' => Setting::get('xendit_callback_token', config('services.xendit.callback_token', '')),

            // Shipping API settings
            'shipping_api_key' => Setting::get('shipping_api_key', ''),
            'store_province_code' => Setting::get('store_province_code', ''),
            'store_city_code' => Setting::get('store_city_code', ''),
            'preferred_courier' => Setting::get('preferred_courier', 'jne'),
            'shipping_cost' => Setting::get('shipping_cost', '10000'),
        ];

        // Fetch provinces and cities for dropdowns
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.settings.edit', compact('settings', 'provinces', 'cities'));
    }

    /**
     * Update settings values.
     */
    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_whatsapp' => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:1024',
            'payment_method' => 'nullable|in:paymentgateway,transfer',

            // Bank transfer validation
            'bank_accounts' => 'nullable|array',
            'bank_accounts.*.bank_name' => 'required_with:bank_accounts|string|max:100',
            'bank_accounts.*.account_number' => 'required_with:bank_accounts|string|max:50',
            'bank_accounts.*.account_holder' => 'required_with:bank_accounts|string|max:100',

            // Midtrans validation
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|in:0,1',

            // Xendit validation
            'xendit_api_key' => 'nullable|string|max:255',
            'xendit_callback_token' => 'nullable|string|max:255',

            // Shipping API validation
            'shipping_api_key' => 'nullable|string|max:255',
            'store_province_code' => 'nullable|string|max:20',
            'store_city_code' => 'nullable|string|max:20',
            'preferred_courier' => 'nullable|string|in:jne,tiki,pos,jnt,sicepat,anteraja',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        // Update store name
        Setting::set('store_name', $request->store_name);

        // Update store whatsapp
        Setting::set('store_whatsapp', $request->input('store_whatsapp', ''));

        // Update payment method
        $paymentMethod = $request->payment_method ?? 'paymentgateway';
        Setting::set('payment_method', $paymentMethod);

        // Save bank accounts as JSON
        $bankAccounts = $request->input('bank_accounts', []);
        // Filter out empty rows
        $bankAccounts = array_values(array_filter($bankAccounts, function ($account) {
            return !empty($account['bank_name']) && !empty($account['account_number']) && !empty($account['account_holder']);
        }));
        Setting::set('bank_accounts', json_encode($bankAccounts));

        // Save Midtrans settings
        Setting::set('midtrans_client_key', $request->input('midtrans_client_key', ''));
        Setting::set('midtrans_server_key', $request->input('midtrans_server_key', ''));
        Setting::set('midtrans_is_production', $request->input('midtrans_is_production', '0'));

        // Save Xendit settings
        Setting::set('xendit_api_key', $request->input('xendit_api_key', ''));
        Setting::set('xendit_callback_token', $request->input('xendit_callback_token', ''));

        // Save Shipping API settings
        Setting::set('shipping_api_key', $request->input('shipping_api_key', ''));
        Setting::set('store_province_code', $request->input('store_province_code', ''));
        Setting::set('store_city_code', $request->input('store_city_code', ''));
        Setting::set('preferred_courier', $request->input('preferred_courier', 'jne'));
        Setting::set('shipping_cost', $request->input('shipping_cost', '10000'));

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo file if it was stored in storage/ (not base64)
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && str_starts_with($oldLogo, 'storage/')) {
                $oldPath = str_replace('storage/', '', $oldLogo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            try {
                // Convert to WebP and encode as base64
                $logoBase64 = $this->imageService->convertToWebPBase64(
                    $request->file('store_logo'),
                    400,
                    85
                );

                // Save base64 string to database
                Setting::set('store_logo', $logoBase64);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}
