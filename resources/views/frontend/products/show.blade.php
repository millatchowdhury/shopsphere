@extends('layouts.frontend')
@section('title', $product->name)
@section('content')
<section class="container py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <img class="img-fluid rounded border bg-white" src="{{ $product->display_image }}" alt="{{ $product->name }}">
            <div class="row g-2 mt-2">
                @foreach($product->images as $image)
                    <div class="col-3"><img class="img-fluid rounded border" src="{{ $image->image_path }}" alt="{{ $product->name }}"></div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <div class="text-muted">{{ $product->brand->name ?? 'ShopSphere' }} / {{ $product->category->name }}</div>
            <h1 class="fw-bold">{{ $product->name }}</h1>
            <p class="h3">${{ number_format($product->selling_price, 2) }}</p>
            <p>{{ $product->description }}</p>
            <div class="mb-3">SKU: <strong>{{ $product->sku }}</strong> | Stock: <strong>{{ $product->stock_quantity }}</strong></div>
            <form method="POST" action="{{ route('cart.store', $product) }}" class="d-flex flex-wrap gap-2">
                @csrf
                <input class="form-control" style="max-width:100px" type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock_quantity) }}">
                <button class="btn btn-dark" @disabled($product->stock_quantity < 1)>Add to Cart</button>
                <button class="btn btn-outline-dark" formaction="{{ route('cart.buy-now', $product) }}" @disabled($product->stock_quantity < 1)>Buy Now</button>
            </form>
        </div>
    </div>
    @if($relatedProducts->isNotEmpty())
        <h2 class="section-title mt-5 mb-3">Related Products</h2>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $related])</div>
            @endforeach
        </div>
    @endif
</section>
@endsection
