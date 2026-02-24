@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.products.index') }}" class="text-brand-600 text-sm font-semibold hover:underline mb-4 inline-block">← Back to Products</a>

    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Price *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    @if($product->images->count())
                    <label class="block text-sm font-medium text-gray-300 mb-2">Current Images</label>
                    <div class="flex gap-3 mb-4 flex-wrap">
                        @foreach($product->images as $img)
                        <div class="relative group">
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200 border border-white/10">
                                @if($img->image_path !== 'placeholder' && !str_starts_with($img->image_path, 'http'))
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="Product image">
                                @elseif(str_starts_with($img->image_path, 'http'))
                                    <img src="{{ $img->image_path }}" class="w-full h-full object-cover" alt="Product image">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-lg">🖼️</div>
                                @endif
                            </div>
                            <label class="flex items-center gap-1 mt-1 cursor-pointer">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="text-red-500 focus:ring-red-500 rounded w-3 h-3">
                                <span class="text-xs text-red-400">Delete</span>
                            </label>
                            @if($img->is_primary)
                                <span class="absolute top-0 right-0 bg-brand-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-bl-lg rounded-tr-lg">Primary</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <label class="block text-sm font-medium text-gray-300 mb-1">{{ $product->images->count() ? 'Add More Images' : 'Upload Images' }}</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 bg-slate-800 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold">
                    @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500 rounded">
                        <span class="text-sm text-gray-300">Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="text-brand-500 focus:ring-brand-500 rounded">
                        <span class="text-sm text-gray-300">Featured</span>
                    </label>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="font-bold text-gray-300 mb-4">SEO Settings</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full px-4 py-3 bg-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="px-8 py-3 bg-slate-800 text-gray-300 rounded-xl font-semibold hover:bg-gray-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
