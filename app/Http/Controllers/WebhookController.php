<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\XenditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk menangani webhook/notification
 * dari payment gateway (Midtrans & Xendit).
 *
 * Route webhook ini harus di-exclude dari CSRF verification.
 */
class WebhookController extends Controller
{
    /**
     * Handle notifikasi pembayaran dari Midtrans.
     *
     * Midtrans mengirim POST request dengan JSON body
     * berisi status transaksi yang harus di-verifikasi.
     */
    public function midtrans(Request $request, MidtransService $midtransService): JsonResponse
    {
        $notification = $request->all();

        // 1. Verifikasi signature untuk memastikan notifikasi asli dari Midtrans
        if (! $midtransService->verifySignature($notification)) {
            Log::warning('Midtrans webhook: Signature tidak valid', $notification);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Cari order berdasarkan order_id dari notifikasi
        $order = Order::where('order_number', $notification['order_id'] ?? '')->first();

        if (! $order) {
            Log::warning('Midtrans webhook: Order tidak ditemukan', [
                'order_id' => $notification['order_id'] ?? 'N/A',
            ]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Mapping transaction_status Midtrans ke status internal
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? 'accept';

        $newStatus = match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'accept') ? 'paid' : $order->status,
            'settlement' => 'paid',
            'deny', 'cancel', 'expire' => 'cancelled',
            default      => null,
        };

        // 4. Update status order jika ada perubahan yang valid
        if ($newStatus && $newStatus !== $order->status) {
            $order->update([
                'status'            => $newStatus,
                'payment_reference' => $notification['transaction_id'] ?? $order->payment_reference,
            ]);

            // Fire event agar listener lain bisa merespons (notifikasi, dll)
            event(new OrderStatusChanged($order));

            Log::info('Midtrans webhook: Status order diperbarui', [
                'order_number' => $order->order_number,
                'old_status'   => $order->getOriginal('status'),
                'new_status'   => $newStatus,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Handle callback webhook dari Xendit.
     *
     * Xendit mengirim POST request dengan header x-callback-token
     * untuk verifikasi dan JSON body berisi status invoice.
     */
    public function xendit(Request $request, XenditService $xenditService): JsonResponse
    {
        // 1. Verifikasi callback token dari header
        if (! $xenditService->verifyCallback($request)) {
            Log::warning('Xendit webhook: Callback token tidak valid');

            return response()->json(['message' => 'Invalid callback token'], 403);
        }

        $payload = $request->all();

        // 2. Cari order berdasarkan external_id dari payload
        $externalId = $payload['external_id'] ?? '';
        $order = Order::where('order_number', $externalId)->first();

        if (! $order) {
            Log::warning('Xendit webhook: Order tidak ditemukan', [
                'external_id' => $externalId,
            ]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Mapping status Xendit ke status internal
        $xenditStatus = $payload['status'] ?? '';
        $newStatus = match ($xenditStatus) {
            'PAID', 'SETTLED' => 'paid',
            'EXPIRED'         => 'cancelled',
            default           => null,
        };

        // 4. Update status order jika ada perubahan yang valid
        if ($newStatus && $newStatus !== $order->status) {
            $order->update([
                'status'            => $newStatus,
                'payment_reference' => $payload['id'] ?? $order->payment_reference,
            ]);

            // Fire event agar listener lain bisa merespons
            event(new OrderStatusChanged($order));

            Log::info('Xendit webhook: Status order diperbarui', [
                'order_number' => $order->order_number,
                'old_status'   => $order->getOriginal('status'),
                'new_status'   => $newStatus,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
