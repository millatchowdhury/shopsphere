@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-3">Website Settings</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="bg-white border rounded p-4">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-7">
        <label class="form-label">Website Title</label><input class="form-control mb-3" name="website_title" value="{{ old('website_title', $settings['website_title'] ?? '') }}">
        <label class="form-label">Store Name</label><input class="form-control mb-3" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}">
        <label class="form-label">Store Email</label><input class="form-control mb-3" type="email" name="store_email" value="{{ old('store_email', $settings['store_email'] ?? 'support@shopsphere.test') }}" required>
        <label class="form-label">Store Phone</label><input class="form-control mb-3" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}">
        <label class="form-label">Store Address</label><textarea class="form-control mb-3" name="store_address">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
        <label class="form-label">Currency Symbol</label><input class="form-control mb-3" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '$') }}" required>
    </div>
    <div class="col-lg-5">
        <label class="form-label">Website Logo</label>
        @if(! empty($settings['website_logo']))
            <div class="border rounded p-3 mb-2 bg-light">
                @if($websiteLogoUrl)
                    <img class="settings-logo-preview" src="{{ $websiteLogoUrl }}" style="width:120px; height:32px;" alt="Website logo">
                @else
                    <div class="text-danger small">The saved logo file could not be found.</div>
                @endif
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="delete_website_logo" value="1" id="deleteWebsiteLogo">
                    <label class="form-check-label" for="deleteWebsiteLogo">Delete current logo</label>
                </div>
            </div>
        @endif
        <input class="form-control mb-3" type="file" name="website_logo" accept="image/*">

        <label class="form-label">Website Favicon</label>
        @if(! empty($settings['website_favicon']))
            <div class="border rounded p-3 mb-2 bg-light">
                @if($websiteFaviconUrl)
                    <img class="settings-favicon-preview" src="{{ $websiteFaviconUrl }}" style="width: 64px; height:64px;" alt="Website favicon">
                @else
                    <div class="text-danger small">The saved favicon file could not be found.</div>
                @endif
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="delete_website_favicon" value="1" id="deleteWebsiteFavicon">
                    <label class="form-check-label" for="deleteWebsiteFavicon">Delete current favicon</label>
                </div>
            </div>
        @endif
        <input class="form-control mb-3" type="file" name="website_favicon" accept=".ico,image/*">
    </div>
</div>
<button class="btn btn-dark">Save Settings</button>
</form>
@endsection
