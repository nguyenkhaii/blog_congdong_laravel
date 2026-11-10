<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::withCount('posts')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        return view('admin.categories.create');
    }

    // Xử lý thêm danh mục
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        Log::info('Admin đã thêm danh mục', [
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'category_name' => $category->name,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // Hiển thị form sửa danh mục
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Xử lý cập nhật danh mục
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        Log::info('Admin đã cập nhật danh mục', [
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'category_name' => $category->name,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xử lý xóa danh mục
    public function destroy(Category $category)
    {
        $categoryId = $category->id;
        $categoryName = $category->name;

        $category->delete();

        Log::warning('Admin đã xóa danh mục', [
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'category_name' => $categoryName,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }
}
