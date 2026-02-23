@extends('admin.layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-brand-600 text-sm font-semibold hover:underline mb-4 inline-block">← Back to Orders</a>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold">{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-400">{{ $order->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
            </div>

            <h3 class="font-bold mb-3">Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 p-3 bg-slate-800/50 rounded-xl">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-lg">🛍️</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400">{{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                    </div>
                    <p class="font-bold text-sm">${{ number_format($item->total, 2) }}</p>
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

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Update Status -->
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <h3 class="font-bold mb-3">Update Status</h3>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="w-full px-4 py-3 bg-slate-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 mb-3">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">Update Status</button>
            </form>
        </div>

        <!-- Customer -->
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <h3 class="font-bold mb-3">👤 Customer</h3>
            <p class="font-semibold">{{ $order->user->name ?? 'Guest' }}</p>
            <p class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</p>
        </div>

        <!-- Shipping -->
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <h3 class="font-bold mb-3">📦 Shipping</h3>
            <p class="font-medium text-sm">{{ $order->shipping_name }}</p>
            <p class="text-sm text-gray-500">{{ $order->shipping_email }}</p>
            @if($order->shipping_phone)<p class="text-sm text-gray-500">{{ $order->shipping_phone }}</p>@endif
            <p class="text-sm text-gray-500 mt-2">{{ $order->shipping_address }}</p>
            <p class="text-sm text-gray-500">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
        </div>

        <!-- Payment -->
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <h3 class="font-bold mb-3">💳 Payment</h3>
            <p class="font-medium text-sm">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</p>
            @if($order->payment)
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold {{ $order->payment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($order->payment->status) }}</span>
            @endif
        </div>
    </div>
</div>
@endsection
