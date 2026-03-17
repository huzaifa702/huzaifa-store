<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $query = $category->products()->where('is_active', true)->with('primaryImage', 'category');

        // Apply sorting based on the "sort" query parameter
        $sort = $request->input('sort', 'all'); // Default to 'all' (view all products)

        match ($sort) {
            'newest'     => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'price_low'  => $query->orderBy('price', 'asc')->orderBy('id', 'asc'),
            'price_high' => $query->orderBy('price', 'desc')->orderBy('id', 'desc'),
            default      => $query->orderBy('id', 'desc'), // "all" — just show all by ID
        };

        $products = $query->paginate(24); // Show more products per page
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('categories.show', compact('category', 'products', 'categories'));
    }
}
