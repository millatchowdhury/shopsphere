@extends('layouts.admin')
@section('title', $product->exists ? 'Edit Product' : 'Create Product')
@section('content')
<h1 class="h3 mb-3">{{ $product->exists ? 'Edit Product' : 'Create Product' }}</h1>
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white border rounded p-4">
    @csrf @if($product->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ old('name', $product->name) }}" required></div>
        <div class="col-md-6"><label class="form-label">Slug</label><input class="form-control" name="slug" value="{{ old('slug', $product->slug) }}"></div>
        <div class="col-md-6"><label class="form-label">Category</label><select class="form-select" name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">Brand</label><select class="form-select" name="brand_id"><option value="">No brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Price</label><input class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required></div>
        <div class="col-md-3"><label class="form-label">Discount Price</label><input class="form-control" type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"></div>
        <div class="col-md-3"><label class="form-label">Stock Quantity</label><input class="form-control" type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required></div>
        <div class="col-md-3"><label class="form-label">SKU</label><input class="form-control" name="sku" value="{{ old('sku', $product->sku) }}" required></div>
        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea></div>
        <div class="col-12">
            <label class="form-label">Product Images</label>
            <input class="form-control" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple>
            <div class="form-text">Upload JPG, JPEG, PNG, or WEBP images. Uploading new images replaces the current product images.</div>
            @if($product->images->isNotEmpty())
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach($product->images as $image)
                        <img src="{{ $image->image_path }}" class="rounded border object-fit-cover" style="width: 96px; height: 96px;" alt="{{ $product->name }}">
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-md-3"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label></div>
        <div class="col-md-3"><label class="form-check"><input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $product->status ?? true))> Active</label></div>
    </div>
    <button class="btn btn-dark mt-4">Save Product</button>
</form>
@endsection
