<div class="table-responsive mt-3">
    <table class="table align-middle">
        <thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge text-bg-secondary status-pill">{{ $order->status }}</span></td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td><a class="btn btn-sm btn-outline-dark" href="{{ route('customer.orders.show', $order) }}">Invoice</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
