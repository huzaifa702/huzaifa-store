@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.products.index') }}" class="text-brand-600 text-sm font-semibold hover:underline mb-4 inline-block">← Back to Products</a>

    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Price *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('short_description') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('description') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Product Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 bg-slate-800 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold">
                    <p class="text-xs text-gray-400 mt-1">Upload multiple images. First image will be the primary image.</p>
                </div>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500 rounded">
                        <span class="text-sm text-gray-300">Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500 rounded">
                        <span class="text-sm text-gray-300">Featured</span>
                    </label>
                </div>
            </div>

            <!-- SEO -->
            <div class="border-t pt-6">
                <h3 class="font-bold text-gray-300 mb-4">SEO Settings</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Create Product</button>
                <a href="{{ route('admin.products.index') }}" class="px-8 py-3 bg-slate-800 text-gray-300 rounded-xl font-semibold hover:bg-gray-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
