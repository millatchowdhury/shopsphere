<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function checkout(?User $user, array $data): Order
    {
        $items = $this->cartService->checkoutItems();
        abort_if($items->isEmpty(), 422, 'Your cart is empty.');

        return DB::transaction(function () use ($user, $data, $items) {
            $subtotal = $this->cartService->checkoutSubtotal();
            $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal);
            $discount = $coupon?->discountFor($subtotal) ?? 0;
            $shipping = $subtotal >= 100 ? 0 : 10;

            $order = Order::create([
                'order_number' => 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => $user?->id,
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $subtotal - $discount + $shipping,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'status' => 'pending',
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => '',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $this->cartService->clearCheckout();

            return $order->load('items');
        });
    }

    private function resolveCoupon(?string $code, float $subtotal): ?Coupon
    {
        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', strtoupper($code))->first();

        return $coupon?->isValidFor($subtotal) ? $coupon : null;
    }
}
