<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $websiteTitle.' Admin')</title>
    @if($websiteFaviconUrl)
        <link rel="icon" href="{{ $websiteFaviconUrl }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}?v={{ file_exists(public_path('css/site.css')) ? filemtime(public_path('css/site.css')) : time() }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            @if($websiteLogoUrl)
                <img class="site-logo" src="{{ $websiteLogoUrl }}" alt="{{ $websiteTitle }}" height="32" style="height:32px;max-height:32px;max-width:120px;width:auto;object-fit:contain;">
            @endif
            <span>{{ $websiteTitle }} Admin</span>
        </a>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-light btn-sm" href="{{ route('home') }}">Storefront</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-warning btn-sm">Logout</button></form>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-xl-2 bg-white border-end admin-sidebar p-3">
            <nav class="nav flex-column gap-1">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('admin.products.index') }}">Products</a>
                <a class="nav-link" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="nav-link" href="{{ route('admin.brands.index') }}">Brands</a>
                <a class="nav-link" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="nav-link" href="{{ route('admin.customers.index') }}">Customers</a>
                <a class="nav-link" href="{{ route('admin.live-chat.index') }}">Live Chat</a>
                <a class="nav-link" href="{{ route('admin.coupons.index') }}">Coupons</a>
                <a class="nav-link" href="{{ route('admin.banners.index') }}">Banners</a>
                <a class="nav-link" href="{{ route('admin.settings.edit') }}">Site Settings</a>
            </nav>
        </aside>
        <section class="col-md-9 col-xl-10 p-4">
            @include('partials.flash')
            @yield('content')
        </section>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
