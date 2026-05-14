<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SmartMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #f97316; --primary-dark: #ea580c; --bg: #0f172a; --card: #1e293b; --border: #334155; --text: #f1f5f9; --muted: #94a3b8; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-wrap { display: flex; width: 100%; max-width: 900px; min-height: 560px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.5); }
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
            top: -80px; right: -80px;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            bottom: -50px; left: -50px;
        }
        .auth-left h1 { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: .75rem; position: relative; z-index: 1; }
        .auth-left p  { color: rgba(255,255,255,.85); font-size: 1rem; line-height: 1.6; position: relative; z-index: 1; }
        .auth-left .features { margin-top: 2rem; position: relative; z-index: 1; }
        .auth-left .feature { display: flex; align-items: center; gap: .75rem; color: rgba(255,255,255,.9); margin-bottom: .75rem; font-size: .9rem; }
        .auth-left .feature i { width: 28px; height: 28px; background: rgba(255,255,255,.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .8rem; }
        .auth-right { flex: 1; background: var(--card); padding: 3rem; display: flex; flex-direction: column; justify-content: center; }
        .auth-logo { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 2rem; }
        .auth-logo span { color: var(--text); }
        h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: .4rem; }
        .subtitle { color: var(--muted); font-size: .9rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; margin-bottom: .4rem; font-size: .875rem; font-weight: 500; color: var(--muted); }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .9rem; }
        .form-control {
            width: 100%;
            padding: .7rem 1rem .7rem 2.8rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: .9rem;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .form-control::placeholder { color: var(--muted); }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .form-check { display: flex; align-items: center; gap: .5rem; font-size: .875rem; color: var(--muted); }
        .form-check input { accent-color: var(--primary); }
        .forgot { font-size: .875rem; color: var(--primary); }
        .btn-submit {
            width: 100%;
            padding: .85rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: .875rem; color: var(--muted); }
        .auth-footer a { color: var(--primary); font-weight: 600; }
        .alert { padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .875rem; background: rgba(239,68,68,.15); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; }
        @media (max-width: 640px) { .auth-left { display: none; } .auth-wrap { max-width: 420px; } }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-left">
        <h1>Welcome Back!</h1>
        <p>Sign in to your SmartMarket account and manage your orders across all branches.</p>
        <div class="features">
            <div class="feature"><i class="fas fa-building"></i> Multi-Branch Management</div>
            <div class="feature"><i class="fas fa-box"></i> Real-time Inventory</div>
            <div class="feature"><i class="fas fa-chart-line"></i> Sales Analytics</div>
            <div class="feature"><i class="fas fa-shield-alt"></i> Secure & Reliable</div>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-logo"><i class="fas fa-store"></i> Smart<span>Market</span></div>
        <h2>Sign In</h2>
        <p class="subtitle">Enter your credentials to continue</p>

        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="form-row">
                <label class="form-check">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</div>
</body>
</html>
