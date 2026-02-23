<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's stats
        $todayRevenue = Order::where('status', '!=', 'cancelled')->whereDate('created_at', today())->sum('total');
        $todayOrders = Order::whereDate('created_at', today())->count();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalCategories = Category::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $topProducts = OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(total) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $recentActivities = ActivityLog::latest()->take(10)->get();

        $monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthlySales = Order::where('status', '!=', 'cancelled')
            ->selectRaw("MONTH(created_at) as month_num, SUM(total) as revenue, COUNT(*) as orders")
            ->groupByRaw("MONTH(created_at)")
            ->orderByRaw("MONTH(created_at)")
            ->get()
            ->map(function ($item) use ($monthNames) {
                $item->month = $monthNames[intval($item->month_num) - 1] ?? $item->month_num;
                return $item;
            });

        // Category product distribution
        $categoryDistribution = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->get()
            ->map(fn($c) => ['name' => $c->name, 'count' => $c->active_products_count]);

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'pendingOrders',
            'activeProducts',
            'totalCategories',
            'recentOrders',
            'topProducts',
            'recentActivities',
            'monthlySales',
            'todayRevenue',
            'todayOrders',
            'categoryDistribution'
        ));
    }
}
