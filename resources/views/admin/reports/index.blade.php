@extends('admin.layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="grid lg:grid-cols-2 gap-6">
    <!-- Top Selling Products -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">🏆 Top Selling Products</h3>
        </div>
        @if($topProducts->count() > 0)
        <div class="space-y-3">
            @foreach($topProducts as $item)
            <div class="flex items-center gap-4 p-3 bg-slate-800/50 rounded-xl">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">{{ $loop->iteration }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $item->product_name }}</p>
                    <p class="text-xs text-gray-400">{{ $item->total_sold }} units sold</p>
                </div>
                <p class="font-bold text-sm text-brand-600">${{ number_format($item->total_revenue, 2) }}</p>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-gray-400 text-sm text-center py-8">No sales data yet.</p>
        @endif
    </div>

    <!-- Top Customers -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">⭐ Top Customers</h3>
        </div>
        @if($topCustomers->count() > 0)
        <div class="space-y-3">
            @foreach($topCustomers as $customer)
            <div class="flex items-center gap-4 p-3 bg-slate-800/50 rounded-xl">
                <div class="w-10 h-10 bg-gradient-to-br from-brand-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($customer->user->name ?? 'U', 0, 1)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm">{{ $customer->user->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $customer->user->email ?? '' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm text-brand-600">${{ number_format($customer->total_spent, 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $customer->total_orders }} orders</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-gray-400 text-sm text-center py-8">No customer data yet.</p>
        @endif
    </div>
</div>
@endsection
