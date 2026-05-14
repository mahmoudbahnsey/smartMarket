@extends('layouts.app')
@section('title', 'Products')

@push('styles')
<style>
    .shop-layout { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; padding: 2rem 0; }
    .sidebar-filter { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; height: fit-content; position: sticky; top: 80px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .filter-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem; color: var(--primary); }
    .filter-section { margin-bottom: 2rem; }
    .filter-section h4 { font-size: .9rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); margin-bottom: 1rem; padding-bottom: .5rem; border-bottom: 1px solid var(--border); }
    .filter-item { display: flex; align-items: center; gap: .5rem; padding: .6rem .8rem; cursor: pointer; font-size: .9rem; color: var(--text-muted); transition: all .2s; border-radius: 6px; margin-bottom: .3rem; }
    .filter-item:hover, .filter-item.active { background: rgba(249,115,22,.1); color: var(--primary); }
    .filter-item input { accent-color: var(--primary); transform: scale(1.2); }
    .price-inputs { display: flex; gap: .8rem; align-items: center; margin-bottom: 1rem; }
    .price-inputs input { flex: 1; padding: .6rem; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: .9rem; transition: border-color .2s; }
    .price-inputs input:focus { outline: none; border-color: var(--primary); }
    .price-inputs span { color: var(--text-muted); font-size: .9rem; font-weight: 600; }
    .products-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .products-count { color: var(--text-muted); font-size: .875rem; }
    .search-bar { display: flex; gap: .5rem; }
    .search-bar input { flex: 1; padding: .6rem 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: .875rem; }
    .search-bar input:focus { outline: none; border-color: var(--primary); }
    .search-bar button { padding: .6rem 1rem; background: var(--primary); color: #fff; border: none; border-radius: 8px; cursor: pointer; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.2rem; }
    .product-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; transition: all .25s; }
    .product-card:hover { transform: translateY(-4px); border-color: rgba(249,115,22,.4); box-shadow: 0 12px 40px rgba(249,115,22,.1); }
    .product-img { height: 180px; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .product-img img { width: 100%; height: 100%; object-fit: cover; }
    .product-img .no-img { font-size: 2.5rem; color: var(--border); }
    .product-badge { position: absolute; top: .6rem; left: .6rem; background: var(--primary); color: #fff; padding: .15rem .5rem; border-radius: 5px; font-size: .7rem; font-weight: 700; }
    .product-badge.sale { background: var(--danger); }
    .product-body { padding: 1rem; }
    .product-category { font-size: .7rem; color: var(--primary); font-weight: 600; text-transform: uppercase; margin-bottom: .3rem; }
    .product-name { font-weight: 700; font-size: .875rem; margin-bottom: .4rem; line-height: 1.3; }
    .product-price { display: flex; align-items: center; gap: .4rem; margin-bottom: .8rem; }
    .price-current { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .price-old { font-size: .8rem; color: var(--text-muted); text-decoration: line-through; }
    .product-footer { display: flex; gap: .4rem; }
    .btn-cart { flex: 1; padding: .5rem; background: var(--primary); color: #fff; border: none; border-radius: 7px; font-weight: 600; font-size: .8rem; cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: .3rem; }
    .btn-cart:hover { background: var(--primary-dark); }
    .btn-view { padding: .5rem .7rem; background: transparent; color: var(--text-muted); border: 1px solid var(--border); border-radius: 7px; cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; }
    .btn-view:hover { border-color: var(--primary); color: var(--primary); }
    @media (max-width: 768px) { .shop-layout { grid-template-columns: 1fr; } .sidebar-filter { position: static; } }
</style>
@endpush

@section('content')
<div class="container">
    <div class="shop-layout">
        {{-- Sidebar Filter --}}
        <aside class="sidebar-filter">
            <div class="filter-title"><i class="fas fa-filter" style="color:var(--primary);"></i> Filters</div>
            <form method="GET" action="{{ route('products') }}">
                <div class="filter-section">
                    <h4>Search</h4>
                    <div class="search-bar">
                        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="filter-section">
                    <h4>Categories</h4>
                    <a href="{{ route('products') }}" class="filter-item {{ !request('category') ? 'active' : '' }}">
                        <i class="fas fa-circle" style="font-size:.5rem;"></i> All Categories
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products', ['category' => $cat->slug]) }}" class="filter-item {{ request('category') == $cat->slug ? 'active' : '' }}">
                        <i class="fas fa-circle" style="font-size:.5rem;"></i> {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
                <div class="filter-section">
                    <h4>Price Range</h4>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                        <span>—</span>
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" style="width:100%;margin-top:.75rem;padding:.7rem;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600;font-size:.9rem;transition:all .2s;">Apply Filters</button>
                </div>
            </form>
        </aside>

        {{-- Products --}}
        <div>
            <div class="products-header">
                <div class="products-count">
                    Showing <strong>{{ $products->total() }}</strong> products
                    @if(request('category')) in <strong>{{ request('category') }}</strong>@endif
                </div>
            </div>

            @if($products->isEmpty())
                <div style="text-align:center;padding:4rem;color:var(--text-muted);">
                    <i class="fas fa-search" style="font-size:3rem;margin-bottom:1rem;display:block;"></i>
                    <p>No products found. Try different filters.</p>
                </div>
            @else
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-img">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <i class="fas fa-image no-img"></i>
                            @endif
                            @if($product->sale_price)
                                <span class="product-badge sale">Sale</span>
                            @elseif($product->is_featured)
                                <span class="product-badge">Featured</span>
                            @endif
                        </div>
                        <div class="product-body">
                            <div class="product-category">{{ $product->category->name ?? '' }}</div>
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
                                        <button type="submit" class="btn-cart"><i class="fas fa-cart-plus"></i> Add</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-cart" style="flex:1;text-decoration:none;"><i class="fas fa-cart-plus"></i> Add</a>
                                @endauth
                                <a href="{{ route('products.show', $product->slug) }}" class="btn-view"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
