<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller untuk halaman pesanan pelanggan (customer-facing).
 *
 * Berbeda dengan Admin\OrderController yang mengelola semua pesanan,
 * controller ini hanya menampilkan pesanan milik user yang login.
 */
class OrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan milik user yang sedang login.
     *
     * Menggunakan eager loading orderItems.product
     * untuk menghindari N+1 query problem.
     */
    public function index(): View
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail satu pesanan.
     *
     * Memastikan user hanya bisa melihat pesanan miliknya sendiri.
     * Jika payment gateway adalah Midtrans, pass client key ke view
     * agar Snap.js bisa digunakan untuk pembayaran.
     */
    public function show(Order $order): View
    {
        // Pastikan user hanya bisa mengakses pesanannya sendiri (kecuali admin)
        $user = Auth::user();
        $isOwner = $order->user_id == Auth::id(); // Use == for type-safe comparison
        $isAdmin = $user && isset($user->role) && $user->role === 'admin';
        
        // Debug logging (remove after testing)
        \Log::info('Order Access Check', [
            'order_id' => $order->id,
            'order_user_id' => $order->user_id,
            'order_user_id_type' => gettype($order->user_id),
            'auth_user_id' => Auth::id(),
            'auth_user_id_type' => gettype(Auth::id()),
            'user_role' => $user->role ?? 'no role',
            'isOwner' => $isOwner,
            'isAdmin' => $isAdmin,
        ]);
        
        if (!$isOwner && !$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Eager load semua relasi yang dibutuhkan di halaman detail
        $order->load(['user', 'coupon', 'orderItems.product']);

        // Siapkan Midtrans client key jika payment gateway-nya Midtrans (dari database)
        $midtransClientKey = null;
        if ($order->payment_gateway === 'midtrans') {
            $midtransClientKey = MidtransService::getClientKey();
        }

        // Ambil daftar rekening bank dari database untuk transfer
        $bankAccounts = json_decode(Setting::get('bank_accounts', '[]'), true) ?: [];

        return view('orders.show', compact('order', 'midtransClientKey', 'bankAccounts'));
    }
}
