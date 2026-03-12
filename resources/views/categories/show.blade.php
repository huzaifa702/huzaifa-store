@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-400 transition-colors">Home</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="text-gray-300 font-medium">{{ $category->name }}</span>
    </nav>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800/50 to-dark-900 rounded-3xl p-8 md:p-12 text-white mb-8 border border-brand-800/30 relative overflow-hidden" style="animation: fadeSlideUp 0.7s ease-out">
        <div class="absolute top-0 right-0 w-48 h-48 bg-brand-500/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-neon-cyan/5 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 md:gap-10">
            @if($category->image)
                <div class="w-28 h-28 md:w-36 md:h-36 rounded-2xl overflow-hidden shadow-2xl shadow-brand-500/20 ring-2 ring-white/10 flex-shrink-0" style="animation: zoomIn 0.6s cubic-bezier(0.23, 1, 0.32, 1) 0.2s both">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                </div>
            @endif
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-400 mt-2 max-w-2xl">{{ $category->description }}</p>
                @endif
                <p class="text-gray-500 mt-2 text-sm">{{ $products->total() }} products found</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    </style>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <span class="text-gray-500 text-sm">Sort by:</span>
            <div class="flex gap-2">
                @foreach(['newest' => 'Newest', 'price_low' => 'Price: Low to High', 'price_high' => 'Price: High to Low'] as $val => $label)
                    <a href="{{ route('categories.show', $category) }}?sort={{ $val }}" class="px-3 py-1 rounded-lg text-sm font-medium {{ request('sort', 'newest') == $val ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'bg-dark-800 text-gray-400 hover:bg-dark-700 border border-dark-700' }} transition-all">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 product-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-400">No products in this category</h3>
                <a href="{{ route('products.index') }}" class="text-brand-400 font-semibold mt-2 inline-block hover:text-brand-300">Browse all products →</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
