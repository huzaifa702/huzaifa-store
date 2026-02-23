@extends('layouts.app')
@section('title', 'My Wishlist')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8 animate-on-scroll">
        <div>
            <h1 class="text-3xl font-bold gradient-text">My Wishlist</h1>
            <p class="text-gray-500 mt-1">{{ $wishlists->total() }} items saved</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all hover:scale-105 btn-glow">
            Browse Products →
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 product-grid">
        @forelse($wishlists as $item)
            @include('partials.product-card', ['product' => $item->product])
        @empty
            <div class="col-span-full text-center py-20 animate-on-scroll">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-red-500/20 to-pink-500/10 rounded-full flex items-center justify-center animate-float">
                    <span class="text-5xl">❤️</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-300">Your wishlist is empty</h3>
                <p class="text-gray-500 mt-2">Save products you love by clicking the heart icon!</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-6 px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all hover:scale-105 btn-glow">
                    Start Shopping →
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $wishlists->links() }}</div>
</div>
@endsection
