<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart, private readonly OrderService $orders)
    {
    }

    public function create(Request $request)
    {
        if (! $request->boolean('buy_now')) {
            $this->cart->clearBuyNow();
        }

        return view('frontend.checkout.create', [
            'items' => $this->cart->checkoutItems(),
            'subtotal' => $this->cart->checkoutSubtotal(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = $this->orders->checkout($request->user(), $data);

        if ($request->user()) {
            return redirect()->route('customer.orders.show', $order)->with('success', 'Order placed successfully.');
        }

        return redirect()->route('orders.confirmation', $order)->with('success', 'Order placed successfully.');
    }

    public function confirmation(Order $order)
    {
        return view('frontend.dashboard.order-show', ['order' => $order->load('items')]);
    }
}
