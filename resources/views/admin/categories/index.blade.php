@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Categories</h1><a class="btn btn-dark" href="{{ route('admin.categories.create') }}">Add Category</a></div>
@include('admin.shared.simple-table', ['items' => $categories, 'resource' => 'categories'])
@endsection
