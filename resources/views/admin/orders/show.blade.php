@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb"><a href="{{ route('admin.orders.index') }}">Orders</a><span>/</span><span>{{ $order->order_number }}</span></div>
        <h1>Order <span style="color:var(--primary);">#{{ $order->order_number }}</span></h1>
        <p style="color:var(--text-muted);font-size:.875rem;">{{ $order->created_at->format('F d, Y — h:i A') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="grid-2" style="align-items:start;">
    <div>
        {{-- Items --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header"><i class="fas fa-box" style="color:var(--primary);margin-right:.5rem;"></i>Order Items ({{ $order->items->count() }})</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;">
                                    @endif
                                    <span style="font-weight:600;font-size:.875rem;">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="font-weight:700;color:var(--primary);">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.3rem 0;color:var(--text-muted);">
                    <span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.3rem 0;color:var(--text-muted);">
                    <span>Tax (14%)</span><span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:1.1rem;font-weight:800;padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.5rem;">
                    <span>Total</span><span style="color:var(--primary);">${{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-user" style="color:var(--primary);margin-right:.5rem;"></i>Customer & Shipping</div>
            <div class="card-body">
                <div class="form-row">
                    <div>
                        <div class="form-label">Customer</div>
                        <div style="font-weight:600;">{{ $order->user->name }}</div>
                        <div style="font-size:.8rem;color:var(--text-muted);">{{ $order->user->email }}</div>
                    </div>
                    <div>
                        <div class="form-label">Branch</div>
                        <div style="font-weight:600;">{{ $order->branch->name }}</div>
                        <div style="font-size:.8rem;color:var(--text-muted);">{{ $order->branch->location }}</div>
                    </div>
                    <div>
                        <div class="form-label">Shipping City</div>
                        <div style="font-weight:600;">{{ $order->shipping_city }}</div>
                    </div>
                    <div>
                        <div class="form-label">Payment Method</div>
                        <div style="font-weight:600;">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <div class="form-label">Shipping Address</div>
                        <div style="font-weight:600;">{{ $order->shipping_address }}</div>
                    </div>
                    @if($order->notes)
                    <div style="grid-column:1/-1;">
                        <div class="form-label">Notes</div>
                        <div style="color:var(--text-muted);">{{ $order->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Update Status --}}
    <div>
        <div class="card">
            <div class="card-header"><i class="fas fa-edit" style="color:var(--primary);margin-right:.5rem;"></i>Update Status</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-control">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control">
                            @foreach(['unpaid','paid','refunded'] as $s)
                                <option value="{{ $s }}" {{ $order->payment_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
