<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send WhatsApp message via Baileys Gateway
     */
    public function sendWhatsApp(string $phone, string $message): bool
    {
        try {
            $gatewayUrl = config('services.whatsapp_gateway.url');
            $response = Http::timeout(10)->post($gatewayUrl . '/send-message', [
                'phone' => $this->formatPhoneNumber($phone),
                'message' => $message,
            ]);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Telegram message to admin group
     */
    public function sendTelegram(string $message): bool
    {
        try {
            $token = config('services.telegram.bot_token');
            $chatId = config('services.telegram.admin_chat_id');
            
            if (empty($token) || empty($chatId)) {
                Log::warning('Telegram credentials not configured');
                return false;
            }

            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order status notification to customer (WA) and admin (Telegram)
     */
    public function notifyOrderStatusChanged(Order $order): void
    {
        $order->load('user');
        
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Berhasil',
            'shipping' => 'Sedang Dikirim',
            'completed' => 'Pesanan Selesai',
            'cancelled' => 'Pesanan Dibatalkan',
        ];

        $statusLabel = $statusLabels[$order->status] ?? $order->status;

        // WhatsApp message to customer
        $waMessage = "*Notifikasi Pesanan*\n\n"
            . "Halo {$order->user->name},\n"
            . "Status pesanan Anda (*{$order->order_number}*) telah diperbarui:\n\n"
            . "📦 Status: *{$statusLabel}*\n"
            . "💰 Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";

        if ($order->tracking_number) {
            $waMessage .= "🚚 No. Resi: *{$order->tracking_number}*\n";
        }

        $waMessage .= "\nTerima kasih telah berbelanja di toko kami! 🙏";

        // Telegram message to admin
        $tgMessage = "<b>🔔 Update Pesanan</b>\n\n"
            . "Order: <b>{$order->order_number}</b>\n"
            . "Customer: {$order->user->name}\n"
            . "Status: <b>{$statusLabel}</b>\n"
            . "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";

        if ($order->tracking_number) {
            $tgMessage .= "Resi: <code>{$order->tracking_number}</code>\n";
        }

        // Send notifications (non-blocking - failures are logged)
        // Note: In production, you would use $order->user->phone for WhatsApp
        // For now, we log the WA message since phone field may not exist
        if (isset($order->user->phone) && !empty($order->user->phone)) {
            $this->sendWhatsApp($order->user->phone, $waMessage);
        } else {
            Log::info('WhatsApp notification skipped (no phone): ' . $waMessage);
        }

        $this->sendTelegram($tgMessage);
    }

    /**
     * Format phone number to international format (62xxx)
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert 08xx to 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // Add 62 prefix if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
}
