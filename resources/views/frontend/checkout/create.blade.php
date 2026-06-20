@extends('layouts.frontend')
@section('title', 'Checkout')
@section('content')
<section class="container py-5">
    <h1 class="section-title mb-4">Checkout</h1>
    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('checkout.store') }}" class="bg-white border rounded p-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Coupon Code</label><input class="form-control" name="coupon_code" value="{{ old('coupon_code') }}"></div>
                    <div class="col-12"><label class="form-label">Order Notes</label><textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea></div>
                </div>
                <button class="btn btn-dark mt-4">Purchase Now</button>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="bg-white border rounded p-4">
                <h2 class="h5">Order Summary</h2>
                @foreach($items as $item)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $item['product']->name }} x {{ $item['quantity'] }}</span><span>${{ number_format($item['line_total'], 2) }}</span></div>
                @endforeach
                <div class="d-flex justify-content-between pt-3"><strong>Subtotal</strong><strong>${{ number_format($subtotal, 2) }}</strong></div>
                <div class="small text-muted mt-2">Shipping is free for orders over $100; otherwise $10.</div>
            </div>
        </div>
    </div>
</section>
@endsection
