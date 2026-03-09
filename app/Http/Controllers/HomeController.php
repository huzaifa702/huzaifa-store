<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('primaryImage', 'category')
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $latestProducts = Product::where('is_active', true)
            ->with('primaryImage', 'category')
            ->latest()
            ->take(8)
            ->get();

        $saleProducts = Product::where('is_active', true)
            ->whereNotNull('sale_price')
            ->with('primaryImage', 'category')
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'latestProducts', 'saleProducts'));
    }
}
