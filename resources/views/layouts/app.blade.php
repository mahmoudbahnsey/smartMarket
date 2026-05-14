<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartMarket') — SmartMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fed7aa;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card2: #263248;
            --border: #334155;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text); min-height: 100vh; }
        a { color: inherit; text-decoration: none; }

        /* ── Navbar ── */
        .navbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .navbar-brand span { color: var(--text); }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; }
        .nav-links a {
            color: var(--text-muted);
            font-weight: 500;
            font-size: .9rem;
            transition: color .2s;
            padding: .3rem 0;
            border-bottom: 2px solid transparent;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        .nav-actions { display: flex; align-items: center; gap: 1rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.2rem;
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
        .btn-sm { padding: .35rem .8rem; font-size: .8rem; }

        .cart-badge {
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: .65rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-left: -8px;
            margin-top: -12px;
            vertical-align: top;
        }

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

        /* ── Container ── */
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        .section { padding: 3rem 0; }

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

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: .85rem 1rem;
            text-align: left;
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: .9rem 1rem;
            border-bottom: 1px solid rgba(51,65,85,.5);
            font-size: .9rem;
        }
        tbody tr:hover { background: rgba(255,255,255,.02); }

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

        /* ── Footer ── */
        footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: .85rem;
            margin-top: 4rem;
        }
        footer span { color: var(--primary); }

        /* ── Pagination ── */
        .pagination { display: flex; gap: .4rem; justify-content: center; margin-top: 2rem; }
        .pagination a, .pagination span {
            padding: .4rem .8rem;
            border-radius: 6px;
            font-size: .85rem;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }
        .pagination a:hover { border-color: var(--primary); color: var(--primary); }
        .pagination .active span { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ── Dropdown ── */
        .dropdown { position: relative; }
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            min-width: 180px;
            padding: .5rem;
            display: none;
            z-index: 200;
            box-shadow: 0 10px 40px rgba(0,0,0,.4);
        }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem .8rem;
            border-radius: 6px;
            font-size: .875rem;
            color: var(--text-muted);
        }
        .dropdown-menu a:hover { background: rgba(255,255,255,.05); color: var(--text); }
        .dropdown-menu hr { border: none; border-top: 1px solid var(--border); margin: .4rem 0; }

        /* ── Avatar ── */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .85rem;
            color: #fff;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <i class="fas fa-store"></i>
        Smart<span>Market</span>
    </a>

    <div class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('products') }}" class="{{ request()->routeIs('products*') ? 'active' : '' }}">Products</a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Admin</a>
            @elseif(auth()->user()->isBranchManager())
                <a href="{{ route('manager.dashboard') }}"><i class="fas fa-building"></i> Manager</a>
            @endif
        @endauth
    </div>

    <div class="nav-actions">
        @auth
            <a href="{{ route('cart') }}" style="position:relative; color: var(--text-muted); font-size:1.2rem;">
                <i class="fas fa-shopping-cart"></i>
                @php $cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0; @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <div class="dropdown">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="dropdown-menu">
                    <a href="#"><i class="fas fa-user"></i> {{ auth()->user()->name }}</a>
                    <a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> My Orders</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" style="background:none;border:none;width:100%;cursor:pointer;display:flex;align-items:center;gap:.6rem;padding:.6rem .8rem;border-radius:6px;font-size:.875rem;color:#fca5a5;transition:background .2s;" onmouseover="this.style.background='rgba(239,68,68,.1)'" onmouseout="this.style.background='none'">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
        @endauth
    </div>
</nav>

{{-- Flash Messages --}}
<div class="container" style="margin-top:1rem;">
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="list-style:none;margin:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

{{-- Main Content --}}
@yield('content')

<footer>
    <p>&copy; {{ date('Y') }} <span>SmartMarket</span>. All rights reserved. Built with ❤️ using Laravel.</p>
</footer>

@stack('scripts')
</body>
</html>
