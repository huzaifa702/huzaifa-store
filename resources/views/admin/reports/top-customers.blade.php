@extends('admin.layouts.app')
@section('title', 'Top Customers')
@section('page-title', 'Top Customers Report')

@section('content')
<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-100">⭐ Top 20 Customers</h3>
                <p class="text-sm text-gray-500 mt-1">Ranked by total amount spent</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-slate-800 text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 transition">← Back to Reports</a>
        </div>
    </div>

    @if($topCustomers->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-800/50 border-b border-gray-100">
                    <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Spent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($topCustomers as $customer)
                <tr class="hover:bg-slate-800/50 transition">
                    <td class="py-4 px-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-brand-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-sm">{{ $loop->iteration }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-semibold text-gray-100">{{ $customer->user->name ?? 'Unknown' }}</p>
                    </td>
                    <td class="py-4 px-6 text-gray-500 text-sm">{{ $customer->user->email ?? '-' }}</td>
                    <td class="py-4 px-6 text-right">
                        <span class="inline-flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm font-medium">{{ $customer->total_orders }}</span>
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-green-600">${{ number_format($customer->total_spent, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-16">
        <p class="text-gray-400 text-lg">👤 No customer data available yet.</p>
        <p class="text-gray-300 text-sm mt-2">Customer data will appear here once orders are placed.</p>
    </div>
    @endif
</div>
@endsection
