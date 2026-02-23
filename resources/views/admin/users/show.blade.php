@extends('admin.layouts.app')
@section('title', $user->name)
@section('page-title', 'Customer Details')

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-brand-600 text-sm font-semibold hover:underline mb-4 inline-block">← Back to Customers</a>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Profile -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6 text-center">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500 to-pink-500 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <h2 class="text-xl font-bold">{{ $user->name }}</h2>
        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
        <p class="text-xs text-gray-400 mt-1">Joined {{ $user->created_at->format('M d, Y') }}</p>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="bg-slate-800/50 rounded-xl p-3">
                <p class="text-2xl font-bold text-brand-600">{{ $user->orders->count() }}</p>
                <p class="text-xs text-gray-500">Orders</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-3">
                <p class="text-2xl font-bold text-brand-600">${{ number_format($totalSpent, 2) }}</p>
                <p class="text-xs text-gray-500">Total Spent</p>
            </div>
        </div>

        <div class="text-left mt-6 space-y-2 text-sm">
            @if($user->phone)<p><span class="text-gray-400">Phone:</span> {{ $user->phone }}</p>@endif
            @if($user->address)<p><span class="text-gray-400">Address:</span> {{ $user->address }}</p>@endif
            @if($user->city)<p><span class="text-gray-400">City:</span> {{ $user->city }}, {{ $user->state }} {{ $user->zip_code }}</p>@endif
        </div>
    </div>

    <!-- Orders -->
    <div class="lg:col-span-2">
        <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
            <h3 class="font-bold text-lg mb-4">Order History</h3>
            @if($user->orders->count() > 0)
            <div class="space-y-3">
                @foreach($user->orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between p-4 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors">
                    <div>
                        <p class="font-semibold text-sm">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }} · {{ $order->items->count() }} items</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm">${{ number_format($order->total, 2) }}</p>
                        <span class="text-xs font-bold {{ $order->status_badge }} px-2 py-0.5 rounded-full">{{ ucfirst($order->status) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
                <p class="text-gray-400 text-sm">No orders yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
