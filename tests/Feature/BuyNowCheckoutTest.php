<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyNowCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_buy_now_checks_out_temporary_item_without_clearing_cart(): void
    {
        $user = User::factory()->create();
        $cartProduct = Product::factory()->create([
            'price' => 40,
            'discount_price' => null,
            'stock_quantity' => 10,
        ]);
        $buyNowProduct = Product::factory()->create([
            'price' => 25,
            'discount_price' => null,
            'stock_quantity' => 10,
        ]);

        $this->post(route('cart.store', $cartProduct), ['quantity' => 2])
            ->assertRedirect();

        $this->actingAs($user)
            ->post(route('cart.buy-now', $buyNowProduct), ['quantity' => 3])
            ->assertRedirect(route('checkout.create', ['buy_now' => 1]));

        $this->get(route('checkout.create', ['buy_now' => 1]))
            ->assertOk()
            ->assertSee($buyNowProduct->name)
            ->assertSee('Unit Price: $25.00')
            ->assertDontSee($cartProduct->name);

        $this->post(route('checkout.store'), [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '555-0100',
            'shipping_address' => '123 Test Street',
        ])->assertRedirect();

        $order = Order::with('items')->firstOrFail();

        $this->assertTrue($order->items->contains(
            fn ($item) => $item->product_id === $buyNowProduct->id && $item->quantity === 3
        ));
        $this->assertFalse($order->items->contains('product_id', $cartProduct->id));
        $this->assertSame(2, array_sum(session('shopping_cart', [])));
        $this->assertSame([], session('buy_now_cart', []));
    }

    public function test_product_card_buy_now_links_to_product_details(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(route('products.show', $product), false)
            ->assertSee('Buy Now');
    }

    public function test_guest_buy_now_goes_directly_to_checkout_and_places_order(): void
    {
        $product = Product::factory()->create([
            'price' => 25,
            'discount_price' => null,
            'stock_quantity' => 10,
        ]);

        $this->post(route('cart.buy-now', $product), ['quantity' => 2])
            ->assertRedirect(route('checkout.create', ['buy_now' => 1]));

        $this->get(route('checkout.create', ['buy_now' => 1]))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('Unit Price: $25.00');

        $this->post(route('checkout.store'), [
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
            'customer_phone' => '555-0100',
            'shipping_address' => '123 Guest Street',
        ])->assertRedirect();

        $order = Order::with('items')->firstOrFail();

        $this->assertNull($order->user_id);
        $this->assertSame('Guest Customer', $order->customer_name);
        $this->assertTrue($order->items->contains(
            fn ($item) => $item->product_id === $product->id && $item->quantity === 2
        ));
    }
}
