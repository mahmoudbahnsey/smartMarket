@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@push('styles')
<style>
    .order-detail-page { padding: 3rem 0; }
    .order-detail-layout { display: grid; grid-template-columns: 1fr 320px; gap: 2rem; align-items: start; }
    .back-link { display: inline-flex; align-items: center; gap: .5rem; color: var(--text-muted); font-size: .875rem; margin-bottom: 1.5rem; transition: color .2s; }
    .back-link:hover { color: var(--primary); }
    .order-title { font-size: 1.8rem; font-weight: 800; margin-bottom: .5rem; }
    .order-title span { color: var(--primary); }
    .order-date { color: var(--text-muted); font-size: .875rem; margin-bottom: 2rem; }
    .section-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
    .section-card-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 700; display: flex; align-items: center; gap: .6rem; }
    .section-card-header i { color: var(--primary); }
    .section-card-body { padding: 1.5rem; }
    .order-item-row { display: flex; align-items: center; gap: 1rem; padding: .75rem 0; border-bottom: 1px solid rgba(51,65,85,.5); }
    .order-item-row:last-child { border-bottom: none; }
    .item-img { width: 56px; height: 56px; border-radius: 10px; background: var(--bg-card2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .item-img img { width: 100%; height: 100%; object-fit: cover; }
    .item-name { flex: 1; font-weight: 600; font-size: .9rem; }
    .item-qty { color: var(--text-muted); font-size: .85rem; }
    .item-price { font-weight: 700; color: var(--primary); }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .info-item .label { font-size: .75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .3rem; }
    .info-item .value { font-size: .9rem; font-weight: 600; }
    .summary-row { display: flex; justify-content: space-between; padding: .5rem 0; font-size: .9rem; }
    .summary-row .label { color: var(--text-muted); }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: .5rem; padding-top: .75rem; font-size: 1.1rem; font-weight: 800; }
    .summary-row.total .value { color: var(--primary); }
    .status-timeline { padding: 1rem 0; }
    .timeline-step { display: flex; align-items: center; gap: 1rem; padding: .6rem 0; }
    .step-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
    .step-icon.done { background: rgba(34,197,94,.2); color: var(--success); }
    .step-icon.active { background: rgba(249,115,22,.2); color: var(--primary); }
    .step-icon.pending { background: rgba(51,65,85,.5); color: var(--text-muted); }
    .step-label { font-size: .875rem; font-weight: 600; }
    .step-label.done { color: var(--success); }
    .step-label.active { color: var(--primary); }
    .step-label.pending { color: var(--text-muted); }
    .badge { display: inline-flex; align-items: center; padding: .25rem .65rem; border-radius: 20px; font-size: .75rem; font-weight: 600; }
    .badge-warning  { background: rgba(245,158,11,.15); color: #fcd34d; }
    .badge-info     { background: rgba(59,130,246,.15);  color: #93c5fd; }
    .badge-primary  { background: rgba(249,115,22,.15);  color: #fdba74; }
    .badge-success  { background: rgba(34,197,94,.15);   color: #86efac; }
    .badge-danger   { background: rgba(239,68,68,.15);   color: #fca5a5; }
    @media (max-width: 768px) { .order-detail-layout { grid-template-columns: 1fr; } .info-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container order-detail-page">
    <a href="{{ route('orders.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Orders</a>

    <h1 class="order-title">Order <span>#{{ $order->order_number }}</span></h1>
    <p class="order-date"><i class="fas fa-calendar"></i> Placed on {{ $order->created_at->format('F d, Y — h:i A') }}</p>

    <div class="order-detail-layout">
        <div>
            {{-- Items --}}
            <div class="section-card">
                <div class="section-card-header"><i class="fas fa-box"></i> Order Items</div>
                <div class="section-card-body">
                    @foreach($order->items as $item)
                    <div class="order-item-row">
                        <div class="item-img">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="">
                            @else
                                <i class="fas fa-image" style="color:var(--border);"></i>
                            @endif
                        </div>
                        <div class="item-name">
                            {{ $item->product->name }}
                            <div class="item-qty">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</div>
                        </div>
                        <div class="item-price">${{ number_format($item->subtotal, 2) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="section-card">
                <div class="section-card-header"><i class="fas fa-map-marker-alt"></i> Shipping Information</div>
                <div class="section-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Customer</div>
                            <div class="value">{{ $order->user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Branch</div>
                            <div class="value">{{ $order->branch->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">City</div>
                            <div class="value">{{ $order->shipping_city }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Payment Method</div>
                            <div class="value">{{ ucfirst($order->payment_method) }}</div>
                        </div>
                        <div class="info-item" style="grid-column:1/-1;">
                            <div class="label">Address</div>
                            <div class="value">{{ $order->shipping_address }}</div>
                        </div>
                        @if($order->notes)
                        <div class="info-item" style="grid-column:1/-1;">
                            <div class="label">Notes</div>
                            <div class="value">{{ $order->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div>
            {{-- Status --}}
            <div class="section-card" style="margin-bottom:1.5rem;">
                <div class="section-card-header"><i class="fas fa-truck"></i> Order Status</div>
                <div class="section-card-body">
                    @php
                        $steps = ['pending','processing','shipped','delivered'];
                        $currentIdx = array_search($order->status, $steps);
                    @endphp
                    <div class="status-timeline">
                        @foreach($steps as $idx => $step)
                        <div class="timeline-step">
                            <div class="step-icon {{ $idx < $currentIdx ? 'done' : ($idx == $currentIdx ? 'active' : 'pending') }}">
                                <i class="fas fa-{{ $idx < $currentIdx ? 'check' : ($idx == $currentIdx ? 'circle' : 'circle') }}"></i>
                            </div>
                            <span class="step-label {{ $idx < $currentIdx ? 'done' : ($idx == $currentIdx ? 'active' : 'pending') }}">
                                {{ ucfirst($step) }}
                            </span>
                        </div>
                        @endforeach
                        @if($order->status === 'cancelled')
                        <div class="timeline-step">
                            <div class="step-icon" style="background:rgba(239,68,68,.2);color:var(--danger);">
                                <i class="fas fa-times"></i>
                            </div>
                            <span class="step-label" style="color:var(--danger);">Cancelled</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="section-card">
                <div class="section-card-header"><i class="fas fa-receipt"></i> Payment Summary</div>
                <div class="section-card-body">
                    <div class="summary-row"><span class="label">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="summary-row"><span class="label">Tax (14%)</span><span>${{ number_format($order->tax, 2) }}</span></div>
                    <div class="summary-row"><span class="label">Shipping</span><span style="color:var(--success);">Free</span></div>
                    <div class="summary-row total"><span class="label">Total</span><span class="value">${{ number_format($order->total_price, 2) }}</span></div>
                    <div style="margin-top:1rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        @php
                            $badge = match($order->status) { 'pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger',default=>'secondary' };
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($order->status) }}</span>
                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
