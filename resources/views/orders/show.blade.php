@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8 animate-on-scroll">
        <div>
            <a href="{{ route('orders.index') }}" class="text-brand-600 text-sm font-semibold hover:underline">← Back to Orders</a>
            <h1 class="text-3xl font-bold mt-2">Order {{ $order->order_number }}</h1>
            <p class="text-gray-400 text-sm mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
            <a href="{{ route('orders.invoice', $order) }}" class="px-4 py-2 bg-dark-800 hover:bg-gray-200 rounded-xl text-sm font-semibold transition-colors">📄 Download Invoice</a>
        </div>
    </div>

    <!-- Order Tracking -->
    <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 mb-8 animate-on-scroll">
        <h3 class="font-bold text-lg mb-6">Order Status</h3>
        <div class="flex items-center justify-between relative">
            @foreach(['pending', 'processing', 'shipped', 'delivered'] as $index => $status)
                @php
                    $statuses = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3, 'cancelled' => -1];
                    $current = $statuses[$order->status] ?? 0;
                    $isActive = $current >= $index;
                    $isCurrent = $order->status === $status;
                @endphp
                <div class="flex flex-col items-center {{ $index > 0 ? 'flex-1' : '' }} relative z-10">
                    @if($index > 0)
                        <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $isActive ? 'bg-brand-500' : 'bg-gray-200' }}"></div>
                    @endif
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold relative z-10 {{ $isActive ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-500' }} {{ $isCurrent ? 'ring-4 ring-brand-200' : '' }}">
                        @if($isActive) ✓ @else {{ $index + 1 }} @endif
                    </div>
                    <span class="text-xs mt-2 font-medium {{ $isActive ? 'text-brand-600' : 'text-gray-400' }}">{{ ucfirst($status) }}</span>
                </div>
            @endforeach
        </div>
        @if($order->status === 'cancelled')
            <div class="mt-4 px-4 py-3 bg-red-50 text-red-700 rounded-xl text-sm font-medium">This order has been cancelled.</div>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Items -->
        <div class="lg:col-span-2">
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 animate-on-scroll">
                <h3 class="font-bold text-lg mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-2xl">🛍️</div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-100">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-400">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-bold">${{ number_format($item->total, 2) }}</p>
                    </div>
                    @endforeach
                </div>
                <hr class="my-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span>${{ number_format($order->shipping_cost, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tax</span><span>${{ number_format($order->tax, 2) }}</span></div>
                    <hr>
                    <div class="flex justify-between text-lg"><span class="font-bold">Total</span><span class="font-black text-brand-600">${{ number_format($order->total, 2) }}</span></div>
                </div>
            </div>
        </div>

        <!-- Shipping + Payment -->
        <div class="space-y-6">
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 animate-on-scroll">
                <h3 class="font-bold text-lg mb-3">📦 Shipping</h3>
                <p class="font-medium">{{ $order->shipping_name }}</p>
                <p class="text-sm text-gray-500">{{ $order->shipping_email }}</p>
                @if($order->shipping_phone)<p class="text-sm text-gray-500">{{ $order->shipping_phone }}</p>@endif
                <p class="text-sm text-gray-500 mt-2">{{ $order->shipping_address }}</p>
                <p class="text-sm text-gray-500">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
            </div>
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 animate-on-scroll">
                <h3 class="font-bold text-lg mb-3">💳 Payment</h3>
                <p class="font-medium">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</p>
                @if($order->payment)
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold {{ $order->payment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($order->payment->status) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
