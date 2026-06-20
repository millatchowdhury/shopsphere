@extends('layouts.frontend')
@section('title', 'Shopping Cart')
@section('content')
<section class="container py-5">
    <h1 class="section-title mb-4">Shopping Cart</h1>
    <div class="bg-white border rounded p-3">
        @forelse($items as $item)
            <div class="row align-items-center border-bottom py-3">
                <div class="col-md-5">{{ $item['product']->name }}<div class="small text-muted">{{ $item['product']->sku }}</div></div>
                <div class="col-md-2">${{ number_format($item['unit_price'], 2) }}</div>
                <div class="col-md-3">
                    <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="d-flex gap-2">
                        @csrf @method('PATCH')
                        <input class="form-control" type="number" name="quantity" min="0" value="{{ $item['quantity'] }}">
                        <button class="btn btn-outline-dark">Update</button>
                    </form>
                </div>
                <div class="col-md-2 text-md-end">
                    <div class="fw-bold">${{ number_format($item['line_total'], 2) }}</div>
                    <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">@csrf @method('DELETE')<button class="btn btn-link text-danger p-0">Remove</button></form>
                </div>
            </div>
        @empty
            <p class="mb-0">Your cart is empty.</p>
        @endforelse
        <div class="d-flex justify-content-between align-items-center pt-3">
            <strong>Subtotal: ${{ number_format($subtotal, 2) }}</strong>
            <a class="btn btn-dark" href="{{ route('checkout.create') }}">Checkout</a>
        </div>
    </div>
</section>
@endsection
