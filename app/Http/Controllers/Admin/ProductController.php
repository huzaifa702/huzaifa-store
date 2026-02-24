<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'primaryImage');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(sku) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|unique:products|max:50',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'meta_title' => $request->name,
            'meta_description' => $request->short_description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        ActivityLogService::log('product_created', "Product '{$product->name}' created", null, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $oldValues = $product->toArray();

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'meta_title' => $request->input('meta_title', $request->name),
            'meta_description' => $request->input('meta_description', $request->short_description),
        ]);

        // Handle deletion of specific images (from delete checkboxes)
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = ProductImage::where('id', $imageId)->where('product_id', $product->id)->first();
                if ($img) {
                    // Delete from storage (only local files, not URLs)
                    if ($img->image_path && !str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                        Storage::disk('public')->delete($img->image_path);
                    }
                    $img->delete();
                }
            }

            // Re-assign primary if the primary image was deleted
            if (!$product->images()->where('is_primary', true)->exists()) {
                $firstImage = $product->images()->orderBy('sort_order')->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }
        }

        // Add new uploaded images
        if ($request->hasFile('images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $existingCount === 0 && $index === 0,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        ActivityLogService::log('product_updated', "Product '{$product->name}' updated", null, $product, $oldValues, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Nullify product_id in order_items to preserve order history
            \App\Models\OrderItem::where('product_id', $product->id)
                ->update(['product_id' => null]);

            // Delete all product images from storage
            foreach ($product->images as $img) {
                if ($img->image_path && !str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }

            // Delete related records
            $product->images()->delete();
            $product->reviews()->delete();

            ActivityLogService::log('product_deleted', "Product '{$product->name}' deleted", null, $product);

            $product->forceDelete();

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        ActivityLogService::log("product_{$status}", "Product '{$product->name}' {$status}", null, $product);

        return back()->with('success', "Product {$status} successfully!");
    }
}
