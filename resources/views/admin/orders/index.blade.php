@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-3">Orders</h1>
<div class="bg-white border rounded p-3">
    @include('admin.orders.table', ['orders' => $orders])
    {{ $orders->links() }}
</div>
@endsection
