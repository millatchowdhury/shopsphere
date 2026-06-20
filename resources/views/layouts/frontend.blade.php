<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $websiteTitle)</title>
    @if($websiteFaviconUrl)
        <link rel="icon" href="{{ $websiteFaviconUrl }}">
    @endif
    @php($assetBase = rtrim(request()->getBaseUrl(), '/'))
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ $assetBase }}/css/site.css?v={{ file_exists(public_path('css/site.css')) ? filemtime(public_path('css/site.css')) : time() }}" rel="stylesheet">
</head>
<body>
@inject('cart', 'App\Services\CartService')
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            @if($websiteLogoUrl)
                <img class="site-logo" src="{{ $websiteLogoUrl }}" alt="{{ $websiteTitle }}" style="width:120px; height:32px;">
            @endif
            <span>{{ $websiteTitle }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavigation">
            <form class="d-flex ms-lg-4 my-3 my-lg-0 flex-grow-1" action="{{ route('products.index') }}">
                <input class="form-control" type="search" name="query" value="{{ request('query') }}" placeholder="Search products, SKU, or category">
                <button class="btn btn-dark ms-2" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-lg-4 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">All Products</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart <span class="badge text-bg-secondary">{{ $cart->count() }}</span></a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-dark btn-sm">Logout</button></form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @include('partials.flash')
    @yield('content')
</main>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
            <h5>{{ $websiteTitle }}</h5>
            <p class="text-white-50 mb-0">Production-ready Laravel ecommerce foundation.</p>
        </div>
        <div class="text-white-50">Cash on Delivery available. Secure checkout with Laravel CSRF protection.</div>
    </div>
</footer>

<div class="live-chat-shell" data-live-chat>
    <section class="live-chat-panel shadow d-none" data-live-chat-panel aria-label="Live chat">
        <div class="live-chat-header">
            <div>
                <div class="fw-semibold">Live Chat</div>
                <div class="small text-white-50">Send us a message</div>
            </div>
            <button class="btn btn-sm btn-outline-light" type="button" data-live-chat-close aria-label="Close live chat">x</button>
        </div>
        <form class="live-chat-form" data-live-chat-form method="POST" action="{{ route('live-chat.store') }}">
            @csrf
            <div class="p-3">
                <div class="alert alert-success d-none mb-3" data-live-chat-success></div>
                <div class="alert alert-danger d-none mb-3" data-live-chat-error></div>
                <input class="form-control form-control-sm mb-2" name="name" placeholder="Name">
                <input class="form-control form-control-sm mb-2" type="email" name="email" placeholder="Email">
                <input class="form-control form-control-sm mb-2" name="phone" placeholder="Phone">
                <textarea class="form-control form-control-sm mb-3" name="message" rows="4" placeholder="How can we help?" required></textarea>
                <button class="btn btn-success w-100" type="submit" data-live-chat-submit>Send Message</button>
            </div>
        </form>
    </section>
    <button class="btn btn-success shadow live-chat-widget" type="button" data-live-chat-toggle>Live Chat</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ $assetBase }}/js/site.js?v={{ file_exists(public_path('js/site.js')) ? filemtime(public_path('js/site.js')) : time() }}"></script>
<script>
    (function () {
        var chat = document.querySelector('[data-live-chat]');

        if (!chat || chat.dataset.liveChatReady === 'true') {
            return;
        }

        var panel = chat.querySelector('[data-live-chat-panel]');
        var toggle = chat.querySelector('[data-live-chat-toggle]');
        var close = chat.querySelector('[data-live-chat-close]');

        if (!panel || !toggle) {
            return;
        }

        chat.dataset.liveChatReady = 'true';

        toggle.addEventListener('click', function () {
            panel.classList.toggle('d-none');
        });

        if (close) {
            close.addEventListener('click', function () {
                panel.classList.add('d-none');
            });
        }
    })();
</script>
</body>
</html>
