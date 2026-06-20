@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<h1 class="h3 mb-4">Dashboard</h1>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Products</div><div class="h3">{{ $totalProducts }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Orders</div><div class="h3">{{ $totalOrders }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Customers</div><div class="h3">{{ $totalCustomers }}</div></div></div>
    <div class="col-md-3"><a class="text-decoration-none text-dark" href="{{ route('admin.live-chat.index') }}"><div class="bg-white border rounded p-3"><div class="text-muted">New Chats</div><div class="h3">{{ $newChatMessages }}</div></div></a></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Revenue</div><div class="h3">${{ number_format($totalRevenue, 2) }}</div></div></div>
</div>
<div class="bg-white border rounded p-4">
    <h2 class="h5">Recent Orders</h2>
    @include('admin.orders.table', ['orders' => $recentOrders])
</div>
@endsection
