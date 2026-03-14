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
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
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

            $recentActivities = collect();
            try {
                $recentActivities = ActivityLog::latest()->take(10)->get();
            } catch (\Throwable $e) {
                // Activity log table might not exist
            }

            // DB-agnostic monthly sales — works with MySQL, SQLite, PostgreSQL
            $monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            try {
                $orders = Order::where('status', '!=', 'cancelled')
                    ->whereNotNull('created_at')
                    ->get(['total', 'created_at']);

                $monthlySales = $orders->groupBy(function ($order) {
                    return (int) $order->created_at->format('n'); // month number 1-12
                })->map(function ($group, $monthNum) use ($monthNames) {
                    return (object) [
                        'month_num' => $monthNum,
                        'month' => $monthNames[$monthNum - 1] ?? $monthNum,
                        'revenue' => $group->sum('total'),
                        'orders' => $group->count(),
                    ];
                })->sortBy('month_num')->values();
            } catch (\Throwable $e) {
                $monthlySales = collect();
            }

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
                'monthlySales'
            ));
        } catch (\Throwable $e) {
            Log::error('Admin Dashboard error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            // Fallback — show dashboard with empty data so admin can still navigate
            return view('admin.dashboard', [
                'totalRevenue' => 0,
                'totalOrders' => 0,
                'totalProducts' => 0,
                'totalCustomers' => 0,
                'pendingOrders' => 0,
                'activeProducts' => 0,
                'totalCategories' => 0,
                'recentOrders' => collect(),
                'topProducts' => collect(),
                'recentActivities' => collect(),
                'monthlySales' => collect(),
            ]);
        }
    }
}
