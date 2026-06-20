<div class="card product-card h-100">
    <img src="{{ $product->display_image }}" class="card-img-top" alt="{{ $product->name }}">
    <div class="card-body d-flex flex-column">
        <div class="small text-muted">{{ $product->category->name ?? 'Catalog' }}</div>
        <h3 class="h6 mt-1"><a class="text-decoration-none text-dark" href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <div class="mt-auto">
            <div class="fw-bold">
                ${{ number_format($product->selling_price, 2) }}
                @if($product->discount_price)
                    <span class="text-muted text-decoration-line-through fw-normal">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('cart.store', $product) }}" class="d-flex flex-column flex-sm-row gap-2 mt-3">
                @csrf
                <button class="btn btn-dark flex-fill" @disabled($product->stock_quantity < 1)>Add to Cart</button>
                <a class="btn btn-outline-dark flex-fill @if($product->stock_quantity < 1) disabled @endif" href="{{ route('products.show', $product) }}" @if($product->stock_quantity < 1) aria-disabled="true" tabindex="-1" @endif>Buy Now</a>
            </form>
        </div>
    </div>
</div>
