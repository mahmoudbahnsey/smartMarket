@extends('layouts.app')
@section('title', $product->name)

@push('styles')
<style>
    .product-detail { padding: 3rem 0; }
    .breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: var(--text-muted); margin-bottom: 2rem; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb span { color: var(--text-muted); }
    .product-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; }
    .product-image-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1;
        display: flex; align-items: center; justify-content: center;
    }
    .product-image-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .product-image-wrap .no-img { font-size: 6rem; color: var(--border); }
    .product-info {}
    .product-category-tag {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(249,115,22,.15); color: var(--primary);
        padding: .3rem .8rem; border-radius: 20px;
        font-size: .8rem; font-weight: 600;
        margin-bottom: 1rem;
    }
    .product-title { font-size: 2rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem; }
    .product-rating { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.2rem; }
    .stars { color: #f59e0b; font-size: .9rem; }
    .rating-count { font-size: .85rem; color: var(--text-muted); }
    .product-price-wrap { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .price-big { font-size: 2.2rem; font-weight: 800; color: var(--primary); }
    .price-old-big { font-size: 1.2rem; color: var(--text-muted); text-decoration: line-through; }
    .sale-pct {
        background: var(--danger); color: #fff;
        padding: .2rem .6rem; border-radius: 6px;
        font-size: .8rem; font-weight: 700;
    }
    .product-desc { color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem; font-size: .95rem; }
    .stock-info { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; font-size: .875rem; }
    .stock-dot { width: 8px; height: 8px; border-radius: 50%; }
    .stock-dot.in { background: var(--success); }
    .stock-dot.out { background: var(--danger); }
    .add-cart-form { display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
    .qty-wrap { display: flex; align-items: center; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .qty-btn { width: 40px; height: 44px; background: var(--bg-card2); border: none; color: var(--text); font-size: 1.1rem; cursor: pointer; transition: background .2s; }
    .qty-btn:hover { background: var(--primary); }
    .qty-input { width: 50px; height: 44px; background: var(--bg-dark); border: none; color: var(--text); text-align: center; font-size: 1rem; font-weight: 600; }
    .btn-add-cart {
        flex: 1; padding: .85rem 1.5rem;
        background: var(--primary); color: #fff;
        border: none; border-radius: 10px;
        font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .btn-add-cart:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .branch-stock { margin-bottom: 1.5rem; }
    .branch-stock h4 { font-size: .875rem; font-weight: 600; color: var(--text-muted); margin-bottom: .75rem; }
    .branch-item { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; margin-bottom: .4rem; font-size: .85rem; }
    .branch-item .qty { font-weight: 700; color: var(--success); }
    .branch-item .qty.low { color: var(--warning); }
    .branch-item .qty.out { color: var(--danger); }

    /* Reviews */
    .reviews-section { margin-top: 3rem; }
    .reviews-section h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem; }
    .review-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.2rem; margin-bottom: 1rem; }
    .review-header { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; }
    .reviewer-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; color: #fff; }
    .reviewer-name { font-weight: 600; font-size: .9rem; }
    .review-date { font-size: .75rem; color: var(--text-muted); }
    .review-stars { color: #f59e0b; font-size: .85rem; }
    .review-text { color: var(--text-muted); font-size: .875rem; line-height: 1.6; }

    /* Related */
    .related-section { margin-top: 3rem; }
    .related-section h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }

    @media (max-width: 768px) { .product-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container product-detail">
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('products') }}">Products</a>
        <span>/</span>
        <a href="{{ route('products', ['category' => $product->category->slug ?? '']) }}">{{ $product->category->name ?? 'Uncategorized' }}</a>
        <span>/</span>
        <span style="color:var(--text);">{{ $product->name }}</span>
    </div>

    <div class="product-grid">
        {{-- Image --}}
        <div class="product-image-wrap">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-image no-img"></i>
            @endif
        </div>

        {{-- Info --}}
        <div class="product-info">
            <div class="product-category-tag">
                <i class="fas fa-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}
            </div>
            <h1 class="product-title">{{ $product->name }}</h1>

            <div class="product-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $product->average_rating ? '' : ($i - 0.5 <= $product->average_rating ? '-half-alt' : '') }}"
                           style="{{ $i <= $product->average_rating ? '' : 'opacity:.3' }}"></i>
                    @endfor
                </div>
                <span class="rating-count">({{ $product->reviews->count() }} reviews)</span>
            </div>

            <div class="product-price-wrap">
                <span class="price-big">${{ number_format($product->current_price, 2) }}</span>
                @if($product->sale_price)
                    <span class="price-old-big">${{ number_format($product->price, 2) }}</span>
                    <span class="sale-pct">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                @endif
            </div>

            @if($product->description)
                <p class="product-desc">{{ $product->description }}</p>
            @endif

            {{-- Stock --}}
            <div class="stock-info">
                @if($product->total_stock > 0)
                    <span class="stock-dot in"></span>
                    <span style="color:var(--success);font-weight:600;">In Stock</span>
                    <span style="color:var(--text-muted);">({{ $product->total_stock }} available)</span>
                @else
                    <span class="stock-dot out"></span>
                    <span style="color:var(--danger);font-weight:600;">Out of Stock</span>
                @endif
            </div>

            {{-- Branch Stock --}}
            @if($product->inventories->count())
            <div class="branch-stock">
                <h4><i class="fas fa-building"></i> Stock by Branch</h4>
                @foreach($product->inventories as $inv)
                <div class="branch-item">
                    <span>{{ $inv->branch->name }}</span>
                    <span class="qty {{ $inv->quantity == 0 ? 'out' : ($inv->quantity <= $inv->low_stock_alert ? 'low' : '') }}">
                        {{ $inv->quantity }} units
                    </span>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Add to Cart --}}
            @auth
                @if($product->total_stock > 0)
                <form method="POST" action="{{ route('cart.add') }}" class="add-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="qty-wrap">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qty" class="qty-input" value="1" min="1" max="{{ $product->total_stock }}">
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <button type="submit" class="btn-add-cart">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
                @else
                    <div class="alert alert-danger" style="margin-bottom:1rem;">This product is currently out of stock.</div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-add-cart" style="display:flex;text-decoration:none;margin-bottom:1rem;">
                    <i class="fas fa-sign-in-alt"></i> Login to Add to Cart
                </a>
            @endauth
        </div>
    </div>

    {{-- Reviews --}}
    <div class="reviews-section">
        <h2><i class="fas fa-star" style="color:#f59e0b;"></i> Customer Reviews</h2>
        @forelse($product->reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-avatar">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                <div>
                    <div class="reviewer-name">{{ $review->user->name }}</div>
                    <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                </div>
                <div class="review-stars" style="margin-left:auto;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="{{ $i <= $review->rating ? '' : 'opacity:.3' }}"></i>
                    @endfor
                </div>
            </div>
            @if($review->comment)
                <p class="review-text">{{ $review->comment }}</p>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--text-muted);">
            <i class="fas fa-comment-slash" style="font-size:2rem;margin-bottom:.75rem;display:block;"></i>
            No reviews yet. Be the first to review!
        </div>
        @endforelse
    </div>

    {{-- Related Products --}}
    @if($related->count())
    <div class="related-section">
        <h2>Related Products</h2>
        <div class="related-grid">
            @foreach($related as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const val = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    if (val >= 1 && val <= max) input.value = val;
}
</script>
@endpush
