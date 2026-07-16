<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIMAK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 70%, #6366f1 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        /* Animated blobs */
        body::before {
            content:''; position:absolute; top:-20%; right:-10%;
            width:600px; height:600px; border-radius:50%;
            background: radial-gradient(circle, rgba(129,140,248,.3) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            content:''; position:absolute; bottom:-15%; left:-10%;
            width:500px; height:500px; border-radius:50%;
            background: radial-gradient(circle, rgba(99,102,241,.2) 0%, transparent 70%);
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%,100%{transform:translateY(0) scale(1)}
            50%{transform:translateY(-30px) scale(1.05)}
        }

        .login-card {
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 44px;
            width: 100%; max-width: 440px;
            box-shadow: 0 24px 80px rgba(0,0,0,.25);
            position: relative; z-index: 10;
            animation: slideUp .5s ease;
        }
        @keyframes slideUp {
            from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)}
        }

        .login-logo {
            text-align: center; margin-bottom: 32px;
        }
        .logo-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #818cf8, #6366f1);
            border-radius: 22px; display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 32px; color: #fff;
            box-shadow: 0 8px 28px rgba(99,102,241,.45);
            margin-bottom: 16px;
        }
        .login-logo h1 { font-size: 26px; font-weight: 800; color: #0f172a; margin:0; }
        .login-logo p  { font-size: 14px; color: #64748b; margin: 6px 0 0; }

        .form-group { margin-bottom: 20px; }
        label {
            display: block; font-size: 13px; font-weight: 600;
            color: #475569; margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap input {
            width: 100%; border: 2px solid #e2e8f0;
            border-radius: 12px; padding: 12px 16px 12px 44px;
            font-size: 14px; font-family: inherit;
            color: #334155; transition: all .2s; background: #fff;
        }
        .input-wrap input:focus {
            outline: none; border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,.12);
        }
        .input-wrap input.is-invalid { border-color: #ef4444; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #94a3b8; font-size: 14px;
        }
        .toggle-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); color: #94a3b8;
            cursor: pointer; font-size: 14px; background: none; border: none;
        }
        .error-msg {
            color: #ef4444; font-size: 12px; margin-top: 6px;
            display: flex; align-items: center; gap: 5px;
        }

        .forgot-password {
            text-align: right; margin-top: -8px; margin-bottom: 16px;
        }
        .forgot-password a {
            font-size: 13px; color: #6366f1; text-decoration: none;
            font-weight: 500; transition: all .2s;
        }
        .forgot-password a:hover {
            color: #4f46e5; text-decoration: underline;
        }

        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,.4); }
        .btn-login:active { transform: translateY(0); }

        .demo-info {
            margin-top: 24px; background: rgba(99,102,241,.07);
            border-radius: 12px; padding: 14px 16px;
            border: 1px solid rgba(99,102,241,.15);
        }
        .demo-info p { font-size: 12px; color: #475569; margin: 0 0 8px; font-weight: 600; }
        .demo-info code {
            font-size: 12px; background: rgba(99, 102, 241, .1);
            color: #4f46e5; padding: 2px 7px; border-radius: 6px;
        }

        /* Welcome Modal Styling */
        .welcome-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 24px;
            animation: fadeIn 0.3s ease;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 28px;
            padding: 36px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: scaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .welcome-card h2 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .welcome-desc {
            font-size: 13.5px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 24px;
            text-align: justify;
        }
        .dev-profile {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-align: left;
            margin-bottom: 26px;
        }
        .dev-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #818cf8, #6366f1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            flex-shrink: 0;
        }
        .dev-info h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .dev-info p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #6366f1;
            font-weight: 600;
        }
        .dev-tag {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .btn-welcome {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-welcome:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.45);
        }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
        <h1>SIMAK</h1>
        <p>Sistem Informasi Manajemen Akademik</p>
    </div>

    @if($errors->any())
    <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 14px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-triangle-exclamation" style="color:#ef4444;font-size:16px;"></i>
        <span style="font-size:13px;color:#7f1d1d;">{{ $errors->first() }}</span>
    </div>
    @endif

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:12px 14px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-circle-check" style="color:#10b981;font-size:16px;"></i>
        <span style="font-size:13px;color:#065f46;">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" id="loginForm">
        @csrf

        <div class="form-group">
            <label for="identifier">NIM / NIDN</label>
            <div class="input-wrap">
                <i class="fas fa-id-card input-icon"></i>
                <input type="text" id="identifier" name="identifier"
                       value="{{ old('identifier') }}"
                       placeholder="Masukkan NIM atau NIDN"
                       class="{{ $errors->has('identifier') ? 'is-invalid' : '' }}"
                       autocomplete="username" required>
            </div>
            @error('identifier')
            <div class="error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password"
                       placeholder="Masukkan password"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                       autocomplete="current-password" required>
                <button type="button" class="toggle-pass" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
            <div class="error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="forgot-password">
            <a href="{{ route('password.forgot') }}">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login" id="btnLogin">
            <i class="fas fa-right-to-bracket"></i>
            Masuk ke Sistem
        </button>
    </form>

    <div class="demo-info">
        <p>🔑 Akun Demo (Seeder)</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
            <div><strong style="font-size:11px;color:#64748b;">Mahasiswa:</strong> NIM <code>2101000001</code> / Pass <code>mhs123</code></div>
            <div><strong style="font-size:11px;color:#64748b;">Dosen:</strong> NIDN <code>2401000002</code> / Pass <code>dosen123</code></div>
            <div><strong style="font-size:11px;color:#64748b;">Admin:</strong> NIDN <code>2401000001</code> / Pass <code>admin123</code></div>
        </div>
    </div>
</div>

<!-- Welcome Overlay Modal -->
<div id="welcomeOverlay" class="welcome-overlay" style="display: none;">
    <div class="welcome-card">
        <div class="welcome-badge">
            <i class="fas fa-bullhorn"></i> Sambutan Hangat
        </div>
        <h2>Selamat Datang di SIMAK</h2>
        <p class="welcome-desc">
            <strong>SIMAK</strong> (Sistem Informasi Manajemen Akademik) adalah platform manajemen perkuliahan modern yang dirancang untuk mempermudah administrasi akademik secara efisien dan terintegrasi. Sistem ini mendukung pengelolaan kartu hasil studi (KHS), absensi perkuliahan presisi, mata kuliah dinamis, dan pengelolaan profil mandiri bagi Mahasiswa, Dosen, serta Administrator.
        </p>

        <div class="dev-profile">
            <div class="dev-avatar">FM</div>
            <div class="dev-info">
                <h5>M. Fajri Mubaraq</h5>
                <p>Creator & Web Developer</p>
                <div class="dev-tag">SIMAK Academic System Project</div>
            </div>
        </div>

        <button type="button" class="btn-welcome" id="btnCloseWelcome">
            Mulai Eksplorasi Sistem <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeOverlay = document.getElementById('welcomeOverlay');
        const btnCloseWelcome = document.getElementById('btnCloseWelcome');

        // Show welcome notification only once per browser session
        if (!sessionStorage.getItem('welcome_seen')) {
            welcomeOverlay.style.display = 'flex';
        }

        btnCloseWelcome.addEventListener('click', function() {
            welcomeOverlay.style.display = 'none';
            sessionStorage.setItem('welcome_seen', 'true');
        });
    });

    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnLogin');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;
    });
</script>
</body>
</html>
