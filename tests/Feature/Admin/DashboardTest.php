<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081234567891',
        ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Admin');
    }

    public function test_customer_cannot_access_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_dashboard_displays_correct_revenue(): void
    {
        // Create a category and product
        $category = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100000,
            'weight' => 500,
            'stock' => 10,
        ]);

        // Create paid order
        $paidOrder = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-001',
            'subtotal' => 200000,
            'discount_amount' => 0,
            'total_price' => 200000,
            'shipping_cost' => 10000,
            'status' => 'paid',
            'payment_gateway' => 'midtrans',
            'shipping_address' => 'Jl. Test',
        ]);

        // Create pending order (should NOT count in revenue)
        $pendingOrder = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-002',
            'subtotal' => 150000,
            'discount_amount' => 0,
            'total_price' => 150000,
            'shipping_cost' => 10000,
            'status' => 'pending',
            'payment_gateway' => 'xendit',
            'shipping_address' => 'Jl. Test 2',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Revenue should only include the paid order
        $response->assertSee('200.000');
    }

    public function test_dashboard_displays_top_selling_products(): void
    {
        $category = Category::create(['name' => 'Pakaian', 'slug' => 'pakaian']);
        $product1 = Product::create([
            'category_id' => $category->id,
            'name' => 'Kemeja Best',
            'slug' => 'kemeja-best',
            'price' => 150000,
            'weight' => 300,
            'stock' => 20,
        ]);
        $product2 = Product::create([
            'category_id' => $category->id,
            'name' => 'Celana Casual',
            'slug' => 'celana-casual',
            'price' => 200000,
            'weight' => 400,
            'stock' => 15,
        ]);

        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-003',
            'subtotal' => 350000,
            'discount_amount' => 0,
            'total_price' => 350000,
            'shipping_cost' => 15000,
            'status' => 'completed',
            'payment_gateway' => 'midtrans',
            'shipping_address' => 'Jl. Test',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 5,
            'price' => 150000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 2,
            'price' => 200000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Kemeja Best');
        $response->assertSee('Celana Casual');
        $response->assertSee('Produk Terlaris');
    }

    public function test_dashboard_displays_recent_orders(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-RECENT',
            'subtotal' => 500000,
            'discount_amount' => 0,
            'total_price' => 500000,
            'shipping_cost' => 20000,
            'status' => 'shipping',
            'payment_gateway' => 'xendit',
            'shipping_address' => 'Jl. Recent',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('ORD-RECENT');
        $response->assertSee('Pesanan Terbaru');
    }

    public function test_dashboard_displays_product_and_category_counts(): void
    {
        Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);
        Category::create(['name' => 'Cat B', 'slug' => 'cat-b']);

        $cat = Category::first();
        Product::create([
            'category_id' => $cat->id,
            'name' => 'Produk Satu',
            'slug' => 'produk-satu',
            'price' => 50000,
            'weight' => 100,
            'stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        // The counts appear in the stat cards
        $response->assertSee('Produk aktif di katalog');
        $response->assertSee('Kategori produk tersedia');
    }
}
