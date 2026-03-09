@extends('layouts.app')
@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 animate-on-scroll">Shopping Cart</h1>

    @if($cart && $cart->items->count() > 0)
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cart->items as $item)
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-4 flex gap-4 items-center animate-on-scroll card-3d">
                <!-- Product Image -->
                <a href="{{ route('products.show', $item->product) }}" class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-dark-800">
                        @if($item->product->primary_image_url)
                            <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            @php $gc = ['from-brand-500 to-neon-purple', 'from-neon-pink to-accent-500', 'from-neon-cyan to-brand-500'][$item->product->id % 3]; @endphp
                            <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex items-center justify-center text-3xl">🛍️</div>
                        @endif
                    </div>
                </a>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <a href="{{ route('products.show', $item->product) }}" class="font-semibold text-gray-100 hover:text-brand-600 transition-colors truncate block">{{ $item->product->name }}</a>
                    <p class="text-sm text-gray-400">{{ $item->product->category->name ?? '' }}</p>
                    <p class="font-bold text-brand-600 mt-1">${{ number_format($item->product->display_price, 2) }}</p>
                </div>

                <!-- Quantity -->
                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <select name="quantity" onchange="this.form.submit()" class="px-3 py-2 bg-dark-800 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
                        @for($i=1; $i<=min(10, $item->product->stock); $i++)
                            <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>

                <!-- Subtotal -->
                <div class="text-right">
                    <p class="font-bold text-gray-100">${{ number_format($item->subtotal, 2) }}</p>
                </div>

                <!-- Remove -->
                <form action="{{ route('cart.remove', $item) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 sticky top-24 animate-on-scroll">
                <h3 class="font-bold text-lg mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal ({{ $cart->items->sum('quantity') }} items)</span><span class="font-semibold">${{ number_format($cart->total, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="font-semibold text-green-600">{{ $cart->total >= 100 ? 'Free' : '$9.99' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tax (5%)</span><span class="font-semibold">${{ number_format($cart->total * 0.05, 2) }}</span></div>
                    <hr>
                    @php $grandTotal = $cart->total + ($cart->total < 100 ? 9.99 : 0) + $cart->total * 0.05; @endphp
                    <div class="flex justify-between text-lg"><span class="font-bold">Total</span><span class="font-black text-brand-600">${{ number_format($grandTotal, 2) }}</span></div>
                </div>

                <a href="{{ route('checkout.index') }}" class="block w-full mt-6 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold text-center hover:shadow-2xl hover:shadow-brand-500/30 transition-all transform hover:scale-[1.02]">
                    Proceed to Checkout →
                </a>
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 hover:text-brand-600 mt-3 transition-colors">Continue Shopping</a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-20 animate-on-scroll">
        <div class="text-8xl mb-4">🛒</div>
        <h2 class="text-2xl font-bold text-gray-400">Your cart is empty</h2>
        <p class="text-gray-400 mt-2 mb-6">Looks like you haven't added any products yet.</p>
        <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Start Shopping →</a>
    </div>
    @endif
</div>
@endsection
