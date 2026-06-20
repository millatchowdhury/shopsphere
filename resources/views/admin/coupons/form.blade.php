@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-3">{{ $coupon->exists ? 'Edit' : 'Create' }} Coupon</h1>
<form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="bg-white border rounded p-4">
@csrf @if($coupon->exists) @method('PUT') @endif
<div class="row g-3"><div class="col-md-4"><label class="form-label">Code</label><input class="form-control" name="code" value="{{ old('code', $coupon->code) }}" required></div><div class="col-md-4"><label class="form-label">Type</label><select class="form-select" name="type"><option value="fixed" @selected($coupon->type === 'fixed')>Fixed</option><option value="percent" @selected($coupon->type === 'percent')>Percent</option></select></div><div class="col-md-4"><label class="form-label">Value</label><input class="form-control" type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required></div><div class="col-md-4"><label class="form-label">Minimum Order</label><input class="form-control" type="number" step="0.01" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount ?? 0) }}" required></div><div class="col-md-4"><label class="form-label">Starts At</label><input class="form-control" type="datetime-local" name="starts_at"></div><div class="col-md-4"><label class="form-label">Expires At</label><input class="form-control" type="datetime-local" name="expires_at"></div></div>
<label class="form-check my-3"><input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $coupon->status ?? true))> Active</label><button class="btn btn-dark">Save</button>
</form>
@endsection
