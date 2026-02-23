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

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'newest' => $query->orderBy('created_at', 'desc'),
                'popular' => $query->orderBy('views_count', 'desc'),
                default => $query->orderBy('created_at', 'desc'),
            };
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
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
