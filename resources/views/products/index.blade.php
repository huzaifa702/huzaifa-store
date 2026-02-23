@extends('layouts.app')
@section('title', 'Products')
@section('meta_description', 'Browse our complete catalog of premium products')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6 animate-on-scroll">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-400 transition-colors">Home</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="text-gray-300 font-medium">Products</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-64 flex-shrink-0">
            <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 sticky top-24 animate-on-scroll">
                <h3 class="font-bold text-lg mb-4 text-white">Filters</h3>
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-sm text-gray-400 mb-3 uppercase tracking-wider">Category</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="text-brand-500 bg-dark-800 border-dark-600 focus:ring-brand-500">
                                <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">All Categories</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }} class="text-brand-500 bg-dark-800 border-dark-600 focus:ring-brand-500">
                                <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-sm text-gray-400 mb-3 uppercase tracking-wider">Price Range</h4>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-sm text-gray-400 mb-3 uppercase tracking-wider">Sort By</h4>
                        <select name="sort" class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-brand-500/20 transition-all text-sm">Apply Filters</button>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6 animate-on-scroll">
                <p class="text-gray-500 text-sm">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 product-grid">
                @forelse($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-bold text-gray-400">No products found</h3>
                        <p class="text-gray-400 mt-2">Try adjusting your filters</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
