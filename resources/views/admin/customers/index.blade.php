@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-3">Customers</h1>
<div class="bg-white border rounded p-3 table-responsive">
    <table class="table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th></th></tr></thead><tbody>
    @foreach($customers as $customer)<tr><td>{{ $customer->name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone }}</td><td>{{ $customer->orders_count }}</td><td><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.customers.show', $customer) }}">View</a></td></tr>@endforeach
    </tbody></table>{{ $customers->links() }}
</div>
@endsection
