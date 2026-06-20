<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() { return view('admin.categories.index', ['categories' => Category::latest()->paginate(15)]); }
    public function create() { return view('admin.categories.form', ['category' => new Category()]); }
    public function edit(Category $category) { return view('admin.categories.form', compact('category')); }

    public function store(Request $request)
    {
        Category::create($this->validated($request));
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category));
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->deleteStoredImage($category);
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,'.$category?->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('image')) {
            if ($category) {
                $this->deleteStoredImage($category);
            }

            $data['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($data['image']);
        }

        return $data;
    }

    private function deleteStoredImage(Category $category): void
    {
        $path = $category->getRawOriginal('image');

        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $path = Str::startsWith($path, '/storage/')
            ? Str::after($path, '/storage/')
            : $path;

        if (Str::startsWith($path, 'categories/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
