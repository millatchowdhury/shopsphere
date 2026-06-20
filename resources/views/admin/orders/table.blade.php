<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td><td>{{ $order->customer_name }}</td><td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge text-bg-secondary status-pill">{{ $order->status }}</span></td><td>{{ $order->created_at->format('M d, Y') }}</td>
                <td><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
