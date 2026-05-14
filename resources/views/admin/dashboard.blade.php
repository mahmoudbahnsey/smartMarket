@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .chart-bar-wrap { display: flex; align-items: flex-end; gap: 6px; height: 120px; padding: 0 .5rem; }
    .chart-bar { flex: 1; background: rgba(249,115,22,.3); border-radius: 4px 4px 0 0; transition: background .2s; position: relative; min-width: 0; }
    .chart-bar:hover { background: var(--primary); }
    .chart-bar .tooltip { position: absolute; bottom: calc(100% + 4px); left: 50%; transform: translateX(-50%); background: var(--bg-card); border: 1px solid var(--border); padding: .2rem .5rem; border-radius: 4px; font-size: .7rem; white-space: nowrap; display: none; }
    .chart-bar:hover .tooltip { display: block; }
    .chart-labels { display: flex; gap: 6px; padding: 0 .5rem; margin-top: .3rem; }
    .chart-labels span { flex: 1; text-align: center; font-size: .65rem; color: var(--text-muted); }
    .recent-table tbody tr:hover { background: rgba(255,255,255,.03); }
    .product-thumb { width: 36px; height: 36px; border-radius: 8px; object-fit: cover; background: var(--bg-card2); }
    .low-stock-item { display: flex; align-items: center; justify-content: space-between; padding: .6rem 0; border-bottom: 1px solid rgba(51,65,85,.4); font-size: .875rem; }
    .low-stock-item:last-child { border-bottom: none; }
    .progress-bar-wrap { background: rgba(51,65,85,.5); border-radius: 4px; height: 6px; flex: 1; margin: 0 .75rem; }
    .progress-bar { height: 100%; border-radius: 4px; background: var(--primary); }
    .progress-bar.danger { background: var(--danger); }
    .progress-bar.warning { background: var(--warning); }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}! Here's what's happening.</p>
    </div>
    <div style="font-size:.85rem;color:var(--text-muted);">
        <i class="fas fa-calendar"></i> {{ now()->format('l, F d Y') }}
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> {{ $stats['pending_orders'] }} pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Products</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-label">Customers</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-building"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_branches'] }}</div>
            <div class="stat-label">Branches</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:1.5rem;">
    {{-- Revenue Chart --}}
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:.5rem;"></i>Monthly Revenue ({{ date('Y') }})</span>
        </div>
        <div class="card-body">
            @php
                $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $maxRev = max(array_values($monthlyRevenue) ?: [1]);
            @endphp
            <div class="chart-bar-wrap">
                @for($m = 1; $m <= 12; $m++)
                    @php $rev = $monthlyRevenue[$m] ?? 0; $h = $maxRev > 0 ? round(($rev / $maxRev) * 100) : 0; @endphp
                    <div class="chart-bar" style="height:{{ max($h, 2) }}%;">
                        <div class="tooltip">${{ number_format($rev, 0) }}</div>
                    </div>
                @endfor
            </div>
            <div class="chart-labels">
                @foreach($months as $m)<span>{{ $m }}</span>@endforeach
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-fire" style="color:var(--primary);margin-right:.5rem;"></i>Top Products</span>
            <a href="{{ route('admin.reports.index') }}" style="font-size:.8rem;color:var(--primary);">View Report</a>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem;">
            @forelse($topProducts as $i => $p)
            <div class="low-stock-item">
                <span style="color:var(--text-muted);font-size:.8rem;width:20px;">{{ $i+1 }}</span>
                <span style="flex:1;font-weight:600;font-size:.875rem;">{{ $p->name }}</span>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $topProducts->max('total_sold') > 0 ? round(($p->total_sold / $topProducts->max('total_sold')) * 100) : 0 }}%;"></div>
                </div>
                <span style="font-size:.8rem;color:var(--text-muted);min-width:50px;text-align:right;">{{ $p->total_sold }} sold</span>
            </div>
            @empty
            <p style="color:var(--text-muted);text-align:center;padding:1rem;">No sales data yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid-2">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-shopping-bag" style="color:var(--primary);margin-right:.5rem;"></i>Recent Orders</span>
            <a href="{{ route('admin.orders.index') }}" style="font-size:.8rem;color:var(--primary);">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" style="color:var(--primary);font-weight:600;font-size:.8rem;">
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
            <a href="{{ route('admin.inventory.index', ['low_stock' => 1]) }}" style="font-size:.8rem;color:var(--primary);">View All</a>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem;">
            @forelse($lowStockItems->take(6) as $inv)
            <div class="low-stock-item">
                <div style="flex:1;">
                    <div style="font-weight:600;font-size:.875rem;">{{ $inv->product->name }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">{{ $inv->branch->name }}</div>
                </div>
                <span class="badge badge-{{ $inv->quantity == 0 ? 'danger' : 'warning' }}">
                    {{ $inv->quantity }} left
                </span>
            </div>
            @empty
            <p style="color:var(--success);text-align:center;padding:1rem;"><i class="fas fa-check-circle"></i> All stock levels are healthy!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
