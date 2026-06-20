@extends('layouts.frontend')
@section('title', 'Invoice '.$order->order_number)
@section('content')
<section class="container py-5">
    <div class="bg-white border rounded p-4">
        <div class="d-flex justify-content-between">
            <div><h1 class="h3">Invoice</h1><p class="text-muted">{{ $order->order_number }}</p></div>
            <span class="badge text-bg-secondary align-self-start status-pill">{{ $order->status }}</span>
        </div>
        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
        <table class="table">
            <thead><tr><th>Product</th><th>SKU</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
            <tbody>
            @foreach($order->items as $item)
                <tr><td>{{ $item->product_name }}</td><td>{{ $item->sku }}</td><td>{{ $item->quantity }}</td><td class="text-end">${{ number_format($item->line_total, 2) }}</td></tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-end">
            <div>Subtotal: ${{ number_format($order->subtotal, 2) }}</div>
            <div>Discount: ${{ number_format($order->discount, 2) }}</div>
            <div>Shipping: ${{ number_format($order->shipping, 2) }}</div>
            <h2 class="h4">Total: ${{ number_format($order->total, 2) }}</h2>
        </div>
    </div>
</section>
@endsection
