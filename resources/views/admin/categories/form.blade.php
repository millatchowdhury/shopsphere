@extends('layouts.admin')
@section('content')
@include('admin.shared.taxonomy-form', ['model' => $category, 'resource' => 'categories', 'hasLogo' => false])
@endsection
