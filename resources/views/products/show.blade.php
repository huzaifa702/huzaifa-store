@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-400 transition-colors">Home</a>
        <span class="text-gray-300 mx-2">/</span>
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-brand-400 transition-colors">Products</a>
        <span class="text-gray-300 mx-2">/</span>
        <a href="{{ route('categories.show', $product->category) }}" class="text-gray-500 hover:text-brand-400 transition-colors">{{ $product->category->name }}</a>
        <span class="text-gray-300 mx-2">/</span>
        <span class="text-gray-300 font-medium">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail -->
    <div class="grid md:grid-cols-2 gap-12 animate-on-scroll">
        <!-- Images -->
        <div x-data="{ mainImage: 0 }">
            <div class="bg-dark-900 border border-dark-800 rounded-3xl overflow-hidden shadow-lg shadow-black/30 mb-4 aspect-square tilt-card" data-tilt data-tilt-max="5" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.1">
                @if($product->images->count() > 0 && $product->images->first()->image_path !== 'placeholder')
                    @foreach($product->images as $index => $image)
                        <img x-show="mainImage === {{ $index }}"
                             src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                             alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endforeach
                @else
                    @php
                        $gcolors = ['from-brand-600 to-brand-900', 'from-blue-600 to-dark-900', 'from-brand-700 to-blue-900', 'from-dark-800 to-brand-800'];
                        $gc = $gcolors[$product->id % count($gcolors)];
                    @endphp
                    <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex items-center justify-center text-8xl">🛍️</div>
                @endif
            </div>
            @if($product->images->count() > 1)
            <div class="flex gap-3">
                @foreach($product->images as $index => $image)
                <button @click="mainImage = {{ $index }}" class="w-20 h-20 rounded-xl overflow-hidden border-2 transition-all" :class="mainImage === {{ $index }} ? 'border-brand-500 shadow-lg shadow-brand-500/30' : 'border-dark-700 hover:border-dark-600'">
                    <img src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" alt="" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Info -->
        <div>
            <a href="{{ route('categories.show', $product->category) }}" class="text-brand-400 text-sm font-semibold uppercase tracking-wider">{{ $product->category->name }}</a>
            <h1 class="text-3xl md:text-4xl font-bold text-white mt-2">{{ $product->name }}</h1>

            <!-- Rating -->
            <div class="flex items-center gap-2 mt-3">
                <div class="flex text-yellow-400">
                    @for($i=1; $i<=5; $i++)
                        @if($i <= round($product->average_rating))
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        @else
                            <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        @endif
                    @endfor
                </div>
                <span class="text-sm text-gray-500">({{ $product->approvedReviews->count() }} reviews)</span>
            </div>

            <!-- Price -->
            <div class="flex items-center gap-3 mt-4">
                @if($product->is_on_sale)
                    <span class="text-3xl font-black text-brand-400">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-xl text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    @php $discount = round(($product->price - $product->sale_price) / $product->price * 100); @endphp
                    <span class="px-3 py-1 bg-accent-500/20 text-accent-400 text-sm font-bold rounded-lg border border-accent-500/30">-{{ $discount }}%</span>
                @else
                    <span class="text-3xl font-black text-white">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="text-gray-400 mt-4 leading-relaxed">{{ $product->short_description }}</p>

            <!-- Stock Status -->
            <div class="mt-4">
                @if($product->stock > 5)
                    <span class="px-3 py-1 bg-green-500/10 text-green-400 text-sm font-semibold rounded-lg border border-green-500/20">✓ In Stock</span>
                @elseif($product->stock > 0)
                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 text-sm font-semibold rounded-lg border border-yellow-500/20">⚡ Only {{ $product->stock }} left</span>
                @else
                    <span class="px-3 py-1 bg-accent-500/10 text-accent-400 text-sm font-semibold rounded-lg border border-accent-500/20">✕ Out of Stock</span>
                @endif
            </div>

            @if($product->sku)
                <p class="text-sm text-gray-400 mt-2">SKU: {{ $product->sku }}</p>
            @endif

            <!-- Add to Cart + Wishlist -->
            @if($product->stock > 0)
            <div class="mt-6 flex items-center gap-3">
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-dark-800 border border-dark-700 rounded-xl" x-data="{ qty: 1 }">
                            <button type="button" @click="qty = Math.max(1, qty-1)" class="px-4 py-3 text-gray-400 hover:text-brand-400 font-bold text-lg transition-colors">−</button>
                            <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock }}" class="w-16 text-center bg-transparent font-semibold text-white focus:outline-none">
                            <button type="button" @click="qty = Math.min({{ $product->stock }}, qty+1)" class="px-4 py-3 text-gray-400 hover:text-brand-400 font-bold text-lg transition-colors">+</button>
                        </div>
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-xl font-bold text-lg hover:shadow-2xl hover:shadow-brand-500/30 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Add to Cart
                        </button>
                    </div>
                </form>

                <!-- Wishlist Heart -->
                <div x-data="{ wishlisted: false }">
                    <button @click="
                        fetch('/wishlist/toggle', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ product_id: {{ $product->id }} })
                        }).then(r => { if(r.status===401){window.location='/login';return;} return r.json(); }).then(d => { if(d) wishlisted = d.wishlisted; })"
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 border"
                        :class="wishlisted ? 'bg-red-500/20 border-red-500/40 text-red-400 scale-110' : 'bg-dark-800 border-dark-700 text-gray-400 hover:text-red-400 hover:border-red-500/30'">
                        <svg class="w-6 h-6" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($product->description)
            <div class="mt-8 bg-dark-900 border border-dark-800 rounded-2xl p-6">
                <h3 class="font-bold text-lg mb-3 text-white">Description</h3>
                <div class="text-gray-400 leading-relaxed">{!! nl2br(e($product->description)) !!}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="mt-16 animate-on-scroll">
        <h2 class="text-2xl font-bold mb-8 text-white">Customer Reviews</h2>

        @auth
            @if(!$product->reviews->where('user_id', auth()->id())->first())
            <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 mb-8">
                <h3 class="font-bold mb-4 text-white">Write a Review</h3>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-4" x-data="{ rating: 5 }">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Rating</label>
                        <div class="flex gap-1">
                            @for($i=1; $i<=5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="text-3xl transition-transform hover:scale-125" :class="{{ $i }} <= rating ? 'text-yellow-400' : 'text-gray-300'">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" x-model="rating">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Comment</label>
                        <textarea name="comment" rows="3" class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm" placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-brand-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-brand-500/20 transition-all">Submit Review</button>
                </form>
            </div>
            @endif
        @endauth

        <div class="space-y-4">
            @forelse($product->approvedReviews as $review)
            <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-700 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ $review->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                </div>
                @if($review->comment)
                    <p class="text-gray-400 mt-3 text-sm">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <p>No reviews yet. Be the first to review this product!</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="mt-16">
        <h2 class="text-2xl font-bold mb-8 text-white animate-on-scroll">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 product-grid">
            @foreach($relatedProducts as $rp)
                @include('partials.product-card', ['product' => $rp])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
