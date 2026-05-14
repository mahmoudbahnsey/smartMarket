@extends('layouts.app')
@section('title', 'My Orders')

@push('styles')
<style>
    .orders-page { padding: 3rem 0; }
    .orders-page h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; }
    .order-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: border-color .2s;
    }
    .order-card:hover { border-color: rgba(249,115,22,.3); }
    .order-card-header {
        padding: 1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap; gap: .75rem;
    }
    .order-number { font-weight: 700; font-size: .95rem; }
    .order-number span { color: var(--primary); }
    .order-meta { display: flex; align-items: center; gap: 1.5rem; font-size: .85rem; color: var(--text-muted); }
    .order-meta i { color: var(--primary); margin-right: .3rem; }
    .order-card-body { padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .order-items-preview { display: flex; gap: .5rem; }
    .order-item-thumb { width: 44px; height: 44px; border-radius: 8px; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border); }
    .order-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .order-item-thumb i { font-size: .9rem; color: var(--border); }
    .order-more { width: 44px; height: 44px; border-radius: 8px; background: var(--bg-card2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; color: var(--text-muted); }
    .order-total { font-size: 1.2rem; font-weight: 800; color: var(--primary); }
    .badge { display: inline-flex; align-items: center; padding: .25rem .65rem; border-radius: 20px; font-size: .75rem; font-weight: 600; }
    .badge-warning  { background: rgba(245,158,11,.15); color: #fcd34d; }
    .badge-info     { background: rgba(59,130,246,.15);  color: #93c5fd; }
    .badge-primary  { background: rgba(249,115,22,.15);  color: #fdba74; }
    .badge-success  { background: rgba(34,197,94,.15);   color: #86efac; }
    .badge-danger   { background: rgba(239,68,68,.15);   color: #fca5a5; }
    .badge-secondary{ background: rgba(148,163,184,.15); color: #cbd5e1; }
    .empty-state { text-align: center; padding: 5rem 2rem; }
    .empty-state i { font-size: 4rem; color: var(--border); margin-bottom: 1.5rem; display: block; }
</style>
@endpush

@section('content')
<div class="container orders-page">
    <h1><i class="fas fa-box" style="color:var(--primary);"></i> My Orders</h1>

    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h2>No orders yet</h2>
            <p style="color:var(--text-muted);margin-bottom:2rem;">You haven't placed any orders yet.</p>
            <a href="{{ route('products') }}" class="btn btn-primary" style="padding:.75rem 2rem;">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @else
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <div>
                    <div class="order-number">Order <span>#{{ $order->order_number }}</span></div>
                    <div style="font-size:.8rem;color:var(--text-muted);margin-top:.2rem;">
                        {{ $order->created_at->format('M d, Y — h:i A') }}
                    </div>
                </div>
                <div class="order-meta">
                    <span><i class="fas fa-building"></i>{{ $order->branch->name }}</span>
                    <span>
                        @php
                            $badge = match($order->status) {
                                'pending'    => 'warning',
                                'processing' => 'info',
                                'shipped'    => 'primary',
                                'delivered'  => 'success',
                                'cancelled'  => 'danger',
                                default      => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($order->status) }}</span>
                    </span>
                    @if($order->payment_status === 'paid')
                        <span class="badge badge-success"><i class="fas fa-check" style="margin-right:.3rem;"></i>Paid</span>
                    @else
                        <span class="badge badge-warning">Unpaid</span>
                    @endif
                </div>
            </div>
            <div class="order-card-body">
                <div class="order-items-preview">
                    @foreach($order->items->take(4) as $item)
                    <div class="order-item-thumb">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    @endforeach
                    @if($order->items->count() > 4)
                        <div class="order-more">+{{ $order->items->count() - 4 }}</div>
                    @endif
                    <span style="font-size:.85rem;color:var(--text-muted);align-self:center;margin-left:.5rem;">
                        {{ $order->items->count() }} item(s)
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div class="order-total">${{ number_format($order->total_price, 2) }}</div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <div class="pagination">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
