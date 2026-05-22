<?php

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use App\Services\XenditService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'phone' => '081234567890'
    ]);
    
    $this->category = Category::create([
        'name' => 'Elektronik',
        'slug' => 'elektronik'
    ]);

    $this->product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Smartphone X',
        'slug' => 'smartphone-x',
        'description' => 'A great smartphone',
        'price' => 5000000.00,
        'weight' => 200,
        'stock' => 10,
    ]);
});

test('guest cannot access cart routes', function () {
    $this->get(route('cart.index'))->assertRedirect(route('login'));
    $this->post(route('cart.add', $this->product->id))->assertRedirect(route('login'));
});

test('user can view empty cart', function () {
    $response = $this->actingAs($this->user)->get(route('cart.index'));
    
    $response->assertStatus(200);
    $response->assertSee('Keranjang Anda Kosong');
});

test('user can add product to cart', function () {
    $response = $this->actingAs($this->user)
        ->post(route('cart.add', $this->product->id), ['quantity' => 2]);

    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('success');
    
    $cart = session('cart');
    expect($cart)->toHaveKey($this->product->id);
    expect($cart[$this->product->id]['quantity'])->toBe(2);
    expect($cart[$this->product->id]['name'])->toBe('Smartphone X');
});

test('user cannot add product exceeding stock to cart', function () {
    $response = $this->actingAs($this->user)
        ->post(route('cart.add', $this->product->id), ['quantity' => 15]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Stok produk tidak mencukupi!');
    expect(session('cart'))->toBeNull();
});

test('user can update cart quantity', function () {
    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)
        ->patch(route('cart.update', $this->product->id), ['quantity' => 3]);

    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('success');
    expect(session('cart')[$this->product->id]['quantity'])->toBe(3);
});

test('user can remove product from cart', function () {
    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)
        ->delete(route('cart.remove', $this->product->id));

    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('success');
    expect(session('cart'))->toBeEmpty();
});

test('user can apply valid coupon', function () {
    $coupon = Coupon::create([
        'code' => 'PROMO50',
        'type' => 'percentage',
        'value' => 10,
        'min_order' => 1000000,
        'expires_at' => now()->addDays(7),
    ]);

    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)
        ->post(route('cart.coupon.apply'), ['code' => 'PROMO50']);

    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('success');
    
    $sessionCoupon = session('coupon');
    expect($sessionCoupon['code'])->toBe('PROMO50');
    expect($sessionCoupon['value'])->toBe('10.00');
});

test('user cannot apply expired coupon', function () {
    $coupon = Coupon::create([
        'code' => 'PROMOEXPIRED',
        'type' => 'percentage',
        'value' => 10,
        'min_order' => 100000,
        'expires_at' => now()->subDays(1),
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('cart.coupon.apply'), ['code' => 'PROMOEXPIRED']);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Kupon ini sudah kedaluwarsa!');
    expect(session('coupon'))->toBeNull();
});

test('user cannot apply coupon with insufficient spend', function () {
    $coupon = Coupon::create([
        'code' => 'BIGSPEND',
        'type' => 'fixed',
        'value' => 50000,
        'min_order' => 10000000, // 10 million minimum
        'expires_at' => now()->addDays(2),
    ]);

    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1, // Total 5 million
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)
        ->post(route('cart.coupon.apply'), ['code' => 'BIGSPEND']);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(session('coupon'))->toBeNull();
});

test('user can render checkout page', function () {
    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)->get(route('cart.checkout'));
    
    $response->assertStatus(200);
    $response->assertSee('Alamat Pengiriman');
});

test('user can process checkout with Midtrans payment gateway', function () {
    // Mock MidtransService
    $midtransMock = Mockery::mock(MidtransService::class);
    $midtransMock->shouldReceive('createSnapToken')
        ->once()
        ->andReturn('dummy-snap-token-123');
    $this->app->instance(MidtransService::class, $midtransMock);

    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 2,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)->post(route('cart.checkout.process'), [
        'shipping_address' => 'Jl. Merdeka No. 10, Jakarta',
        'payment_gateway' => 'midtrans',
    ]);

    // Verify order created in db
    $order = Order::first();
    expect($order)->not->toBeNull();
    expect($order->user_id)->toBe($this->user->id);
    expect($order->payment_gateway)->toBe('midtrans');
    expect($order->payment_reference)->toBe('dummy-snap-token-123');
    expect($order->shipping_address)->toBe('Jl. Merdeka No. 10, Jakarta');
    
    // Total price = (5,000,000 * 2) + 15,000 (shipping for 400g) = 10,015,000
    expect((int)$order->total_price)->toBe(10015000);
    
    // Check order item
    $orderItem = OrderItem::first();
    expect($orderItem->product_id)->toBe($this->product->id);
    expect($orderItem->quantity)->toBe(2);

    // Check stock decremented: 10 - 2 = 8
    $this->product->refresh();
    expect($this->product->stock)->toBe(8);

    // Check cart cleared in session
    expect(session('cart'))->toBeNull();

    $response->assertRedirect(route('orders.show', $order->id));
    $response->assertSessionHas('success');
});

test('user can process checkout with Xendit payment gateway', function () {
    // Mock XenditService
    $xenditMock = Mockery::mock(XenditService::class);
    $xenditMock->shouldReceive('createInvoice')
        ->once()
        ->andReturn('https://checkout.xendit.co/v2/invoices/dummy-invoice-123');
    $this->app->instance(XenditService::class, $xenditMock);

    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)->post(route('cart.checkout.process'), [
        'shipping_address' => 'Jl. Veteran No. 5, Bandung',
        'payment_gateway' => 'xendit',
    ]);

    // Verify order created in db
    $order = Order::first();
    expect($order)->not->toBeNull();
    expect($order->payment_gateway)->toBe('xendit');
    expect($order->payment_reference)->toBe('https://checkout.xendit.co/v2/invoices/dummy-invoice-123');

    // Check cart cleared in session
    expect(session('cart'))->toBeNull();

    $response->assertRedirect('https://checkout.xendit.co/v2/invoices/dummy-invoice-123');
});

test('user can process checkout with Bank Transfer', function () {
    // Set payment method to transfer
    \App\Models\Setting::set('payment_method', 'transfer');

    $cart = [
        $this->product->id => [
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => $this->product->price,
            'weight' => $this->product->weight,
            'image' => $this->product->image,
            'slug' => $this->product->slug,
        ]
    ];
    session(['cart' => $cart]);

    $response = $this->actingAs($this->user)->post(route('cart.checkout.process'), [
        'shipping_address' => 'Jl. Veteran No. 5, Bandung',
        'payment_gateway' => 'transfer',
    ]);

    // Verify order created in db
    $order = Order::first();
    expect($order)->not->toBeNull();
    expect($order->payment_gateway)->toBe('transfer');
    expect($order->payment_reference)->toBeNull();

    // Check cart cleared in session
    expect(session('cart'))->toBeNull();

    $response->assertRedirect(route('orders.show', $order->id));
    $response->assertSessionHas('success', 'Pesanan berhasil dibuat! Silakan lakukan transfer bank sesuai instruksi di halaman detail pesanan.');
});
