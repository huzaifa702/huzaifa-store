@extends('admin.layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <h2 class="text-lg font-bold">All Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="px-6 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm text-center whitespace-nowrap">+ Add Category</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($categories as $category)
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-2xl">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                @else
                    📂
                @endif
            </div>
            <div>
                <h3 class="font-bold text-gray-100">{{ $category->name }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-xs text-gray-400">{{ $category->products_count ?? 0 }} products</p>
                    @if(($category->products_count ?? 0) === 0)
                        <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-500 rounded-full text-[10px] font-bold tracking-wide uppercase">Empty</span>
                    @endif
                </div>
            </div>
        </div>
        @if($category->description)
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $category->description }}</p>
        @endif
        <div class="flex items-center justify-between">
            <form action="{{ route('admin.categories.toggle', $category) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold transition-opacity hover:opacity-80 {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-800 text-gray-400' }}">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </button>
            </form>
            <div class="flex gap-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete &quot;{{ $category->name }}&quot; and ALL its {{ $category->products_count ?? 0 }} products? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
