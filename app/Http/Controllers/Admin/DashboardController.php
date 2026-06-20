<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveChatMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'newChatMessages' => LiveChatMessage::where('status', 'new')->count(),
            'totalRevenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'recentOrders' => Order::latest()->limit(8)->get(),
        ]);
    }
}
