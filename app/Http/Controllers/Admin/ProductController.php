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
            'meta_title' => $request->name,
            'meta_description' => $request->short_description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        ActivityLogService::log('product_updated', "Product '{$product->name}' updated", null, $product, $oldValues, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        ActivityLogService::log('product_deleted', "Product '{$product->name}' deleted", null, $product);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        ActivityLogService::log("product_{$status}", "Product '{$product->name}' {$status}", null, $product);

        return back()->with('success', "Product {$status} successfully!");
    }
}
