<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SmartMarket Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card2: #263248;
            --sidebar-w: 260px;
            --border: #334155;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text); display: flex; min-height: 100vh; }
        a { color: inherit; text-decoration: none; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-brand .logo {
            width: 40px; height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff;
        }
        .sidebar-brand .brand-text { font-weight: 800; font-size: 1.1rem; }
        .sidebar-brand .brand-text span { color: var(--primary); }
        .sidebar-brand small { color: var(--text-muted); font-size: .7rem; display: block; }

        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-section {
            padding: .5rem 1rem .25rem;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--text-muted);
            margin-top: .5rem;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem 1.5rem;
            color: var(--text-muted);
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
            margin: .1rem 0;
        }
        .nav-item:hover { color: var(--text); background: rgba(255,255,255,.04); }
        .nav-item.active {
            color: var(--primary);
            background: rgba(249,115,22,.08);
            border-left-color: var(--primary);
        }
        .nav-item i { width: 18px; text-align: center; font-size: .9rem; }
        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            border-radius: 20px;
            padding: .1rem .5rem;
            font-size: .7rem;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .user-info { flex: 1; min-width: 0; }
        .sidebar-user .user-name { font-weight: 600; font-size: .875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user .user-role { font-size: .75rem; color: var(--primary); }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ── */
        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-title { font-weight: 700; font-size: 1.1rem; }
        .topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .topbar-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }
        .topbar-icon:hover { border-color: var(--primary); color: var(--primary); }
        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }

        /* ── Page Content ── */
        .page-content { padding: 2rem; flex: 1; }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; }
        .page-header p { color: var(--text-muted); font-size: .875rem; margin-top: .2rem; }
        .breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .8rem; color: var(--text-muted); margin-bottom: .5rem; }
        .breadcrumb a:hover { color: var(--primary); }

        /* ── Stat Cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--primary); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-icon.orange { background: rgba(249,115,22,.15); color: var(--primary); }
        .stat-icon.blue   { background: rgba(59,130,246,.15);  color: #60a5fa; }
        .stat-icon.green  { background: rgba(34,197,94,.15);   color: #4ade80; }
        .stat-icon.purple { background: rgba(168,85,247,.15);  color: #c084fc; }
        .stat-icon.red    { background: rgba(239,68,68,.15);   color: #f87171; }
        .stat-icon.yellow { background: rgba(245,158,11,.15);  color: #fbbf24; }
        .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: .8rem; color: var(--text-muted); margin-top: .3rem; }
        .stat-change { font-size: .75rem; margin-top: .4rem; }
        .stat-change.up   { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        /* ── Cards ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body { padding: 1.5rem; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            border: none;
            transition: all .2s;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-info    { background: var(--info); color: #fff; }
        .btn-sm { padding: .35rem .75rem; font-size: .8rem; }
        .btn-xs { padding: .25rem .6rem; font-size: .75rem; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; margin-bottom: .4rem; font-size: .875rem; font-weight: 500; color: var(--text-muted); }
        .form-control {
            width: 100%;
            padding: .65rem 1rem;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: .9rem;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--bg-card); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .form-check { display: flex; align-items: center; gap: .5rem; }
        .form-check input { width: 16px; height: 16px; accent-color: var(--primary); }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: .85rem 1rem;
            text-align: left;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td {
            padding: .9rem 1rem;
            border-bottom: 1px solid rgba(51,65,85,.5);
            font-size: .875rem;
        }
        tbody tr:hover { background: rgba(255,255,255,.02); }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: .25rem .65rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-success  { background: rgba(34,197,94,.15);  color: #86efac; }
        .badge-danger   { background: rgba(239,68,68,.15);  color: #fca5a5; }
        .badge-warning  { background: rgba(245,158,11,.15); color: #fcd34d; }
        .badge-info     { background: rgba(59,130,246,.15); color: #93c5fd; }
        .badge-primary  { background: rgba(249,115,22,.15); color: #fdba74; }
        .badge-secondary{ background: rgba(148,163,184,.15);color: #cbd5e1; }

        /* ── Alerts ── */
        .alert {
            padding: .85rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .9rem;
        }
        .alert-success { background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.3); color: #86efac; }
        .alert-danger  { background: rgba(239,68,68,.15);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }
        .alert-info    { background: rgba(59,130,246,.15); border: 1px solid rgba(59,130,246,.3); color: #93c5fd; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: .4rem; justify-content: center; margin-top: 1.5rem; }
        .pagination a, .pagination span {
            padding: .4rem .8rem;
            border-radius: 6px;
            font-size: .85rem;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }
        .pagination a:hover { border-color: var(--primary); color: var(--primary); }
        .pagination .active span { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.7);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 90%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
        }
        .modal-close { cursor: pointer; color: var(--text-muted); font-size: 1.2rem; }
        .modal-close:hover { color: var(--danger); }
        .modal-body { padding: 1.5rem; }
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
        }

        /* ── Grid ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }

        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .form-row, .form-row-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo"><i class="fas fa-store"></i></div>
        <div>
            <div class="brand-text">Smart<span>Market</span></div>
            <small>Admin Panel</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="nav-section">Catalog</div>
        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i> Inventory
            @php $lowStock = \App\Models\Inventory::whereColumn('quantity','<=','low_stock_alert')->count(); @endphp
            @if($lowStock > 0)
                <span class="nav-badge">{{ $lowStock }}</span>
            @endif
        </a>

        <div class="nav-section">Operations</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Orders
            @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.branches.index') }}" class="nav-item {{ request()->routeIs('admin.branches*') ? 'active' : '' }}">
            <i class="fas fa-building"></i> Branches
        </a>

        <div class="nav-section">Management</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Reports
        </a>

        <div class="nav-section">Store</div>
        <a href="{{ route('home') }}" class="nav-item" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Store
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1rem;padding:.3rem;" title="Logout" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--text-muted)'">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">
            <div class="topbar-icon">
                <i class="fas fa-bell"></i>
                @if(isset($lowStock) && $lowStock > 0)
                    <span class="notif-dot"></span>
                @endif
            </div>
            <a href="{{ route('home') }}" class="topbar-icon" title="View Store">
                <i class="fas fa-store"></i>
            </a>
        </div>
    </header>

    {{-- Content --}}
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
