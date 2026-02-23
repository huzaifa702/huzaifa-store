@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-400 transition-colors">Home</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="text-gray-300 font-medium">{{ $category->name }}</span>
    </nav>

    <!-- Category Header — 3D Animated -->
    <div class="relative bg-gradient-to-r from-brand-900 via-brand-800/50 to-dark-900 rounded-3xl p-8 md:p-12 text-white mb-8 border border-brand-800/30 overflow-hidden" style="perspective: 1200px;">
        <!-- Animated Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="particle particle-cyan" style="top:15%;left:10%;animation-delay:0s;"></div>
            <div class="particle particle-purple" style="top:60%;left:25%;animation-delay:1s;"></div>
            <div class="particle particle-blue" style="top:30%;left:70%;animation-delay:2s;"></div>
            <div class="particle particle-pink" style="top:75%;left:80%;animation-delay:3s;"></div>
            <div class="particle particle-gold" style="top:20%;left:50%;animation-delay:4s;"></div>
            <div class="particle particle-cyan" style="top:80%;left:45%;animation-delay:5s;"></div>
            <div class="particle particle-purple" style="top:40%;left:90%;animation-delay:1.5s;"></div>
        </div>
        <!-- Glowing orbs -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/8 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl animate-float-slow"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-neon-cyan/5 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl animate-float-slow" style="animation-delay: 3s;"></div>

        <div class="grid md:grid-cols-2 gap-8 items-center relative z-10">
            <div class="animate-slide-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-brand-300 mb-4 animate-glow-ring">
                    <span>🏷️</span> {{ $products->total() }} Products
                </div>
                <h1 class="text-4xl md:text-5xl font-black leading-tight">
                    <span class="gradient-text">{{ $category->name }}</span>
                </h1>
                @if($category->description)
                    <p class="text-gray-400 mt-3 max-w-lg text-base leading-relaxed">{{ $category->description }}</p>
                @endif
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('categories.show', $category) }}?sort=newest" class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300 btn-glow hover:scale-105">
                        Latest →
                    </a>
                    <a href="{{ route('categories.show', $category) }}?sort=price_low" class="px-5 py-2.5 border border-white/10 text-white rounded-xl text-sm font-semibold hover:bg-white/5 transition-all duration-300">
                        Best Price
                    </a>
                </div>
            </div>
            @if($category->image)
                <div class="hidden md:flex justify-center items-center relative animate-slide-right" style="perspective: 800px;">
                    <div class="cat-hero-img w-56 h-56 rounded-3xl overflow-hidden shadow-2xl shadow-brand-500/30 ring-1 ring-white/[0.08]">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -inset-8 bg-gradient-to-r from-brand-500/10 via-neon-cyan/5 to-neon-purple/10 rounded-full blur-3xl -z-10 cat-hero-glow"></div>
                    <!-- Orbiting dots -->
                    <div class="absolute inset-0 cat-orbit">
                        <div class="absolute w-2 h-2 bg-brand-400 rounded-full shadow-lg shadow-brand-400/50" style="top:0;left:50%;"></div>
                        <div class="absolute w-1.5 h-1.5 bg-neon-cyan rounded-full shadow-lg shadow-neon-cyan/50" style="top:50%;right:0;"></div>
                        <div class="absolute w-2 h-2 bg-neon-purple rounded-full shadow-lg shadow-neon-purple/50" style="bottom:0;left:50%;"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .cat-hero-img {
            animation: catImgFloat 5s ease-in-out infinite;
            transform-style: preserve-3d;
        }
        @keyframes catImgFloat {
            0%, 100% { transform: translateY(0) rotateY(0deg) rotateX(0deg) scale(1); }
            25% { transform: translateY(-10px) rotateY(5deg) rotateX(2deg) scale(1.02); }
            50% { transform: translateY(-16px) rotateY(0deg) rotateX(-2deg) scale(1.04); }
            75% { transform: translateY(-8px) rotateY(-5deg) rotateX(1deg) scale(1.01); }
        }
        .cat-hero-glow {
            animation: catGlow 3s ease-in-out infinite alternate;
        }
        @keyframes catGlow {
            0% { opacity: 0.3; transform: scale(0.9); }
            100% { opacity: 0.8; transform: scale(1.15); }
        }
        .cat-orbit {
            animation: orbSpin 12s linear infinite;
        }
        @keyframes orbSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Sort Bar -->
    <div class="flex items-center justify-between mb-8 animate-on-scroll">
        <div class="flex items-center gap-4">
            <span class="text-gray-500 text-sm font-medium">Sort by</span>
            <div class="flex gap-2">
                @foreach(['newest' => '🕐 Newest', 'price_low' => '💰 Price ↑', 'price_high' => '💎 Price ↓'] as $val => $label)
                    <a href="{{ route('categories.show', $category) }}?sort={{ $val }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('sort', 'newest') == $val ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg shadow-brand-500/20' : 'glass-card text-gray-400 hover:text-white' }} transition-all duration-300 hover:scale-105">{{ $label }}</a>
                @endforeach
            </div>
        </div>
        <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
            <span>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span>
            <span>of {{ $products->total() }}</span>
        </div>
    </div>

    <!-- Product Grid with 3D Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 product-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="col-span-full text-center py-20 animate-on-scroll">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-brand-500/20 to-neon-cyan/10 rounded-full flex items-center justify-center animate-float">
                    <span class="text-5xl">🔍</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-300">No products in this category</h3>
                <p class="text-gray-500 mt-2">Check back soon for new arrivals!</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-6 px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all hover:scale-105 btn-glow">
                    Browse All Products →
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
