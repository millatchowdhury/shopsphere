@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-3">{{ $banner->exists ? 'Edit' : 'Create' }} Banner</h1>
<form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" class="bg-white border rounded p-4">
@csrf @if($banner->exists) @method('PUT') @endif
<label class="form-label">Title</label><input class="form-control mb-3" name="title" value="{{ old('title', $banner->title) }}" required>
<label class="form-label">Subtitle</label><input class="form-control mb-3" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
<label class="form-label">Button Text</label><input class="form-control mb-3" name="button_text" value="{{ old('button_text', $banner->button_text) }}">
<label class="form-label">Button Link</label><input class="form-control mb-3" name="button_link" value="{{ old('button_link', $banner->button_link) }}">
<label class="form-label">Image URL</label><input class="form-control mb-3" name="image_path" value="{{ old('image_path', $banner->image_path) }}" required>
<label class="form-label">Sort Order</label><input class="form-control mb-3" type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" required>
<label class="form-check mb-3"><input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $banner->status ?? true))> Active</label>
<button class="btn btn-dark">Save</button></form>
@endsection
