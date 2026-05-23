<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerSettingController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Payment\MidtransCallbackController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AdminProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SystemUpdateController as AdminSystemUpdateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Katalog & Produk)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product:slug}', [HomeController::class, 'show'])->name('products.show');

// Static Pages
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/refund-policy', [PageController::class, 'refundPolicy'])->name('pages.refund-policy');

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
| Customer Order & Settings Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');

    // Pengaturan Pelanggan
    Route::get('/settings', [CustomerSettingController::class, 'index'])->name('customer.settings');
    Route::put('/settings/profile', [CustomerSettingController::class, 'updateProfile'])->name('customer.profile.update');

    // Alamat Pengiriman
    Route::post('/settings/address', [CustomerSettingController::class, 'storeAddress'])->name('customer.address.store');
    Route::put('/settings/address/{address}', [CustomerSettingController::class, 'updateAddress'])->name('customer.address.update');
    Route::delete('/settings/address/{address}', [CustomerSettingController::class, 'destroyAddress'])->name('customer.address.destroy');

    // Rekening Bank Pengembalian Dana
    Route::post('/settings/bank-account', [CustomerSettingController::class, 'storeBankAccount'])->name('customer.bank.store');
    Route::put('/settings/bank-account/{bankAccount}', [CustomerSettingController::class, 'updateBankAccount'])->name('customer.bank.update');
    Route::delete('/settings/bank-account/{bankAccount}', [CustomerSettingController::class, 'destroyBankAccount'])->name('customer.bank.destroy');

    // API Wilayah Indonesia
    Route::get('/api/provinces', [WilayahController::class, 'getProvinces'])->name('api.provinces');
    Route::get('/api/cities/{provinceCode}', [WilayahController::class, 'getCities'])->name('api.cities');
    Route::get('/api/districts/{cityCode}', [WilayahController::class, 'getDistricts'])->name('api.districts');
    Route::get('/api/villages/{districtCode}', [WilayahController::class, 'getVillages'])->name('api.villages');
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

    // Pengaturan Profile Admin
    Route::get('/settings/profile', [AdminProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::patch('/settings/profile', [AdminProfileController::class, 'update'])->name('settings.profile.update');
    Route::put('/settings/profile/password', [AdminProfileController::class, 'updatePassword'])->name('settings.profile.updatePassword');

    // System Update
    Route::get('/system', [AdminSystemUpdateController::class, 'index'])->name('system.index');
    Route::post('/system/save-settings', [AdminSystemUpdateController::class, 'saveSettings'])->name('system.saveSettings');
    Route::post('/system/pull-update', [AdminSystemUpdateController::class, 'pullUpdate'])->name('system.pullUpdate');
    Route::post('/system/clear-cache', [AdminSystemUpdateController::class, 'clearCache'])->name('system.clearCache');
    Route::post('/system/run-migrations', [AdminSystemUpdateController::class, 'runMigrations'])->name('system.runMigrations');
    Route::post('/system/optimize', [AdminSystemUpdateController::class, 'optimize'])->name('system.optimize');
    Route::post('/system/composer-update', [AdminSystemUpdateController::class, 'composerUpdate'])->name('system.composerUpdate');
    Route::post('/system/storage-link', [AdminSystemUpdateController::class, 'createStorageLink'])->name('system.storageLink');
    Route::post('/system/info', [AdminSystemUpdateController::class, 'systemInfo'])->name('system.info');
});

/*
|--------------------------------------------------------------------------
| Payment Callback Routes (No CSRF - External Callbacks)
|--------------------------------------------------------------------------
*/
// Midtrans HTTP Notification (Webhook)
Route::post('/webhook/midtrans/notification', [MidtransCallbackController::class, 'notification'])
    ->name('midtrans.notification');

// Midtrans Webhook Test Endpoint (GET for testing)
Route::get('/webhook/midtrans/notification', [MidtransCallbackController::class, 'test'])
    ->name('midtrans.notification.test');

// Midtrans Redirect URLs (After Payment)
Route::get('/payment/midtrans/finish', [MidtransCallbackController::class, 'finish'])
    ->name('midtrans.finish');
Route::get('/payment/midtrans/unfinish', [MidtransCallbackController::class, 'unfinish'])
    ->name('midtrans.unfinish');
Route::get('/payment/midtrans/error', [MidtransCallbackController::class, 'error'])
    ->name('midtrans.error');

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
