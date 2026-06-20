<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.dashboard.index', [
            'orders' => $request->user()->orders()->latest()->limit(5)->get(),
        ]);
    }

    public function orders(Request $request)
    {
        return view('frontend.dashboard.orders', [
            'orders' => $request->user()->orders()->latest()->paginate(10),
        ]);
    }

    public function showOrder(Order $order)
    {
        Gate::authorize('view', $order);

        return view('frontend.dashboard.order-show', ['order' => $order->load('items')]);
    }
}
