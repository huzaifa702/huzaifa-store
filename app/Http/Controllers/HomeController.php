<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::where('is_active', true)
                ->withCount('activeProducts')
                ->orderBy('sort_order')
                ->get();

            $featuredProducts = Product::where('is_active', true)
                ->where('is_featured', true)
                ->with('primaryImage', 'category')
                ->take(8)
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

            // Dynamic stats from DB
            $productCount = Product::where('is_active', true)->count();
            $categoryCount = $categories->count();

            // Build hero slides — one slide PER PRODUCT, grouped by category
            // So it cycles: Cat A Product 1, Cat A Product 2, ..., Cat B Product 1, Cat B Product 2, ...
            $heroSlides = [];

            // Gather all active products grouped by category
            $categoryProducts = Product::where('is_active', true)
                ->with('primaryImage', 'category')
                ->get()
                ->groupBy('category_id');

            // Dynamic style variants for hero text
            $gradientStyles = ['gradient-text', 'gradient-text-fire', 'gradient-text-emerald', 'gradient-text-rose', 'gradient-text-gold', 'gradient-text'];
            $badgeIcons = ['🔥', '✨', '🏠', '💪', '💎', '📚', '🎮', '🎨', '🛍️'];

            foreach ($categories as $catIndex => $cat) {
                $products = $categoryProducts[$cat->id] ?? collect();
                $gradientClass = $gradientStyles[$catIndex % count($gradientStyles)];
                $badgeIcon = $badgeIcons[$catIndex % count($badgeIcons)];

                if ($products->isEmpty()) {
                    // Still show the category even if no products
                    $heroSlides[] = [
                        'badge' => $badgeIcon . ' ' . $cat->name,
                        'title' => '<span class="' . $gradientClass . '">' . e($cat->name) . '</span>',
                        'desc' => $cat->description ?? 'Explore our ' . e($cat->name) . ' collection.',
                        'image' => 'https://picsum.photos/seed/' . $cat->slug . '/500/500',
                        'category_slug' => $cat->slug,
                    ];
                } else {
                    // One slide per product in this category
                    foreach ($products as $product) {
                        $heroSlides[] = [
                            'badge' => $badgeIcon . ' ' . $cat->name,
                            'title' => e($product->name),
                            'desc' => $cat->description ?? 'Explore our ' . e($cat->name) . ' collection.',
                            'image' => $product->primary_image_url ?: 'https://picsum.photos/seed/' . $cat->slug . '/500/500',
                            'category_slug' => $cat->slug,
                        ];
                    }
                }
            }

            // Fallback if no slides were generated
            if (empty($heroSlides)) {
                $heroSlides[] = [
                    'badge' => '🔥 Premium Store',
                    'title' => 'Welcome to <span class="gradient-text">Huzaifa Store</span>',
                    'desc' => 'Your premium shopping destination.',
                    'image' => 'https://picsum.photos/seed/store/500/500',
                    'category_slug' => '',
                ];
            }

            return view('home', compact('featuredProducts', 'categories', 'latestProducts', 'saleProducts', 'heroSlides', 'productCount', 'categoryCount'));
        } catch (\Exception $e) {
            // Fallback: show home page with empty data if DB query fails
            return view('home', [
                'featuredProducts' => collect(),
                'categories' => collect(),
                'latestProducts' => collect(),
                'saleProducts' => collect(),
                'heroSlides' => [
                    ['badge' => '🔥 Premium Store', 'title' => 'Welcome to <span class="gradient-text">Huzaifa Store</span>', 'desc' => 'Your premium shopping destination.', 'image' => 'https://picsum.photos/seed/store/500/500', 'category_slug' => ''],
                ],
                'productCount' => 0,
                'categoryCount' => 0,
            ]);
        }
    }
}
