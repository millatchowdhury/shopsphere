@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Order {{ $order->order_number }}</h1><a class="btn btn-outline-dark" href="{{ route('customer.orders.show', $order) }}">View Invoice</a></div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="bg-white border rounded p-4">
            <table class="table">
                <thead><tr><th>Product</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                <tbody>@foreach($order->items as $item)<tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td class="text-end">${{ number_format($item->line_total, 2) }}</td></tr>@endforeach</tbody>
            </table>
            <div class="text-end fw-bold">Grand Total: ${{ number_format($order->total, 2) }}</div>
        </div>
    </div>
    <div class="col-lg-4">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="bg-white border rounded p-4">
            @csrf @method('PUT')
            <label class="form-label">Order Status</label><select class="form-select mb-3" name="status">@foreach(\App\Models\Order::STATUSES as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
            <label class="form-label">Payment Status</label><select class="form-select mb-3" name="payment_status">@foreach(['pending','paid','failed','refunded'] as $status)<option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
            <button class="btn btn-dark">Update</button>
        </form>
    </div>
</div>
@endsection
