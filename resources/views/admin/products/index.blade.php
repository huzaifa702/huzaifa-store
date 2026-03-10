@extends('admin.layouts.app')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-3 flex-wrap items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="px-4 py-2 bg-slate-900 rounded-xl border border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 w-64 text-gray-200 placeholder-gray-500">
            <select name="category" class="px-4 py-2 bg-slate-900 rounded-xl border border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 text-gray-300" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->products_count }})</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">Filter</button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-slate-700 text-gray-300 rounded-xl text-sm font-semibold hover:bg-slate-600 transition-colors">Clear</a>
            @endif
        </form>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}</span>
        <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm">+ Add Product</a>
    </div>
</div>

@if($products->isEmpty())
    <div class="bg-slate-900 rounded-2xl p-12 text-center">
        <div class="text-5xl mb-4">📦</div>
        <h3 class="text-lg font-bold text-gray-300 mb-2">No products found</h3>
        <p class="text-sm text-gray-500 mb-6">
            @if(request('search') || request('category'))
                No products match your current filters. Try adjusting your search or category filter.
            @else
                You haven't added any products yet.
            @endif
        </p>
        @if(request('search') || request('category'))
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 bg-slate-700 text-gray-300 rounded-xl text-sm font-semibold hover:bg-slate-600 transition-colors inline-block">Clear Filters</a>
        @else
            <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm inline-block">+ Add Your First Product</a>
        @endif
    </div>
@else
<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-x-auto">
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
        <tbody class="divide-y divide-slate-800">
            @foreach($products as $product)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 flex-shrink-0">
                            @if($product->primaryImage && $product->primaryImage->image_path)
                                @if(str_starts_with($product->primaryImage->image_path, 'http'))
                                    <img src="{{ $product->primaryImage->image_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @endif
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-lg">{{ substr($product->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-100 text-sm">{{ Str::limit($product->name, 30) }}</p>
                            <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-400 bg-slate-800 px-2 py-1 rounded-lg">{{ $product->category->name ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($product->sale_price)
                        <span class="font-bold text-sm text-brand-400">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xs text-gray-500 line-through ml-1">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="font-bold text-sm text-gray-200">${{ number_format($product->price, 2) }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-medium {{ $product->stock > 5 ? 'text-green-400' : ($product->stock > 0 ? 'text-yellow-400' : 'text-red-400') }}">{{ $product->stock }}</span>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-500/15 text-green-400 border border-green-500/30' : 'bg-slate-800 text-gray-500 border border-slate-700' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 bg-blue-500/15 text-blue-400 rounded-lg text-xs font-semibold hover:bg-blue-500/25 transition-colors border border-blue-500/30">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete &quot;{{ $product->name }}&quot;? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-500/15 text-red-400 rounded-lg text-xs font-semibold hover:bg-red-500/25 transition-colors border border-red-500/30">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->withQueryString()->links() }}</div>
@endif
@endsection
