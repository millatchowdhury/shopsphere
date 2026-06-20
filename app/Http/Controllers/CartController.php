<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index()
    {
        return view('frontend.cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:99']]);

        $this->cart->add($product, $data['quantity'] ?? 1);

        return back()->with('success', 'Product added to cart.');
    }

    public function buyNow(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:99']]);

        $this->cart->buyNow($product, $data['quantity'] ?? 1);

        return redirect()->route('checkout.create', ['buy_now' => 1]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:99']]);

        $this->cart->update($product, $data['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Product $product)
    {
        $this->cart->remove($product);

        return back()->with('success', 'Product removed from cart.');
    }
}
