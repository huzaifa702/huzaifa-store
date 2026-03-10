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
        $categories = Category::withCount('products')->orderBy('sort_order')->paginate(15);
        return view('admin.categories.index', compact('categories'));
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
            $slug = Str::slug($request->name);

            // Force-delete ALL trashed categories with the same slug
            // This fully removes the unique constraint blocker
            $trashedWithSlug = Category::onlyTrashed()->where('slug', $slug)->get();
            foreach ($trashedWithSlug as $trashed) {
                /** @var Category $trashed */
                $trashed->forceDelete();
            }

            // If a NON-trashed category with the same slug already exists, alter the slug slightly
            $existingCount = Category::where('slug', $slug)->count();
            if ($existingCount > 0) {
                $slug = $slug . '-' . ($existingCount + 1);
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
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

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->input('sort_order', 0),
        ];

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        ActivityLogService::log('category_updated', "Category '{$category->name}' updated", null, $category);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        try {
            // Soft-delete all products in this category first
            $productCount = 0;
            try {
                $productCount = $category->products()->count();
                if ($productCount > 0) {
                    $category->products()->delete(); // soft-delete so they can be restored
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
                ActivityLogService::log('category_deleted', "Category '{$category->name}' force deleted ({$productCount} products removed)", null, $category);
            } catch (\Exception $e) { /* ignore */ }

            // Force-delete so slug is fully released and can be reused
            $category->forceDelete();

            return redirect()->route('admin.categories.index')->with('success', "Category deleted successfully! ({$productCount} products also removed)");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
