<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Eager load 'user' to avoid N+1 queries when showing customer names
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Eager load related customer, coupon, and products in order items
        $order->load(['user', 'coupon', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipping,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?: $order->tracking_number,
        ]);

        // Trigger Event & Listener when status changes
        if ($oldStatus !== $request->status) {
            event(new OrderStatusChanged($order));
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
