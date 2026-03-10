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
    <div class="bg-gradient-to-r from-brand-900 via-brand-800/50 to-dark-900 rounded-3xl p-8 md:p-12 text-white mb-8 animate-on-scroll border border-brand-800/30 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-brand-500/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-400 mt-2 max-w-2xl">{{ $category->description }}</p>
            @endif
            <p class="text-gray-500 mt-2 text-sm">{{ $products->total() }} products found</p>
        </div>
    </div>

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
