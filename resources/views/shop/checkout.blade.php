@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-page { padding: 3rem 0; }
    .checkout-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }
    .checkout-page h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; }
    .checkout-section {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .checkout-section-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
        display: flex; align-items: center; gap: .6rem;
        font-size: .95rem;
    }
    .checkout-section-header i { color: var(--primary); }
    .checkout-section-body { padding: 1.5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; margin-bottom: .4rem; font-size: .85rem; font-weight: 500; color: var(--text-muted); }
    .form-control { width: 100%; padding: .65rem 1rem; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: .9rem; transition: border-color .2s; }
    .form-control:focus { outline: none; border-color: var(--primary); }
    select.form-control option { background: var(--bg-card); }
    .payment-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
    .payment-option { position: relative; }
    .payment-option input { position: absolute; opacity: 0; }
    .payment-label {
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
        padding: 1rem;
        background: var(--bg-dark);
        border: 2px solid var(--border);
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s;
        font-size: .85rem; font-weight: 600;
    }
    .payment-label i { font-size: 1.5rem; color: var(--text-muted); }
    .payment-option input:checked + .payment-label { border-color: var(--primary); background: rgba(249,115,22,.08); }
    .payment-option input:checked + .payment-label i { color: var(--primary); }

    /* Order Summary */
    .order-summary { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; position: sticky; top: 80px; }
    .order-summary h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1.2rem; }
    .order-item { display: flex; align-items: center; gap: .75rem; padding: .6rem 0; border-bottom: 1px solid rgba(51,65,85,.5); }
    .order-item:last-of-type { border-bottom: none; }
    .order-item-img { width: 44px; height: 44px; border-radius: 8px; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .order-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .order-item-name { flex: 1; font-size: .85rem; font-weight: 600; }
    .order-item-qty { font-size: .75rem; color: var(--text-muted); }
    .order-item-price { font-weight: 700; font-size: .9rem; color: var(--primary); }
    .summary-divider { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }
    .summary-row { display: flex; justify-content: space-between; font-size: .9rem; padding: .3rem 0; }
    .summary-row .label { color: var(--text-muted); }
    .summary-row.total { font-size: 1.1rem; font-weight: 800; padding-top: .75rem; border-top: 1px solid var(--border); margin-top: .5rem; }
    .summary-row.total .value { color: var(--primary); }
    .btn-place-order {
        width: 100%; padding: .9rem;
        background: var(--primary); color: #fff;
        border: none; border-radius: 10px;
        font-size: 1rem; font-weight: 700;
        cursor: pointer; transition: all .2s;
        margin-top: 1.2rem;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .btn-place-order:hover { background: var(--primary-dark); transform: translateY(-1px); }
    @media (max-width: 768px) { .checkout-layout { grid-template-columns: 1fr; } .order-summary { position: static; } .form-row { grid-template-columns: 1fr; } .payment-options { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container checkout-page">
    <h1><i class="fas fa-lock" style="color:var(--primary);"></i> Checkout</h1>

    <form method="POST" action="{{ route('orders.place') }}">
        @csrf
        <div class="checkout-layout">
            <div>
                {{-- Shipping --}}
                <div class="checkout-section">
                    <div class="checkout-section-header">
                        <i class="fas fa-map-marker-alt"></i> Shipping Information
                    </div>
                    <div class="checkout-section-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly style="opacity:.7;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->phone ?? '' }}" readonly style="opacity:.7;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Shipping Address *</label>
                            <textarea name="shipping_address" class="form-control" rows="2" placeholder="Street address, apartment, etc." required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" name="shipping_city" class="form-control" placeholder="Cairo, Alexandria..." value="{{ old('shipping_city') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Branch --}}
                <div class="checkout-section">
                    <div class="checkout-section-header">
                        <i class="fas fa-building"></i> Select Branch
                    </div>
                    <div class="checkout-section-body">
                        <div class="form-group">
                            <label class="form-label">Fulfillment Branch *</label>
                            <select name="branch_id" class="form-control" required>
                                <option value="">— Choose a branch —</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} — {{ $branch->city ?? $branch->location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Order Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="checkout-section">
                    <div class="checkout-section-header">
                        <i class="fas fa-credit-card"></i> Payment Method
                    </div>
                    <div class="checkout-section-body">
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <div class="payment-label"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="card">
                                <div class="payment-label"><i class="fas fa-credit-card"></i> Credit Card</div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="online">
                                <div class="payment-label"><i class="fas fa-mobile-alt"></i> Online Payment</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="order-summary">
                <h3>Order Summary</h3>
                @php
                    $subtotal = $cart->items->sum(fn($i) => $i->product->current_price * $i->quantity);
                    $tax = round($subtotal * 0.14, 2);
                    $total = $subtotal + $tax;
                @endphp
                @foreach($cart->items as $item)
                <div class="order-item">
                    <div class="order-item-img">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="">
                        @else
                            <i class="fas fa-image" style="color:var(--border);font-size:.9rem;"></i>
                        @endif
                    </div>
                    <div>
                        <div class="order-item-name">{{ Str::limit($item->product->name, 30) }}</div>
                        <div class="order-item-qty">Qty: {{ $item->quantity }}</div>
                    </div>
                    <div class="order-item-price">${{ number_format($item->product->current_price * $item->quantity, 2) }}</div>
                </div>
                @endforeach
                <hr class="summary-divider">
                <div class="summary-row"><span class="label">Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                <div class="summary-row"><span class="label">Tax (14%)</span><span>${{ number_format($tax, 2) }}</span></div>
                <div class="summary-row"><span class="label">Shipping</span><span style="color:var(--success);">Free</span></div>
                <div class="summary-row total"><span class="label">Total</span><span class="value">${{ number_format($total, 2) }}</span></div>
                <button type="submit" class="btn-place-order">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
                <a href="{{ route('cart') }}" style="display:block;text-align:center;margin-top:.75rem;font-size:.85rem;color:var(--text-muted);">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
