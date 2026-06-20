@extends('layouts.frontend')
@section('title', 'Customer Dashboard')
@section('content')
<section class="container py-5">
    <h1 class="section-title">Welcome, {{ auth()->user()->name }}</h1>
    <div class="bg-white border rounded p-4 mt-3">
        <div class="d-flex justify-content-between align-items-center"><h2 class="h5 mb-0">Recent Orders</h2><a href="{{ route('customer.orders') }}">View all</a></div>
        @include('frontend.dashboard.partials.order-table', ['orders' => $orders])
    </div>
</section>
@endsection
