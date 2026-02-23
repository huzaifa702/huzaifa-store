<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $topProducts = OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(total) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        $topCustomers = Order::select('user_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total) as total_spent')
            ->where('status', '!=', 'cancelled')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('topProducts', 'topCustomers'));
    }

    public function topProducts()
    {
        $topProducts = OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(total) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(20)
            ->get();

        return view('admin.reports.top-products', compact('topProducts'));
    }

    public function topCustomers()
    {
        $topCustomers = Order::select('user_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total) as total_spent')
            ->where('status', '!=', 'cancelled')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->take(20)
            ->get();

        return view('admin.reports.top-customers', compact('topCustomers'));
    }
}
