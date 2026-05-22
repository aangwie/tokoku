<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk integrasi dengan Midtrans Snap API.
 *
 * Menangani pembuatan snap token untuk pembayaran
 * dan verifikasi signature dari webhook notification.
 */
class MidtransService
{
    /**
     * Base URL Midtrans Snap API (sandbox / production).
     */
    private string $baseUrl;

    /**
     * Server key Midtrans untuk autentikasi.
     */
    private string $serverKey;

    public function __construct()
    {
        // Tentukan URL berdasarkan environment (sandbox vs production)
        $this->baseUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $this->serverKey = config('services.midtrans.server_key');
    }

    /**
     * Buat Snap Token dari Midtrans untuk sebuah order.
     *
     * Token ini digunakan oleh Snap.js di frontend
     * untuk menampilkan popup pembayaran Midtrans.
     *
     * @param  Order  $order  Order yang akan dibayar (harus sudah load relasi user & orderItems.product)
     * @return string|null     Snap token, atau null jika gagal
     */
    public function createSnapToken(Order $order): ?string
    {
        // Pastikan relasi sudah ter-load untuk menghindari N+1 queries
        $order->loadMissing(['user', 'orderItems.product']);

        // 1. Detail transaksi utama
        $transactionDetails = [
            'order_id'     => $order->order_number,
            'gross_amount' => (int) $order->total_price,
        ];

        // 2. Detail item pesanan
        $itemDetails = $order->orderItems->map(fn ($item) => [
            'id'       => (string) $item->product_id,
            'price'    => (int) $item->price,
            'quantity' => $item->quantity,
            'name'     => mb_substr($item->product->name ?? 'Produk', 0, 50),
        ])->toArray();

        // Tambahkan ongkos kirim sebagai item terpisah jika ada
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Tambahkan diskon sebagai item negatif jika ada
        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => (int) -$order->discount_amount,
                'quantity' => 1,
                'name'     => 'Diskon Kupon',
            ];
        }

        // 3. Detail pelanggan
        $customerDetails = [
            'first_name' => $order->user->name,
            'email'      => $order->user->email,
        ];

        // 4. Kirim request ke Midtrans Snap API
        $response = Http::withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post($this->baseUrl, [
                'transaction_details' => $transactionDetails,
                'item_details'        => $itemDetails,
                'customer_details'    => $customerDetails,
            ]);

        // Log response untuk debugging
        if ($response->failed()) {
            Log::error('Midtrans createSnapToken gagal', [
                'order_number' => $order->order_number,
                'status'       => $response->status(),
                'body'         => $response->body(),
            ]);

            return null;
        }

        return $response->json('token');
    }

    /**
     * Verifikasi signature dari notifikasi webhook Midtrans.
     *
     * Formula signature: SHA512(order_id + status_code + gross_amount + server_key)
     *
     * @param  array  $notification  Data notifikasi dari Midtrans
     * @return bool   True jika signature valid
     */
    public function verifySignature(array $notification): bool
    {
        $orderId    = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        // Hitung signature yang diharapkan
        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $this->serverKey
        );

        return hash_equals($expectedSignature, $signatureKey);
    }
}
