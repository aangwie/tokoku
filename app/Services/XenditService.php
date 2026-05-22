<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk integrasi dengan Xendit Invoice API.
 *
 * Menangani pembuatan invoice untuk pembayaran
 * dan verifikasi callback token dari webhook.
 *
 * Kredensial dibaca dari database (Setting model) dengan
 * fallback ke config/env jika belum diatur di database.
 */
class XenditService
{
    /**
     * Base URL Xendit Invoice API.
     */
    private const BASE_URL = 'https://api.xendit.co/v2/invoices';

    /**
     * API key Xendit untuk autentikasi.
     */
    private string $apiKey;

    /**
     * Callback token untuk verifikasi webhook.
     */
    private string $callbackToken;

    public function __construct()
    {
        // Baca kredensial dari database, fallback ke config/env
        $this->apiKey = Setting::get('xendit_api_key', config('services.xendit.api_key'));
        $this->callbackToken = Setting::get('xendit_callback_token', config('services.xendit.callback_token'));
    }

    /**
     * Buat invoice di Xendit untuk sebuah order.
     *
     * Mengembalikan URL halaman pembayaran Xendit
     * yang bisa di-redirect ke pelanggan.
     *
     * @param  Order  $order  Order yang akan dibayar (harus sudah load relasi user)
     * @return string|null     Invoice URL, atau null jika gagal
     */
    public function createInvoice(Order $order): ?string
    {
        // Pastikan relasi user ter-load
        $order->loadMissing('user');

        $payload = [
            'external_id'          => $order->order_number,
            'amount'               => (int) $order->total_price,
            'payer_email'          => $order->user->email,
            'description'          => 'Pembayaran pesanan ' . $order->order_number,
            'success_redirect_url' => route('orders.show', $order->id),
            'failure_redirect_url' => route('orders.show', $order->id),
        ];

        // Kirim request ke Xendit Invoice API dengan Basic Auth
        $response = Http::withBasicAuth($this->apiKey, '')
            ->acceptJson()
            ->post(self::BASE_URL, $payload);

        // Log jika request gagal
        if ($response->failed()) {
            Log::error('Xendit createInvoice gagal', [
                'order_number' => $order->order_number,
                'status'       => $response->status(),
                'body'         => $response->body(),
            ]);

            return null;
        }

        return $response->json('invoice_url');
    }

    /**
     * Verifikasi callback webhook dari Xendit.
     *
     * Membandingkan header x-callback-token dengan
     * callback token yang tersimpan di konfigurasi.
     *
     * @param  Request  $request  HTTP request dari webhook Xendit
     * @return bool     True jika callback token valid
     */
    public function verifyCallback(Request $request): bool
    {
        $headerToken = $request->header('x-callback-token', '');

        return hash_equals($this->callbackToken, $headerToken);
    }
}
