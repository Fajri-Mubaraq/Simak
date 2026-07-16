<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SIMAK</title>
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

        .card {
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

        .card-header {
            text-align: center; margin-bottom: 32px;
        }
        .header-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #818cf8, #6366f1);
            border-radius: 22px; display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 28px; color: #fff;
            box-shadow: 0 8px 28px rgba(99,102,241,.45);
            margin-bottom: 16px;
        }
        .card-header h1 { font-size: 24px; font-weight: 800; color: #0f172a; margin:0; }
        .card-header p  { font-size: 14px; color: #64748b; margin: 8px 0 0; line-height: 1.5; }

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
        .error-msg {
            color: #ef4444; font-size: 12px; margin-top: 6px;
            display: flex; align-items: center; gap: 5px;
        }

        .btn-primary {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,.4); }
        .btn-primary:active { transform: translateY(0); }

        .back-link {
            text-align: center; margin-top: 24px;
        }
        .back-link a {
            font-size: 13px; color: #6366f1; text-decoration: none;
            font-weight: 500; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .back-link a:hover {
            color: #4f46e5; text-decoration: underline;
        }

        /* Steps indicator */
        .steps {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; margin-bottom: 28px;
        }
        .step {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; transition: all .3s;
        }
        .step.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,.4);
        }
        .step.inactive {
            background: #e2e8f0; color: #94a3b8;
        }
        .step-line {
            width: 40px; height: 2px; background: #e2e8f0; border-radius: 2px;
        }

        /* Success message */
        .success-msg {
            background: rgba(16,185,129,.08);
            border: 1px solid rgba(16,185,129,.2);
            border-radius: 10px; padding: 12px 14px;
            margin-bottom: 20px; display: flex;
            align-items: center; gap: 10px;
            font-size: 13px; color: #065f46;
        }

        /* Error alert */
        .error-alert {
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 10px; padding: 12px 14px;
            margin-bottom: 20px; display: flex;
            align-items: center; gap: 10px;
            font-size: 13px; color: #991b1b;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <div class="header-icon"><i class="fas fa-key"></i></div>
        <h1>Forgot Password</h1>
        <p>Masukkan email yang terdaftar untuk<br>mereset password Anda</p>
    </div>

    <!-- Steps -->
    <div class="steps">
        <div class="step active">1</div>
        <div class="step-line"></div>
        <div class="step inactive">2</div>
    </div>

    @if($errors->any())
    <div class="error-alert">
        <i class="fas fa-triangle-exclamation" style="color:#ef4444;font-size:16px;"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('password.verify') }}" id="forgotForm">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrap">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email"
                       id="email"
                       name="email"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="contoh@simak.ac.id"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>
            @error('email')
            <div class="error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary" id="btnSubmit">
            <i class="fas fa-arrow-right"></i>
            Lanjutkan
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Login
        </a>
    </div>
</div>

<script>
    document.getElementById('forgotForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;
    });
</script>
</body>
</html>
