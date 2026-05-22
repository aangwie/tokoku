<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Katalog & Produk)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product:slug}', [HomeController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Cart Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Kupon Diskon
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('cart.checkout.process');
});

/*
|--------------------------------------------------------------------------
| Customer Order Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin - Sales Analytics
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // CRUD Produk
    Route::resource('products', AdminProductController::class)->except(['show']);

    // CRUD Kupon
    Route::resource('coupons', AdminCouponController::class)->except(['show']);

    // Manajemen Pesanan
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Pengaturan Toko
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Webhook Routes (No CSRF - External Callbacks)
|--------------------------------------------------------------------------
*/
Route::prefix('webhook')->group(function () {
    Route::post('/midtrans', [WebhookController::class, 'midtrans'])->name('webhook.midtrans');
    Route::post('/xendit', [WebhookController::class, 'xendit'])->name('webhook.xendit');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze Default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $userId = \Illuminate\Support\Facades\Auth::id();

    // Stats for customer
    $totalOrders = \App\Models\Order::where('user_id', $userId)->count();
    
    $pendingOrders = \App\Models\Order::where('user_id', $userId)
        ->where('status', 'pending')
        ->count();

    $activeOrders = \App\Models\Order::where('user_id', $userId)
        ->whereIn('status', ['paid', 'shipping'])
        ->count();

    $totalSpent = \App\Models\Order::where('user_id', $userId)
        ->whereIn('status', ['paid', 'shipping', 'completed'])
        ->sum('total_price');

    // Recent orders
    $recentOrders = \App\Models\Order::with('orderItems.product')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('dashboard', compact('totalOrders', 'pendingOrders', 'activeOrders', 'totalSpent', 'recentOrders'));
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
