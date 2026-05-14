@extends('layouts.admin')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="page-header">
    <div>
        <h1>Sales Reports</h1>
        <p>Analyze your business performance</p>
    </div>
</div>

{{-- Date Filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.5rem;">
        <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label class="form-label">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}">
            </div>
            <div>
                <label class="form-label">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-chart-bar"></i> Generate Report</button>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">This Month</a>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="stats-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value">${{ number_format($revenue, 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($ordersCount) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="stat-value">${{ $ordersCount > 0 ? number_format($revenue / $ordersCount, 2) : '0.00' }}</div>
            <div class="stat-label">Avg. Order Value</div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:1.5rem;">
    {{-- Top Products --}}
    <div class="card">
        <div class="card-header"><i class="fas fa-fire" style="color:var(--primary);margin-right:.5rem;"></i>Top Selling Products</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>#</th><th>Product</th><th>Units Sold</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $i => $p)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                        <td style="font-weight:600;font-size:.875rem;">{{ $p->name }}</td>
                        <td><span class="badge badge-info">{{ $p->total_sold }}</span></td>
                        <td style="font-weight:700;color:var(--primary);">${{ number_format($p->revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted);">No data for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Branch Revenue --}}
    <div class="card">
        <div class="card-header"><i class="fas fa-building" style="color:var(--primary);margin-right:.5rem;"></i>Revenue by Branch</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Branch</th><th>Orders</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    @forelse($branchRevenue as $b)
                    <tr>
                        <td style="font-weight:600;font-size:.875rem;">{{ $b->name }}</td>
                        <td><span class="badge badge-info">{{ $b->orders_count }}</span></td>
                        <td style="font-weight:700;color:var(--primary);">${{ number_format($b->revenue ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--text-muted);">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Daily Revenue Chart --}}
@if($dailyRevenue->count())
<div class="card">
    <div class="card-header"><i class="fas fa-chart-line" style="color:var(--primary);margin-right:.5rem;"></i>Daily Revenue</div>
    <div class="card-body">
        @php $maxDaily = $dailyRevenue->max('revenue') ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:4px;height:140px;overflow-x:auto;padding-bottom:.5rem;">
            @foreach($dailyRevenue as $day)
            @php $h = round(($day->revenue / $maxDaily) * 100); @endphp
            <div style="flex:0 0 auto;width:28px;display:flex;flex-direction:column;align-items:center;gap:4px;">
                <div style="width:100%;height:{{ max($h, 2) }}%;background:rgba(249,115,22,.4);border-radius:4px 4px 0 0;transition:background .2s;cursor:pointer;position:relative;"
                     title="${{ number_format($day->revenue, 2) }}"
                     onmouseover="this.style.background='var(--primary)'"
                     onmouseout="this.style.background='rgba(249,115,22,.4)'">
                </div>
                <span style="font-size:.6rem;color:var(--text-muted);white-space:nowrap;">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
