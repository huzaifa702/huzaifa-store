<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::withCount('products')->orderBy('sort_order')->paginate(15);
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load categories: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->input('sort_order', 0),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('categories', 'public');
            }

            $category = Category::create($data);

            try {
                ActivityLogService::log('category_created', "Category '{$category->name}' created", null, $category);
            } catch (\Exception $e) {
                // Ignore activity log errors — table might not exist
            }

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->input('sort_order', 0),
            ];

            // Handle image update
            if ($request->hasFile('image')) {
                try {
                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }
                } catch (\Exception $e) { /* ignore */ }
                $data['image'] = $request->file('image')->store('categories', 'public');
            }

            // Handle explicit image removal
            if ($request->boolean('remove_image') && !$request->hasFile('image')) {
                try {
                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }
                } catch (\Exception $e) { /* ignore */ }
                $data['image'] = null;
            }

            $category->update($data);

            try {
                ActivityLogService::log('category_updated', "Category '{$category->name}' updated", null, $category);
            } catch (\Exception $e) {
                // Ignore activity log errors
            }

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Soft-delete all products in this category
            $productCount = 0;
            try {
                $productCount = $category->products()->count();
                if ($productCount > 0) {
                    $category->products()->delete();
                }
            } catch (\Exception $e) { /* ignore */ }

            // Delete category image from storage
            try {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
            } catch (\Exception $e) { /* ignore */ }

            // Log activity
            try {
                ActivityLogService::log('category_deleted', "Category '{$category->name}' deleted ({$productCount} products removed)", null, $category);
            } catch (\Exception $e) { /* ignore */ }

            $category->delete();

            return redirect()->route('admin.categories.index')->with('success', "Category deleted successfully! ({$productCount} products also removed)");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
