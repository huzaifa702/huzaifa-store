@extends('admin.layouts.app')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="px-4 py-2 bg-slate-900 rounded-xl border shadow-black/20 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 w-64">
            <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">Search</button>
        </form>
    </div>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm">+ Add Product</a>
</div>

<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-800/50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($products as $product)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl">🛍️</div>
                        <div>
                            <p class="font-semibold text-gray-100 text-sm">{{ Str::limit($product->name, 30) }}</p>
                            <p class="text-xs text-gray-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $product->category->name ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    @if($product->sale_price)
                        <span class="font-bold text-sm text-brand-600">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xs text-gray-400 line-through ml-1">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="font-bold text-sm">${{ number_format($product->price, 2) }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-medium {{ $product->stock > 5 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">{{ $product->stock }}</span>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-800 text-gray-500' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->withQueryString()->links() }}</div>
@endsection
