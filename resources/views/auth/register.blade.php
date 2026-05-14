<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — SmartMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #f97316; --primary-dark: #ea580c; --bg: #0f172a; --card: #1e293b; --border: #334155; --text: #f1f5f9; --muted: #94a3b8; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-wrap { display: flex; width: 100%; max-width: 900px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.5); }
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
        .auth-left::before { content: ''; position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,.08); border-radius: 50%; top: -80px; right: -80px; }
        .auth-left h1 { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: .75rem; position: relative; z-index: 1; }
        .auth-left p  { color: rgba(255,255,255,.85); font-size: .95rem; line-height: 1.6; position: relative; z-index: 1; }
        .auth-right { flex: 1; background: var(--card); padding: 3rem; }
        .auth-logo { font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-bottom: 1.5rem; }
        .auth-logo span { color: var(--text); }
        h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: .3rem; }
        .subtitle { color: var(--muted); font-size: .875rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; margin-bottom: .35rem; font-size: .85rem; font-weight: 500; color: var(--muted); }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .85rem; }
        .form-control {
            width: 100%;
            padding: .65rem 1rem .65rem 2.8rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: .875rem;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .form-control::placeholder { color: var(--muted); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .btn-submit {
            width: 100%;
            padding: .8rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            margin-top: .5rem;
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .auth-footer { text-align: center; margin-top: 1.2rem; font-size: .875rem; color: var(--muted); }
        .auth-footer a { color: var(--primary); font-weight: 600; }
        .alert { padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .875rem; background: rgba(239,68,68,.15); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; }
        @media (max-width: 640px) { .auth-left { display: none; } .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-left">
        <h1>Join SmartMarket</h1>
        <p>Create your account and start shopping from multiple branches with real-time inventory tracking.</p>
    </div>
    <div class="auth-right">
        <div class="auth-logo"><i class="fas fa-store"></i> Smart<span>Market</span></div>
        <h2>Create Account</h2>
        <p class="subtitle">Fill in your details to get started</p>

        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Phone (optional)</label>
                <div class="input-wrap">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" class="form-control" placeholder="+20 1xx xxx xxxx" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-user-plus"></i> Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>
</div>
</body>
</html>
