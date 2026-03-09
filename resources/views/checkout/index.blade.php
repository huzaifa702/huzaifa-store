@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 animate-on-scroll">Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Shipping Info -->
            <div class="lg:col-span-2 space-y-6 animate-on-scroll">
                <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">📦 Shipping Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Full Name *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', $user->name) }}" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Email *</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', $user->email) }}" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $user->phone) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">City *</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city', $user->city) }}" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Address *</label>
                            <textarea name="shipping_address" rows="2" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('shipping_address', $user->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">State</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state', $user->state) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">ZIP Code *</label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip', $user->zip_code) }}" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">💳 Payment Method</h3>
                    <div class="space-y-3" x-data="{ method: 'cod' }">
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-all" :class="method === 'cod' ? 'bg-brand-50 border-2 border-brand-500' : 'bg-dark-800/50 border-2 border-transparent hover:bg-dark-800'">
                            <input type="radio" name="payment_method" value="cod" x-model="method" class="text-brand-500 focus:ring-brand-500">
                            <div>
                                <p class="font-semibold">Cash on Delivery</p>
                                <p class="text-xs text-gray-500">Pay when you receive your order</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-all" :class="method === 'bank_transfer' ? 'bg-brand-50 border-2 border-brand-500' : 'bg-dark-800/50 border-2 border-transparent hover:bg-dark-800'">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="method" class="text-brand-500 focus:ring-brand-500">
                            <div>
                                <p class="font-semibold">Bank Transfer</p>
                                <p class="text-xs text-gray-500">Transfer to our bank account</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">📝 Order Notes</h3>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 sticky top-24 animate-on-scroll">
                    <h3 class="font-bold text-lg mb-4">Order Summary</h3>
                    <div class="space-y-3 mb-4">
                        @foreach($cart->items as $item)
                        <div class="flex gap-3 items-center">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-dark-800 flex-shrink-0">
                                @if($item->product->primary_image_url)
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    @php $gc = ['from-brand-500 to-neon-purple', 'from-neon-pink to-accent-500', 'from-neon-cyan to-brand-500'][$item->product->id % 3]; @endphp
                                    <div class="w-full h-full bg-gradient-to-br {{ $gc }} flex items-center justify-center text-lg">🛍️</div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-100 truncate">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">× {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <hr class="my-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>${{ number_format($cart->total, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-green-600">{{ $cart->total >= 100 ? 'Free' : '$9.99' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Tax (5%)</span><span>${{ number_format($cart->total * 0.05, 2) }}</span></div>
                        <hr>
                        @php $total = $cart->total + ($cart->total < 100 ? 9.99 : 0) + $cart->total * 0.05; @endphp
                        <div class="flex justify-between text-lg"><span class="font-bold">Total</span><span class="font-black text-brand-600">${{ number_format($total, 2) }}</span></div>
                    </div>

                    <button type="submit" class="w-full mt-6 py-3 bg-gradient-to-r from-brand-500 to-accent-500 text-white rounded-xl font-bold hover:shadow-2xl hover:shadow-brand-500/30 transition-all transform hover:scale-[1.02]">
                        Place Order 🎉
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
