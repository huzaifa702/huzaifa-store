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

        // Build hero slides from categories with real product images
        $heroSlides = [];
        $slideConfig = [
            'Electronics' => ['badge' => '🔥 Hot Collection', 'title' => 'Discover <span class="gradient-text">Premium</span> Electronics', 'desc' => 'Explore our curated collection of top-tier gadgets and devices.'],
            'Fashion' => ['badge' => '✨ New Arrivals', 'title' => 'Latest <span class="gradient-text-fire">Fashion</span> Trends', 'desc' => 'Stay ahead of the curve with our newest fashion arrivals. Style redefined.'],
            'Home & Living' => ['badge' => '🏠 Home Essentials', 'title' => 'Transform Your <span class="gradient-text-emerald">Living Space</span>', 'desc' => 'Beautiful home decor and essentials that make every room feel special.'],
            'Sports' => ['badge' => '💪 Active Life', 'title' => 'Gear Up for <span class="gradient-text-rose">Adventure</span>', 'desc' => 'Professional sports equipment and activewear for every athlete.'],
            'Beauty' => ['badge' => '💎 Premium Beauty', 'title' => 'Glow with <span class="gradient-text-gold">Confidence</span>', 'desc' => 'Premium skincare and beauty essentials for your daily routine.'],
            'Books' => ['badge' => '📚 Knowledge Hub', 'title' => 'Expand Your <span class="gradient-text">Mind</span>', 'desc' => 'Discover books that inspire, educate, and transform your thinking.'],
        ];

        foreach ($categories as $cat) {
            $config = $slideConfig[$cat->name] ?? ['badge' => '🛍️ Shop Now', 'title' => $cat->name, 'desc' => $cat->description ?? 'Explore our collection.'];
            $product = Product::where('category_id', $cat->id)
                ->where('is_active', true)
                ->with('primaryImage')
                ->inRandomOrder()
                ->first();
            $heroSlides[] = [
                'badge' => $config['badge'],
                'title' => $config['title'],
                'desc' => $config['desc'],
                'image' => $product ? $product->primary_image_url : 'https://picsum.photos/seed/' . $cat->slug . '/500/500',
                'category_slug' => $cat->slug,
            ];
        }

        return view('home', compact('featuredProducts', 'categories', 'latestProducts', 'saleProducts', 'heroSlides'));
    }
}
