<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('primaryImage', 'category');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Apply sorting — default is 'all' (view all products by ID)
        $sort = $request->input('sort', 'all');

        match ($sort) {
            'newest'     => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'price_low'  => $query->orderBy('price', 'asc')->orderBy('id', 'asc'),
            'price_high' => $query->orderBy('price', 'desc')->orderBy('id', 'desc'),
            default      => $query->orderBy('id', 'desc'), // "all" — just show all by ID
        };

        $products = $query->paginate(24); // Show more products per page
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->increment('views_count');
        $product->load(['images', 'category', 'approvedReviews.user']);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('primaryImage')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
