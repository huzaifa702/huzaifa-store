<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $query = $category->activeProducts()->with('primaryImage');

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'newest' => $query->orderBy('created_at', 'desc'),
                default => $query->orderBy('created_at', 'desc'),
            };
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('categories.show', compact('category', 'products', 'categories'));
    }
}
