<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    /**
     * Test endpoint for Midtrans webhook (GET request)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Midtrans webhook endpoint is ready',
            'endpoint' => url('/webhook/midtrans/notification'),
            'method' => 'POST',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Handle Midtrans payment notification
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notification(Request $request)
    {
        try {
            // Get notification data
            $notification = $request->all();
            
            Log::info('Midtrans Notification Received', $notification);

            // Verify signature
            if (!$this->verifySignature($notification)) {
                Log::error('Invalid Midtrans signature');
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Get order
            $orderId = $notification['order_id'] ?? null;
            if (!$orderId) {
                Log::error('Order ID not found in notification');
                return response()->json(['message' => 'Order ID required'], 400);
            }

            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Get transaction status
            $transactionStatus = $notification['transaction_status'] ?? '';
            $fraudStatus = $notification['fraud_status'] ?? 'accept';
            $paymentType = $notification['payment_type'] ?? '';

            // Update order based on transaction status
            $this->updateOrderStatus($order, $transactionStatus, $fraudStatus, $paymentType, $notification);

            return response()->json(['message' => 'Notification processed successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Verify Midtrans signature
     * 
     * @param array $notification
     * @return bool
     */
    private function verifySignature(array $notification): bool
    {
        // Get server key from settings or fallback to .env
        $serverKey = Setting::get('midtrans_server_key', config('midtrans.server_key'));
        
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        // Create signature
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $mySignature === $signatureKey;
    }

    /**
     * Update order status based on Midtrans transaction status
     * 
     * @param Order $order
     * @param string $transactionStatus
     * @param string $fraudStatus
     * @param string $paymentType
     * @param array $notification
     * @return void
     */
    private function updateOrderStatus(Order $order, string $transactionStatus, string $fraudStatus, string $paymentType, array $notification): void
    {
        // Log the status update
        Log::info("Updating order {$order->order_number} - Transaction: {$transactionStatus}, Fraud: {$fraudStatus}");

        // Store payment details
        $paymentDetails = [
            'payment_type' => $paymentType,
            'transaction_id' => $notification['transaction_id'] ?? null,
            'transaction_time' => $notification['transaction_time'] ?? null,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ];

        // Add payment-specific details
        if (isset($notification['va_numbers'])) {
            $paymentDetails['va_number'] = $notification['va_numbers'][0]['va_number'] ?? null;
            $paymentDetails['bank'] = $notification['va_numbers'][0]['bank'] ?? null;
        } elseif (isset($notification['permata_va_number'])) {
            $paymentDetails['va_number'] = $notification['permata_va_number'];
            $paymentDetails['bank'] = 'permata';
        } elseif (isset($notification['bill_key'])) {
            $paymentDetails['bill_key'] = $notification['bill_key'];
            $paymentDetails['biller_code'] = $notification['biller_code'] ?? null;
        }

        // Update order based on transaction status
        switch ($transactionStatus) {
            case 'capture':
                // For credit card transactions
                if ($fraudStatus == 'accept') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'paid',
                        'payment_details' => json_encode($paymentDetails),
                        'paid_at' => now(),
                    ]);
                    Log::info("Order {$order->order_number} marked as PAID (capture/accept)");
                } else {
                    $order->update([
                        'payment_status' => 'pending',
                        'payment_details' => json_encode($paymentDetails),
                    ]);
                    Log::info("Order {$order->order_number} marked as PENDING (capture/challenge)");
                }
                break;

            case 'settlement':
                // Payment successfully settled
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'paid',
                    'payment_details' => json_encode($paymentDetails),
                    'paid_at' => now(),
                ]);
                Log::info("Order {$order->order_number} marked as PAID (settlement)");
                break;

            case 'pending':
                // Payment is pending
                $order->update([
                    'payment_status' => 'pending',
                    'payment_details' => json_encode($paymentDetails),
                ]);
                Log::info("Order {$order->order_number} marked as PENDING");
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                // Payment denied, cancelled, or expired
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                    'payment_details' => json_encode($paymentDetails),
                ]);
                Log::info("Order {$order->order_number} marked as FAILED ({$transactionStatus})");
                break;

            case 'failure':
                // Payment failed
                $order->update([
                    'payment_status' => 'failed',
                    'payment_details' => json_encode($paymentDetails),
                ]);
                Log::info("Order {$order->order_number} marked as FAILED (failure)");
                break;

            default:
                Log::warning("Unknown transaction status: {$transactionStatus} for order {$order->order_number}");
                break;
        }
    }

    /**
     * Handle finish redirect from Midtrans
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                // Check payment status from Midtrans API
                try {
                    $this->checkAndUpdatePaymentStatus($order);
                } catch (\Exception $e) {
                    Log::error('Error checking payment status: ' . $e->getMessage());
                }
                
                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Terima kasih! Pembayaran Anda sedang diproses.');
            }
        }

        return redirect()->route('orders.index')
            ->with('info', 'Pembayaran Anda sedang diproses.');
    }
    /**
     * Check and update payment status from Midtrans API
     * 
     * @param Order $order
     * @return void
     */
    private function checkAndUpdatePaymentStatus(Order $order): void
    {
        if ($order->status !== 'pending') {
            return; // Already processed
        }

        $serverKey = Setting::get('midtrans_server_key');
        $isProduction = Setting::get('midtrans_is_production', '0') === '1';
        
        $apiUrl = $isProduction
            ? "https://api.midtrans.com/v2/{$order->order_number}/status"
            : "https://api.sandbox.midtrans.com/v2/{$order->order_number}/status";

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $result = json_decode($response, true);
                
                $transactionStatus = $result['transaction_status'] ?? '';
                $fraudStatus = $result['fraud_status'] ?? 'accept';
                $paymentType = $result['payment_type'] ?? '';
                
                Log::info("Checked payment status for {$order->order_number}: {$transactionStatus}");
                
                // Update order status
                $this->updateOrderStatus($order, $transactionStatus, $fraudStatus, $paymentType, $result);
            }
        } catch (\Exception $e) {
            Log::error('Failed to check Midtrans status: ' . $e->getMessage());
        }
    }

    /**
     * Handle unfinish redirect from Midtrans
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                return redirect()->route('orders.show', $order->id)
                    ->with('warning', 'Pembayaran Anda belum selesai. Silakan selesaikan pembayaran Anda.');
            }
        }

        return redirect()->route('orders.index')
            ->with('warning', 'Pembayaran belum selesai.');
    }

    /**
     * Handle error redirect from Midtrans
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function error(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
            }
        }

        return redirect()->route('orders.index')
            ->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
    }
}