@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Coupons</h1><a class="btn btn-dark" href="{{ route('admin.coupons.create') }}">Add Coupon</a></div>
<div class="bg-white border rounded p-3 table-responsive"><table class="table"><thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($coupons as $coupon)<tr><td>{{ $coupon->code }}</td><td>{{ $coupon->type }}</td><td>{{ $coupon->value }}</td><td>{{ $coupon->status ? 'Active' : 'Inactive' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a><form class="d-inline" data-confirm="Delete coupon?" method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach
</tbody></table>{{ $coupons->links() }}</div>
@endsection
