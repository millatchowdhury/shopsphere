@extends('layouts.admin')
@section('content')
@include('admin.shared.taxonomy-form', ['model' => $brand, 'resource' => 'brands', 'hasLogo' => true])
@endsection
