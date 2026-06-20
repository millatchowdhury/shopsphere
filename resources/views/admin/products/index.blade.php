@extends('layouts.admin')
@section('title', 'Products')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3">Products</h1><a class="btn btn-dark" href="{{ route('admin.products.create') }}">Add Product</a></div>
<div class="bg-white border rounded p-3 table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Name</th><th>Category</th><th>Brand</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}<div class="small text-muted">{{ $product->sku }}</div></td>
                <td>{{ $product->category->name }}</td><td>{{ $product->brand->name ?? '-' }}</td>
                <td>${{ number_format($product->selling_price, 2) }}</td><td>{{ $product->stock_quantity }}</td>
                <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                    <form class="d-inline" data-confirm="Delete this product?" method="POST" action="{{ route('admin.products.destroy', $product) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
