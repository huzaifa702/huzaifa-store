<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('primaryImage', 'category');

        if ($request->filled('q')) {
            // Normalize: trim, collapse multiple spaces, lowercase
            $rawSearch = preg_replace('/\s+/', ' ', trim($request->q));
            $search = strtolower($rawSearch);

            // Split into individual words for multi-word matching
            $words = array_filter(explode(' ', $search));

            if (!empty($words)) {
                // Each word must appear in at least one searchable column (AND logic)
                foreach ($words as $word) {
                    $escapedWord = '%' . $word . '%';
                    $query->where(function ($q) use ($escapedWord) {
                        $q->whereRaw('LOWER(name) LIKE ?', [$escapedWord])
                          ->orWhereRaw('LOWER(short_description) LIKE ?', [$escapedWord])
                          ->orWhereRaw('LOWER(description) LIKE ?', [$escapedWord])
                          ->orWhereRaw('LOWER(sku) LIKE ?', [$escapedWord]);
                    });
                }

                // Order by relevance: exact phrase in name > partial name match > description match
                $exactPhrase = '%' . $search . '%';
                $firstWord = '%' . $words[0] . '%';
                $query->orderByRaw("
                    CASE
                        WHEN LOWER(name) LIKE ? THEN 1
                        WHEN LOWER(name) LIKE ? THEN 2
                        WHEN LOWER(short_description) LIKE ? THEN 3
                        WHEN LOWER(description) LIKE ? THEN 4
                        ELSE 5
                    END ASC
                ", [$exactPhrase, $firstWord, $exactPhrase, $exactPhrase]);
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('search.index', compact('products', 'categories'));
    }
}
