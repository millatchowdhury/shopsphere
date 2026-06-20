@extends('layouts.frontend')
@section('title', $websiteTitle.' - Modern Ecommerce')
@section('content')
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @forelse($banners as $banner)
            <div class="carousel-item @if($loop->first) active @endif">
                <section class="hero-slide d-flex align-items-center" style="background-image:url('{{ $banner->image_path }}')">
                    <div class="container hero-content text-white">
                        <h1 class="display-5 fw-bold">{{ $banner->title }}</h1>
                        <p class="lead">{{ $banner->subtitle }}</p>
                        @if($banner->button_text)
                            <a class="btn btn-warning btn-lg" href="{{ $banner->button_link ?: route('products.index') }}">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                </section>
            </div>
        @empty
            <div class="carousel-item active">
                <section class="hero-slide d-flex align-items-center" style="background-image:url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1800&q=80')">
                    <div class="container hero-content text-white">
                        <h1 class="display-5 fw-bold">Fresh Products, Simple Checkout</h1>
                        <p class="lead">Browse curated products, pay cash on delivery, and track every order.</p>
                        <a class="btn btn-warning btn-lg" href="{{ route('products.index') }}">Shop Now</a>
                    </div>
                </section>
            </div>
        @endforelse
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</div>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title">Product Categories</h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm">View all</a>
    </div>
    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-6 col-md-3">
                <a class="text-decoration-none text-dark" href="{{ route('products.index', ['category' => $category->slug]) }}">
                    <div class="category-tile bg-white border rounded overflow-hidden">
                        <img class="w-100" src="{{ $category->image ?: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $category->name }}">
                        <div class="p-3 fw-semibold">{{ $category->name }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<section class="container py-4">
    <h2 class="section-title mb-3">Featured Products</h2>
    <div class="row g-4">
        @foreach($featuredProducts as $product)
            <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
        @endforeach
    </div>
</section>

<section class="container py-5">
    <h2 class="section-title mb-3">New Arrivals</h2>
    <div class="row g-4">
        @foreach($newArrivals as $product)
            <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
        @endforeach
    </div>
</section>
@endsection
