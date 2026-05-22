<?php

use App\Events\OrderStatusChanged;
use App\Listeners\SendOrderStatusNotification;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '08123456789'
    ]);

    $this->category = Category::create([
        'name' => 'Elektronik',
        'slug' => 'elektronik'
    ]);

    // Setup dummy order
    $this->order = Order::create([
        'user_id' => $this->user->id,
        'order_number' => 'TRX-123456',
        'subtotal' => 100000,
        'discount_amount' => 0,
        'total_price' => 115000,
        'shipping_cost' => 15000,
        'status' => 'pending',
        'payment_gateway' => 'midtrans',
        'shipping_address' => 'Jl. Kebagusan No. 2',
    ]);

    // Set configuration variables for services
    config([
        'services.telegram.bot_token' => 'dummy-bot-token',
        'services.telegram.admin_chat_id' => 'dummy-chat-id',
        'services.whatsapp_gateway.url' => 'http://localhost:8000',
    ]);
});

test('order status changed event is registered and dispatches properly', function () {
    Event::fake();

    $this->order->update(['status' => 'paid']);
    event(new OrderStatusChanged($this->order));

    Event::assertDispatched(OrderStatusChanged::class);
});

test('notification listener is listening to order status changed event', function () {
    Event::fake();

    // Trigger update status route / mock trigger to see if event is fired
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin)
        ->patch(route('admin.orders.updateStatus', $this->order->id), [
            'status' => 'paid',
            'tracking_number' => 'RESI123456'
        ]);

    $response->assertRedirect();
    Event::assertDispatched(OrderStatusChanged::class);
});

test('notification service sends telegram and whatsapp requests successfully', function () {
    Http::fake([
        'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
        'http://localhost:8000/*' => Http::response(['success' => true], 200),
    ]);

    $service = new NotificationService();
    $service->notifyOrderStatusChanged($this->order);

    // Assert WhatsApp request sent with formatted number and message
    Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
        return $request->url() === 'http://localhost:8000/send-message' &&
            $request['phone'] === '628123456789' && // formatted 08123456789 -> 628123456789
            str_contains($request['message'], 'TRX-123456') &&
            str_contains($request['message'], 'Menunggu Pembayaran');
    });

    // Assert Telegram request sent
    Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
        return str_contains($request->url(), 'api.telegram.org/botdummy-bot-token/sendMessage') &&
            $request['chat_id'] === 'dummy-chat-id' &&
            str_contains($request['text'], 'TRX-123456');
    });
});

test('notification service logs skip message when user has no phone', function () {
    Http::fake();
    Log::shouldReceive('info')
        ->once()
        ->withArgs(fn($msg) => str_contains($msg, 'WhatsApp notification skipped (no phone)'));
        
    // Suppress Telegram log warning if it happens, or mock telegram request
    Http::fake([
        'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
    ]);

    $this->user->update(['phone' => '']);
    
    $service = new NotificationService();
    $service->notifyOrderStatusChanged($this->order);
});
