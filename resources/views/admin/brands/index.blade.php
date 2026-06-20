@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Brands</h1><a class="btn btn-dark" href="{{ route('admin.brands.create') }}">Add Brand</a></div>
@include('admin.shared.simple-table', ['items' => $brands, 'resource' => 'brands'])
@endsection
