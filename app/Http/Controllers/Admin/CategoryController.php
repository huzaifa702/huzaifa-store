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
                'is_active' => $request->has('is_active'),
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
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->input('sort_order', 0),
            ];

            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $request->file('image')->store('categories', 'public');
            }

            $category->update($data);

            try {
                ActivityLogService::log('category_updated', "Category '{$category->name}' updated", null, $category);
            } catch (\Exception $e) {
                // Ignore activity log errors
            }

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Detach products from this category instead of deleting them
            // This preserves the products so they can be reassigned later
            $productCount = 0;
            try {
                $productCount = $category->products()->count();
                if ($productCount > 0) {
                    $category->products()->update(['category_id' => null]);
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
                ActivityLogService::log('category_deleted', "Category '{$category->name}' deleted ({$productCount} products detached)", null, $category);
            } catch (\Exception $e) { /* ignore */ }

            // Force-delete so slug is fully released and can be reused
            $category->forceDelete();

            return redirect()->route('admin.categories.index')->with('success', "Category deleted successfully! ({$productCount} products detached and preserved)");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    public function toggleActive(Category $category)
    {
        try {
            $category->update(['is_active' => !$category->is_active]);

            $status = $category->is_active ? 'activated' : 'deactivated';
            try {
                ActivityLogService::log("category_{$status}", "Category '{$category->name}' {$status}", null, $category);
            } catch (\Exception $e) { /* ignore */ }

            return back()->with('success', "Category {$status} successfully!");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category toggle active failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update category status: ' . $e->getMessage());
        }
    }
}
