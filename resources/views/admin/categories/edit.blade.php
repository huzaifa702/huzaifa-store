@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.categories.index') }}" class="text-brand-600 text-sm font-semibold hover:underline mb-4 inline-block">← Back to Categories</a>
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Image</label>
                @if($category->image)
                    <div class="mb-3 flex items-start gap-4 p-3 bg-slate-800/50 rounded-xl">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-24 h-24 rounded-lg object-cover border border-white/10" alt="Current image">
                        <div class="flex flex-col gap-2">
                            <p class="text-xs text-gray-400">Current Image</p>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="text-red-500 focus:ring-red-500 rounded">
                                <span class="text-sm text-red-400">Remove current image</span>
                            </label>
                        </div>
                    </div>
                @endif
                <label class="block text-xs text-gray-400 mb-1">{{ $category->image ? 'Upload new image (replaces current)' : 'Upload image' }}</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 bg-slate-800 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer pb-3">
                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500 rounded">
                        <span class="text-sm text-gray-300">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="px-8 py-3 bg-slate-800 text-gray-300 rounded-xl font-semibold hover:bg-gray-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
