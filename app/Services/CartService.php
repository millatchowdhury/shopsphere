<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'shopping_cart';
    private const BUY_NOW_SESSION_KEY = 'buy_now_cart';

    public function items(): Collection
    {
        return $this->itemsFor(self::SESSION_KEY);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $currentQuantity = $cart[$product->id] ?? 0;

        // Stock is enforced here so checkout cannot receive impossible quantities.
        $cart[$product->id] = min($product->stock_quantity, $currentQuantity + $quantity);

        session([self::SESSION_KEY => $cart]);
    }

    public function buyNow(Product $product, int $quantity = 1): void
    {
        session([
            self::BUY_NOW_SESSION_KEY => [
                $product->id => min($product->stock_quantity, $quantity),
            ],
        ]);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = session(self::SESSION_KEY, []);

        if ($quantity < 1) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($product->stock_quantity, $quantity);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(Product $product): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$product->id]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function clearBuyNow(): void
    {
        session()->forget(self::BUY_NOW_SESSION_KEY);
    }

    public function checkoutItems(): Collection
    {
        return $this->itemsFor($this->hasBuyNow() ? self::BUY_NOW_SESSION_KEY : self::SESSION_KEY);
    }

    public function checkoutSubtotal(): float
    {
        return (float) $this->checkoutItems()->sum('line_total');
    }

    public function clearCheckout(): void
    {
        if ($this->hasBuyNow()) {
            $this->clearBuyNow();

            return;
        }

        $this->clear();
    }

    public function hasBuyNow(): bool
    {
        return ! empty(session(self::BUY_NOW_SESSION_KEY, []));
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function count(): int
    {
        return (int) collect(session(self::SESSION_KEY, []))->sum();
    }

    private function itemsFor(string $sessionKey): Collection
    {
        $cart = collect(session($sessionKey, []));
        $products = Product::with('primaryImage')->whereIn('id', $cart->keys())->get()->keyBy('id');

        return $cart->map(function (int $quantity, int $productId) use ($products) {
            $product = $products->get($productId);

            if (! $product) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $product->selling_price,
                'line_total' => $product->selling_price * $quantity,
            ];
        })->filter()->values();
    }
}
