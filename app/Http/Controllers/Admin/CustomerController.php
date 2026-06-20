<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index', [
            'customers' => User::where('role', 'customer')->withCount('orders')->latest()->paginate(15),
        ]);
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        return view('admin.customers.show', ['customer' => $customer->load('orders')]);
    }
}
