<div class="product-card">
    <div class="product-img">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        @else
            <i class="fas fa-image no-img"></i>
        @endif
        @if($product->is_featured)
            <span class="product-badge">Featured</span>
        @elseif($product->sale_price)
            <span class="product-badge sale">Sale</span>
        @endif
    </div>
    <div class="product-body">
        <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
        <div class="product-name">{{ $product->name }}</div>
        <div class="product-price">
            <span class="price-current">${{ number_format($product->current_price, 2) }}</span>
            @if($product->sale_price)
                <span class="price-old">${{ number_format($product->price, 2) }}</span>
            @endif
        </div>
        <div class="product-footer">
            @auth
                <form method="POST" action="{{ route('cart.add') }}" style="flex:1;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-cart" style="flex:1;text-decoration:none;"><i class="fas fa-cart-plus"></i> Add to Cart</a>
            @endauth
            <a href="{{ route('products.show', $product->slug) }}" class="btn-view">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
</div>
