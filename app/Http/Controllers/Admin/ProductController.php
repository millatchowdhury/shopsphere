<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Product::class);

        return view('admin.products.index', ['products' => Product::with(['category', 'brand'])->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.products.form', $this->formData(new Product()));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $product = Product::create($data);
        $this->syncUploadedImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', $this->formData($product));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request, $product));
        $this->syncUploadedImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->deleteStoredImages($product);
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $id = $product?->id;

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,'.$id],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,'.$id],
            'description' => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status', true);

        unset($data['images']);

        return $data;
    }

    private function syncUploadedImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $this->deleteStoredImages($product);
        $product->images()->delete();

        collect($request->file('images'))
            ->values()
            ->each(function ($image, int $index) use ($product) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            });
    }

    private function deleteStoredImages(Product $product): void
    {
        $product->images->each(function (ProductImage $image) {
            $path = $image->getRawOriginal('image_path');

            if (! $path || Str::startsWith($path, ['http://', 'https://', '/storage/'])) {
                return;
            }

            Storage::disk('public')->delete($path);
        });
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ];
    }
}
