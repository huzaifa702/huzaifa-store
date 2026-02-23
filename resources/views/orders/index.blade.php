@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 animate-on-scroll">My Orders</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <a href="{{ route('orders.show', $order) }}" class="block bg-dark-900 rounded-2xl shadow-black/20 p-6 hover:shadow-lg transition-all card-3d animate-on-scroll">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-gray-100">{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">{{ $order->items_count }} items</p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div>
                    <p class="font-bold text-lg text-brand-600">${{ number_format($order->total, 2) }}</p>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $orders->links() }}</div>
    @else
    <div class="text-center py-20 animate-on-scroll">
        <div class="text-8xl mb-4">📦</div>
        <h2 class="text-2xl font-bold text-gray-400">No orders yet</h2>
        <p class="text-gray-400 mt-2 mb-6">Start shopping to see your orders here.</p>
        <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Browse Products →</a>
    </div>
    @endif
</div>
@endsection
