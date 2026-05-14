@extends('layouts.app')
@section('title', 'Home')

@push('styles')
<style>
    /* Hero */
    .hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(249,115,22,.15) 0%, transparent 70%);
        top: -200px; right: -100px;
        border-radius: 50%;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(249,115,22,.15);
        border: 1px solid rgba(249,115,22,.3);
        color: #fdba74;
        padding: .4rem 1rem;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    .hero h1 { font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.2rem; }
    .hero h1 span { color: var(--primary); }
    .hero p { font-size: 1.1rem; color: var(--text-muted); max-width: 500px; line-height: 1.7; margin-bottom: 2rem; }
    .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
    .hero-stats { display: flex; gap: 2rem; margin-top: 3rem; }
    .hero-stat .value { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
    .hero-stat .label { font-size: .8rem; color: var(--text-muted); }

    /* Section Headers */
    .section-header { text-align: center; margin-bottom: 2.5rem; }
    .section-header h2 { font-size: 2rem; font-weight: 800; margin-bottom: .5rem; }
    .section-header p { color: var(--text-muted); }
    .section-header .line {
        width: 60px; height: 4px;
        background: var(--primary);
        border-radius: 2px;
        margin: .75rem auto 0;
    }

    /* Category Cards */
    .categories-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .cat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
    }
    .cat-card:hover { border-color: var(--primary); transform: translateY(-3px); }
    .cat-icon {
        width: 56px; height: 56px;
        background: rgba(249,115,22,.15);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: var(--primary);
        margin: 0 auto 1rem;
    }
    .cat-name { font-weight: 600; font-size: .9rem; }
    .cat-count { font-size: .75rem; color: var(--text-muted); margin-top: .2rem; }

    /* Product Cards */
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: all .25s;
    }
    .product-card:hover { transform: translateY(-4px); border-color: rgba(249,115,22,.4); box-shadow: 0 12px 40px rgba(249,115,22,.1); }
    .product-img {
        height: 200px;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .product-img img { width: 100%; height: 100%; object-fit: cover; }
    .product-img .no-img { font-size: 3rem; color: var(--border); }
    .product-badge {
        position: absolute;
        top: .75rem; left: .75rem;
        background: var(--primary);
        color: #fff;
        padding: .2rem .6rem;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 700;
    }
    .product-badge.sale { background: var(--danger); }
    .product-body { padding: 1.2rem; }
    .product-category { font-size: .75rem; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem; }
    .product-name { font-weight: 700; font-size: .95rem; margin-bottom: .5rem; line-height: 1.3; }
    .product-price { display: flex; align-items: center; gap: .5rem; margin-bottom: 1rem; }
    .price-current { font-size: 1.2rem; font-weight: 800; color: var(--primary); }
    .price-old { font-size: .85rem; color: var(--text-muted); text-decoration: line-through; }
    .product-footer { display: flex; gap: .5rem; }
    .btn-cart {
        flex: 1;
        padding: .55rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
        transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: .4rem;
    }
    .btn-cart:hover { background: var(--primary-dark); }
    .btn-view {
        padding: .55rem .8rem;
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all .2s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-view:hover { border-color: var(--primary); color: var(--primary); }

    /* Sale Banner */
    .sale-banner {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border-radius: 16px;
        padding: 3rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        position: relative;
    }
    .sale-banner::before {
        content: '';
        position: absolute;
        width: 300px; height: 300px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
        right: -100px; top: -100px;
    }
    .sale-banner h2 { font-size: 2rem; font-weight: 800; color: #fff; }
    .sale-banner p { color: rgba(255,255,255,.85); margin-top: .5rem; }
    .sale-banner .btn-white {
        background: #fff;
        color: var(--primary);
        padding: .75rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }
    .sale-banner .btn-white:hover { transform: scale(1.05); }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge"><i class="fas fa-bolt"></i> Multi-Branch E-Commerce</div>
            <h1>Shop Smart,<br>Shop <span>Anywhere</span></h1>
            <p>Discover thousands of products across all our branches. Fast delivery, real-time inventory, and the best prices guaranteed.</p>
            <div class="hero-actions">
                <a href="{{ route('products') }}" class="btn btn-primary" style="padding:.85rem 2rem;font-size:1rem;">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
                <a href="#categories" class="btn btn-outline" style="padding:.85rem 2rem;font-size:1rem;">
                    <i class="fas fa-th-large"></i> Browse Categories
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="value">500+</div>
                    <div class="label">Products</div>
                </div>
                <div class="hero-stat">
                    <div class="value">10+</div>
                    <div class="label">Branches</div>
                </div>
                <div class="hero-stat">
                    <div class="value">5K+</div>
                    <div class="label">Customers</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="section" id="categories">
    <div class="container">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <p>Find exactly what you're looking for</p>
            <div class="line"></div>
        </div>
        <div class="categories-grid">
            @forelse($categories as $cat)
            <a href="{{ route('products', ['category' => $cat->slug]) }}" class="cat-card">
                <div class="cat-icon"><i class="fas fa-tag"></i></div>
                <div class="cat-name">{{ $cat->name }}</div>
                <div class="cat-count">{{ $cat->products_count }} products</div>
            </a>
            @empty
            <p style="color:var(--text-muted);grid-column:1/-1;text-align:center;">No categories yet.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Featured Products --}}
@if($featuredProducts->count())
<section class="section" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Hand-picked products just for you</p>
            <div class="line"></div>
        </div>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Sale Banner --}}
@if($saleProducts->count())
<section class="section">
    <div class="container">
        <div class="sale-banner">
            <div>
                <h2>🔥 Hot Deals — Up to 50% Off!</h2>
                <p>Limited time offers on selected products. Don't miss out!</p>
            </div>
            <a href="{{ route('products') }}" class="btn-white">Shop Sale</a>
        </div>
    </div>
</section>
@endif

{{-- New Arrivals --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>New Arrivals</h2>
            <p>Fresh products added to our catalog</p>
            <div class="line"></div>
        </div>
        <div class="products-grid">
            @forelse($newArrivals as $product)
            @include('shop.partials.product-card', ['product' => $product])
            @empty
            <p style="color:var(--text-muted);grid-column:1/-1;text-align:center;padding:3rem;">No products yet. Check back soon!</p>
            @endforelse
        </div>
        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ route('products') }}" class="btn btn-outline" style="padding:.75rem 2.5rem;">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection
