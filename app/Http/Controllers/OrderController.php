<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        // Pastikan user hanya bisa mengakses pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Eager load semua relasi yang dibutuhkan di halaman detail
        $order->load(['user', 'coupon', 'orderItems.product']);

        // Siapkan Midtrans client key jika payment gateway-nya Midtrans
        $midtransClientKey = null;
        if ($order->payment_gateway === 'midtrans') {
            $midtransClientKey = config('services.midtrans.client_key');
        }

        return view('orders.show', compact('order', 'midtransClientKey'));
    }
}
