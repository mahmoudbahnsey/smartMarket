@extends('layouts.app')
@section('title', 'My Cart')

@push('styles')
<style>
    .cart-page { padding: 3rem 0; }
    .cart-layout { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }
    .cart-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .cart-header h1 { font-size: 1.8rem; font-weight: 800; }
    .cart-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.2rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 1rem;
        transition: border-color .2s;
    }
    .cart-item:hover { border-color: rgba(249,115,22,.3); }
    .cart-item-img {
        width: 80px; height: 80px;
        border-radius: 10px;
        background: var(--bg-card2);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; flex-shrink: 0;
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .cart-item-img i { font-size: 1.8rem; color: var(--border); }
    .cart-item-info { flex: 1; }
    .cart-item-name { font-weight: 700; font-size: .95rem; margin-bottom: .3rem; }
    .cart-item-cat { font-size: .75rem; color: var(--primary); font-weight: 600; margin-bottom: .5rem; }
    .cart-item-price { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .cart-item-actions { display: flex; align-items: center; gap: .75rem; }
    .qty-form { display: flex; align-items: center; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
    .qty-form button { width: 32px; height: 36px; background: var(--bg-card2); border: none; color: var(--text); cursor: pointer; font-size: 1rem; transition: background .2s; }
    .qty-form button:hover { background: var(--primary); }
    .qty-form input { width: 44px; height: 36px; background: var(--bg-dark); border: none; color: var(--text); text-align: center; font-size: .9rem; font-weight: 600; }
    .btn-remove { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: .9rem; padding: .4rem; border-radius: 6px; transition: all .2s; }
    .btn-remove:hover { color: var(--danger); background: rgba(239,68,68,.1); }
    .cart-item-subtotal { font-weight: 700; font-size: 1rem; min-width: 70px; text-align: right; }

    /* Summary */
    .cart-summary {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 80px;
    }
    .cart-summary h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; padding: .6rem 0; font-size: .9rem; }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: .5rem; padding-top: 1rem; font-size: 1.1rem; font-weight: 800; }
    .summary-row .label { color: var(--text-muted); }
    .summary-row.total .label { color: var(--text); }
    .summary-row .value { font-weight: 600; }
    .summary-row.total .value { color: var(--primary); font-size: 1.3rem; }
    .btn-checkout {
        width: 100%;
        padding: .9rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        margin-top: 1.2rem;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        text-decoration: none;
    }
    .btn-checkout:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .empty-cart { text-align: center; padding: 5rem 2rem; }
    .empty-cart i { font-size: 5rem; color: var(--border); margin-bottom: 1.5rem; display: block; }
    .empty-cart h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: .75rem; }
    .empty-cart p { color: var(--text-muted); margin-bottom: 2rem; }
    @media (max-width: 768px) { .cart-layout { grid-template-columns: 1fr; } .cart-summary { position: static; } }
</style>
@endpush

@section('content')
<div class="container cart-page">
    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart" style="color:var(--primary);"></i> My Cart</h1>
        @if($cart->items->count())
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Clear all items?')">
                <i class="fas fa-trash"></i> Clear Cart
            </button>
        </form>
        @endif
    </div>

    @if($cart->items->isEmpty())
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything yet.</p>
            <a href="{{ route('products') }}" class="btn btn-primary" style="padding:.75rem 2rem;">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @else
        <div class="cart-layout">
            {{-- Items --}}
            <div>
                @foreach($cart->items as $item)
                <div class="cart-item">
                    <div class="cart-item-img">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-cat">{{ $item->product->category->name ?? '' }}</div>
                        <div class="cart-item-name">{{ $item->product->name }}</div>
                        <div class="cart-item-price">${{ number_format($item->product->current_price, 2) }}</div>
                    </div>
                    <div class="cart-item-actions">
                        <form method="POST" action="{{ route('cart.update', $item) }}" class="qty-form">
                            @csrf @method('PATCH')
                            <button type="button" onclick="this.nextElementSibling.stepDown(); this.form.submit()">−</button>
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" onchange="this.form.submit()">
                            <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit()">+</button>
                        </form>
                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" title="Remove"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                    <div class="cart-item-subtotal">
                        ${{ number_format($item->product->current_price * $item->quantity, 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="cart-summary">
                <h3>Order Summary</h3>
                @php
                    $subtotal = $cart->items->sum(fn($i) => $i->product->current_price * $i->quantity);
                    $tax = round($subtotal * 0.14, 2);
                    $total = $subtotal + $tax;
                @endphp
                <div class="summary-row">
                    <span class="label">Subtotal ({{ $cart->items->sum('quantity') }} items)</span>
                    <span class="value">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Tax (14%)</span>
                    <span class="value">${{ number_format($tax, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Shipping</span>
                    <span class="value" style="color:var(--success);">Free</span>
                </div>
                <div class="summary-row total">
                    <span class="label">Total</span>
                    <span class="value">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn-checkout">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                <a href="{{ route('products') }}" style="display:block;text-align:center;margin-top:1rem;font-size:.85rem;color:var(--text-muted);">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
