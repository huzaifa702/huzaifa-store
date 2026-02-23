@extends('layouts.app')
@section('title', 'Search Results')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8 animate-on-scroll">
        <h1 class="text-3xl font-bold">Search Results</h1>
        @if(request('q'))
            <p class="text-gray-500 mt-1">Showing results for "<span class="font-semibold text-gray-300">{{ request('q') }}</span>" — {{ $products->total() }} found</p>
        @endif
    </div>

    <!-- Search Bar -->
    <form action="{{ route('search') }}" method="GET" class="mb-8 animate-on-scroll">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for products..." class="w-full pl-12 pr-4 py-3 bg-dark-900 rounded-xl shadow-black/20 focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm border border-dark-700">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <select name="category" class="px-4 py-3 bg-dark-900 rounded-xl shadow-black/20 text-sm border border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Search</button>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 product-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-400">No products found</h3>
                <p class="text-gray-400 mt-2">Try a different search term or browse our categories.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 text-brand-600 font-semibold hover:underline">Browse All Products →</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
