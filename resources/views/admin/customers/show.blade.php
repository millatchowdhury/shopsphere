@extends('layouts.admin')
@section('content')
<h1 class="h3">{{ $customer->name }}</h1>
<div class="bg-white border rounded p-4 mb-4"><p>{{ $customer->email }}</p><p>{{ $customer->phone }}</p><p>{{ $customer->address }}</p></div>
<div class="bg-white border rounded p-4">@include('admin.orders.table', ['orders' => $customer->orders])</div>
@endsection
