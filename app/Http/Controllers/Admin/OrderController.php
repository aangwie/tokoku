<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filterType = $request->get('filter_type', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Eager load 'user' to avoid N+1 queries when showing customer names
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        // Calculate revenue statistics
        // Only count paid, shipping, and completed orders (exclude pending and cancelled)
        $revenueQuery = Order::whereIn('status', ['paid', 'shipping', 'completed']);

        // Total revenue (all time)
        $totalRevenue = (clone $revenueQuery)->sum('total_price');

        // Current month revenue
        $currentMonthRevenue = (clone $revenueQuery)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        // Filtered revenue based on selected filter
        $filteredRevenue = $totalRevenue;
        $filterLabel = 'Semua Waktu';

        if ($filterType === 'month') {
            $filteredRevenue = $currentMonthRevenue;
            $filterLabel = 'Bulan ' . Carbon::now()->format('F Y');
        } elseif ($filterType === 'custom' && $startDate && $endDate) {
            $filteredRevenue = (clone $revenueQuery)
                ->whereBetween('created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ])
                ->sum('total_price');
            $filterLabel = Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y');
        }

        // Count orders by status
        $orderCounts = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact(
            'orders',
            'totalRevenue',
            'currentMonthRevenue',
            'filteredRevenue',
            'filterLabel',
            'filterType',
            'startDate',
            'endDate',
            'orderCounts'
        ));
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
