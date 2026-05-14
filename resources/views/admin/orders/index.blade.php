@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="page-header">
    <div>
        <h1>Orders</h1>
        <p>Manage all customer orders</p>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.5rem;">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:180px;">
                <label class="form-label">Search Order #</label>
                <input type="text" name="search" class="form-control" placeholder="ORD-..." value="{{ request('search') }}">
            </div>
            <div style="min-width:150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight:700;color:var(--primary);font-size:.85rem;">{{ $order->order_number }}</td>
                    <td style="font-size:.875rem;">{{ $order->user->name }}</td>
                    <td style="font-size:.8rem;color:var(--text-muted);">{{ $order->branch->name }}</td>
                    <td style="font-weight:700;">${{ number_format($order->total_price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'info' : 'warning') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        @php $b = match($order->status){'pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger',default=>'secondary'}; @endphp
                        <span class="badge badge-{{ $b }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="font-size:.8rem;color:var(--text-muted);">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-xs">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted);">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div style="padding:1rem 1.5rem;">
        <div class="pagination">{{ $orders->links() }}</div>
    </div>
    @endif
</div>
@endsection
