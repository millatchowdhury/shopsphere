<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function index(Request $request)
    {
        return view('frontend.products.index', [
            'products' => $this->products->search($request->only(['query', 'category'])),
            'categories' => Category::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->status, 404);

        return view('frontend.products.show', [
            'product' => $product->load(['category', 'brand', 'images']),
            'relatedProducts' => Product::with('primaryImage')
                ->where('status', true)
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->limit(4)
                ->get(),
        ]);
    }
}
