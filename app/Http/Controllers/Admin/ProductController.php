<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'primaryImage');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
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

        try {
            $product = Product::create([
                'name' => \Illuminate\Support\Str::title($request->name),
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'sku' => $request->sku,
                'stock' => $request->stock,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
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

            try {
                ActivityLogService::log('product_created', "Product '{$product->name}' created", null, $product);
            } catch (\Exception $e) {
                // Ignore log errors so it doesn't crash creation
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
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

        try {
            $oldValues = $product->toArray();

            // If the user checked "replace all existing images"
            if ($request->has('replace_images') && $request->hasFile('images')) {
                foreach ($product->images as $existingImage) {
                    if ($existingImage->image_path !== 'placeholder' && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingImage->image_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($existingImage->image_path);
                    }
                    $existingImage->delete();
                }
            }

            $product->update([
                'name' => \Illuminate\Support\Str::title($request->name),
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'sku' => $request->sku,
                'stock' => $request->stock,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'meta_title' => $request->name,
                'meta_description' => $request->short_description,
            ]);

            if ($request->hasFile('images')) {
                $baseSortOrder = $product->images()->count();
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $baseSortOrder === 0 && $index === 0,
                        'sort_order' => $baseSortOrder + $index,
                    ]);
                }
            }

            try {
                ActivityLogService::log('product_updated', "Product '{$product->name}' updated", null, $product, $oldValues, $product->toArray());
            } catch (\Exception $e) {
                // Ignore log errors
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            try {
                ActivityLogService::log('product_deleted', "Product '{$product->name}' deleted", null, $product);
            } catch (\Exception $e) {}
            
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function toggleActive(Product $product)
    {
        try {
            $product->update(['is_active' => !$product->is_active]);

            $status = $product->is_active ? 'activated' : 'deactivated';
            try {
                ActivityLogService::log("product_{$status}", "Product '{$product->name}' {$status}", null, $product);
            } catch (\Exception $e) {}

            return back()->with('success', "Product {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle status: ' . $e->getMessage());
        }
    }
}
