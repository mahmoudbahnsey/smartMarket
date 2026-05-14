@extends('layouts.admin')
@section('title', 'Manager Dashboard')
@section('page-title', 'Branch Manager Dashboard')

@push('styles')
<style>
.branch-header {
    background: linear-gradient(135deg, rgba(249,115,22,.15), rgba(249,115,22,.05));
    border: 1px solid rgba(249,115,22,.3);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.branch-icon {
    width: 56px; height: 56px;
    background: var(--primary);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <div style="font-size:.85rem;color:var(--text-muted);">
        <i class="fas fa-calendar"></i> {{ now()->format('l, F d Y') }}
    </div>
</div>

{{-- Branch Info --}}
<div class="branch-header">
    <div class="branch-icon"><i class="fas fa-building"></i></div>
    <div>
        <div style="font-size:1.2rem;font-weight:800;">{{ $branch->name }}</div>
        <div style="color:var(--text-muted);font-size:.875rem;margin-top:.2rem;">
            <i class="fas fa-map-marker-alt" style="color:var(--primary);margin-right:.4rem;"></i>{{ $branch->location }}
            @if($branch->phone)
                &nbsp;&nbsp;<i class="fas fa-phone" style="color:var(--primary);margin-right:.4rem;"></i>{{ $branch->phone }}
            @endif
        </div>
    </div>
    <span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}" style="margin-left:auto;">
        {{ $branch->is_active ? 'Active' : 'Inactive' }}
    </span>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-change up"><i class="fas fa-clock"></i> {{ $stats['pending_orders'] }} pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['revenue'], 0) }}</div>
            <div class="stat-label">Branch Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-boxes"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_products'] }}</div>
            <div class="stat-label">Products in Branch</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $stats['low_stock'] > 0 ? 'red' : 'green' }}">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['low_stock'] }}</div>
            <div class="stat-label">Low Stock Alerts</div>
        </div>
    </div>
</div>

<div class="grid-2">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-shopping-bag" style="color:var(--primary);margin-right:.5rem;"></i>Recent Orders</span>
            <a href="{{ route('manager.orders.index') }}" style="font-size:.8rem;color:var(--primary);">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('manager.orders.show', $order) }}" style="color:var(--primary);font-weight:600;font-size:.8rem;">
                                #{{ substr($order->order_number, -8) }}
                            </a>
                        </td>
                        <td style="font-size:.875rem;">{{ $order->user->name }}</td>
                        <td style="font-weight:700;color:var(--primary);">${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            @php $b = match($order->status){'pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger',default=>'secondary'}; @endphp
                            <span class="badge badge-{{ $b }}">{{ ucfirst($order->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-exclamation-triangle" style="color:var(--warning);margin-right:.5rem;"></i>Low Stock Alerts</span>
            <a href="{{ route('manager.inventory.index', ['low_stock'=>1]) }}" style="font-size:.8rem;color:var(--primary);">View All</a>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem;">
            @forelse($lowStockItems as $inv)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid rgba(51,65,85,.4);">
                <div>
                    <div style="font-weight:600;font-size:.875rem;">{{ $inv->product->name }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">Alert level: {{ $inv->low_stock_alert }}</div>
                </div>
                <span class="badge badge-{{ $inv->quantity == 0 ? 'danger' : 'warning' }}">
                    {{ $inv->quantity }} left
                </span>
            </div>
            @empty
            <p style="color:var(--success);text-align:center;padding:1rem;">
                <i class="fas fa-check-circle"></i> All stock levels are healthy!
            </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
