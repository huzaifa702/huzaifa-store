@extends('admin.layouts.app')
@section('title', 'Top Selling Products')
@section('page-title', 'Top Selling Products Report')

@section('content')
<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-100">🏆 Top 20 Best Selling Products</h3>
                <p class="text-sm text-gray-500 mt-1">Ranked by total units sold</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-slate-800 text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 transition">← Back to Reports</a>
        </div>
    </div>

    @if($topProducts->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-800/50 border-b border-gray-100">
                    <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Units Sold</th>
                    <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($topProducts as $item)
                <tr class="hover:bg-slate-800/50 transition">
                    <td class="py-4 px-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">{{ $loop->iteration }}</div>
                    </td>
                    <td class="py-4 px-6 font-semibold text-gray-100">{{ $item->product_name }}</td>
                    <td class="py-4 px-6 text-right">
                        <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">{{ number_format($item->total_sold) }} units</span>
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-green-600">${{ number_format($item->total_revenue, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-16">
        <p class="text-gray-400 text-lg">📦 No sales data available yet.</p>
        <p class="text-gray-300 text-sm mt-2">Sales data will appear here once orders are placed.</p>
    </div>
    @endif
</div>
@endsection
