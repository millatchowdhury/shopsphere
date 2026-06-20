@extends('layouts.frontend')
@section('title', 'Order History')
@section('content')
<section class="container py-5">
    <h1 class="section-title">Order History</h1>
    <div class="bg-white border rounded p-4 mt-3">
        @include('frontend.dashboard.partials.order-table', ['orders' => $orders])
        {{ $orders->links() }}
    </div>
</section>
@endsection
