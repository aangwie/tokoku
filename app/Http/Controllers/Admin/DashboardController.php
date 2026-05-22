<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin analytics dashboard.
     */
    public function index()
    {
        // Revenue: Sum of total_price for successful orders (paid, shipping, completed)
        $totalRevenue = Order::whereIn('status', ['paid', 'shipping', 'completed'])
            ->sum('total_price');

        // Total orders count
        $totalOrders = Order::count();

        // Active products count
        $totalProducts = Product::count();

        // Categories count
        $totalCategories = Category::count();

        // Recent orders with eager-loaded user (anti-N+1)
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top selling products: group by product_id, sum quantity, eager load product.category
        $topProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product.category')
            ->get();

        // Monthly sales for the current year (for a simple chart/stats)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthRaw = $isSqlite ? "CAST(strftime('%m', created_at) as integer)" : "MONTH(created_at)";

        $monthlySales = Order::whereIn('status', ['paid', 'shipping', 'completed'])
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw($monthRaw . ' as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy(DB::raw($monthRaw))
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'recentOrders',
            'topProducts',
            'monthlySales'
        ));
    }
}
