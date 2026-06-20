@extends('layouts.frontend')
@section('title', 'Products')
@section('content')
<section class="container py-5">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="bg-white border rounded p-3">
                <h2 class="h5">Categories</h2>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action" href="{{ route('products.index') }}">All Products</a>
                    @foreach($categories as $category)
                        <a class="list-group-item list-group-item-action" href="{{ route('products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
        <div class="col-lg-9">
            <h1 class="section-title mb-3">Shop Products</h1>
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-6 col-xl-4">@include('partials.product-card', ['product' => $product])</div>
                @empty
                    <div class="col-12"><div class="alert alert-info">No products found.</div></div>
                @endforelse
            </div>
            @if($products->hasPages())
                <nav class="shop-pagination mt-4" aria-label="Product pagination">
                    {{ $products->links() }}
                </nav>
            @endif
        </div>
    </div>
</section>
@endsection
