<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index', ['orders' => Order::with('user')->latest()->paginate(15)]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', ['order' => $order->load('items', 'user')]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
        ]);

        $order->update($data);

        return back()->with('success', 'Order status updated.');
    }
}
