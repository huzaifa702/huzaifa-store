@extends('admin.layouts.app')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="px-4 py-2 bg-slate-900 rounded-xl border shadow-black/20 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 w-64">
        <select name="status" class="px-4 py-2 bg-slate-900 rounded-xl border shadow-black/20 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">Filter</button>
    </form>
</div>

<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-800/50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($orders as $order)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 font-semibold text-sm">{{ $order->order_number }}</td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="px-6 py-4 font-bold text-sm text-brand-600">${{ number_format($order->total, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-brand-50 text-brand-600 rounded-lg text-xs font-semibold hover:bg-brand-100 transition-colors">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->withQueryString()->links() }}</div>
@endsection
