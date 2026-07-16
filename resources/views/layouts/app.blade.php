<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMAK - Sistem Informasi Manajemen Akademik">
    <title>@yield('title', 'Dashboard') | SIMAK</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary:       #6366f1;
            --primary-dark:  #4f46e5;
            --primary-light: #818cf8;
            --secondary:     #0ea5e9;
            --success:       #10b981;
            --danger:        #ef4444;
            --warning:       #f59e0b;
            --info:          #06b6d4;
            --dark:          #0f172a;
            --sidebar-w:     260px;
            --topbar-h:      64px;
            --shadow:        0 4px 24px rgba(99,102,241,.10);
            --shadow-hover:  0 8px 32px rgba(99,102,241,.18);
            --radius:        14px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #334155;
            margin: 0;
            min-height: 100vh;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed; top:0; left:0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 55%, #4338ca 100%);
            z-index: 1050;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 24px rgba(99,102,241,.2);
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 28px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 14px;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background: linear-gradient(135deg,#818cf8,#6366f1);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff;
            box-shadow: 0 4px 16px rgba(99,102,241,.45);
            flex-shrink: 0;
        }
        .brand-text h5 {
            margin:0; font-size:16px; font-weight:800; color:#fff; letter-spacing:.02em;
        }
        .brand-text p {
            margin: 2px 0 0; font-size:11px; color:rgba(255,255,255,.5);
        }

        .sidebar-nav {
            flex:1; padding: 16px 10px; overflow-y:auto;
        }
        .nav-label {
            color: rgba(255,255,255,.35);
            font-size: 10px; font-weight:700; letter-spacing:.12em;
            text-transform: uppercase;
            padding: 14px 12px 6px;
        }
        .nav-link {
            color: rgba(255,255,255,.7);
            padding: 10px 12px;
            border-radius: 10px; font-size:14px; font-weight:500;
            display:flex; align-items:center; gap:12px;
            transition: all .2s; margin-bottom:2px;
            text-decoration:none;
        }
        .nav-link:hover { background:rgba(255,255,255,.1); color:#fff; transform:translateX(3px); }
        .nav-link.active {
            background: linear-gradient(135deg,rgba(129,140,248,.25),rgba(99,102,241,.15));
            color:#fff; border:1px solid rgba(129,140,248,.25);
        }
        .nav-icon {
            width:32px; height:32px;
            background:rgba(255,255,255,.08); border-radius:8px;
            display:flex; align-items:center; justify-content:center;
            font-size:13px; flex-shrink:0;
        }
        .nav-link.active .nav-icon {
            background:linear-gradient(135deg,#818cf8,#6366f1);
            box-shadow:0 3px 10px rgba(99,102,241,.45); color:#fff;
        }

        .sidebar-footer {
            padding:16px; border-top:1px solid rgba(255,255,255,.08);
        }
        .footer-user-link {
            transition: all 0.2s ease;
        }
        .footer-user-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        .footer-user-link:hover .user-name {
            color: var(--primary-light) !important;
        }
        .footer-user-link:hover .avatar {
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.5);
        }
        .avatar {
            width:36px; height:36px;
            background:linear-gradient(135deg,#818cf8,#6366f1);
            border-radius:50%; display:flex; align-items:center;
            justify-content:center; color:#fff; font-size:14px; font-weight:700; flex-shrink:0;
        }
        .user-name { margin:0; font-size:13px; font-weight:600; color:#fff; }
        .user-role { margin:2px 0 0; font-size:11px; color:rgba(255,255,255,.5); }
        .btn-logout {
            width:100%; padding:8px; border-radius:8px; border:none;
            background:rgba(239,68,68,.15); color:#fca5a5;
            font-size:13px; font-weight:600; cursor:pointer;
            transition:all .2s; display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-logout:hover { background:rgba(239,68,68,.25); color:#fff; }

        /* ========== MAIN ========== */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ========== TOPBAR ========== */
        .topbar {
            background:#fff; height:var(--topbar-h);
            padding: 0 28px;
            display:flex; align-items:center; justify-content:space-between;
            border-bottom:1px solid #e2e8f0;
            position:sticky; top:0; z-index:100;
            box-shadow:0 1px 8px rgba(0,0,0,.05);
        }
        .topbar-left { display:flex; align-items:center; gap:12px; }
        .sidebar-toggle {
            display:none; background:none; border:none;
            font-size:20px; color:#64748b; cursor:pointer; padding:4px;
        }
        .page-title-bar { font-size:18px; font-weight:700; color:#0f172a; }
        .page-title-bar span { color:var(--primary); }
        .topbar-right { display:flex; align-items:center; gap:12px; }
        .status-badge {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            color:#fff; padding:6px 14px; border-radius:20px;
            font-size:12px; font-weight:600; display:flex; align-items:center; gap:6px;
        }
        .status-dot { width:7px; height:7px; border-radius:50%; background:#86efac; }

        /* ========== PAGE CONTENT ========== */
        .page-content { padding:24px 28px; flex:1; }

        /* ========== BREADCRUMB ========== */
        .breadcrumb-custom {
            display:flex; align-items:center; gap:8px;
            font-size:13px; color:#64748b; margin-bottom:18px;
        }
        .breadcrumb-custom a { color:var(--primary); text-decoration:none; font-weight:500; }
        .breadcrumb-custom a:hover { text-decoration:underline; }
        .breadcrumb-custom .sep { color:#cbd5e1; }
        .breadcrumb-custom .current { color:#334155; font-weight:600; }

        /* ========== ALERT ========== */
        .alert-c {
            border-radius:12px; padding:13px 16px;
            display:flex; align-items:center; gap:12px;
            font-size:14px; font-weight:500; border:none;
            animation: slideDown .3s ease; margin-bottom:16px;
        }
        .alert-success-c {
            background:linear-gradient(135deg,rgba(16,185,129,.1),rgba(16,185,129,.05));
            color:#065f46; border-left:4px solid var(--success);
        }
        .alert-danger-c {
            background:linear-gradient(135deg,rgba(239,68,68,.1),rgba(239,68,68,.05));
            color:#7f1d1d; border-left:4px solid var(--danger);
        }
        .alert-close {
            margin-left:auto; background:none; border:none; cursor:pointer;
            opacity:.6; font-size:16px; line-height:1;
        }
        @keyframes slideDown {
            from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)}
        }

        /* ========== FLOATING WELCOME TOAST ========== */
        .toast-welcome {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 320px;
            max-width: 420px;
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.5s;
            opacity: 0;
        }
        .toast-welcome.show {
            transform: translateX(0);
            opacity: 1;
        }
        .toast-welcome-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #html;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
            animation: bounceIn 0.8s ease;
        }
        .toast-welcome-content {
            flex-grow: 1;
        }
        .toast-welcome-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .toast-welcome-msg {
            margin: 2px 0 0;
            font-size: 13px;
            color: #64748b;
            line-height: 1.4;
        }
        .toast-welcome-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 16px;
            padding: 4px;
            transition: color 0.2s;
        }
        .toast-welcome-close:hover {
            color: #475569;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 0.8; }
            70% { transform: scale(0.9); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* ========== CARDS ========== */
        .card-c {
            background:#fff; border-radius:var(--radius);
            box-shadow:var(--shadow); border:none; overflow:hidden;
        }
        .card-header-c {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            padding:18px 22px; color:#fff;
        }
        .card-header-c h5 { margin:0; font-size:16px; font-weight:700; }
        .card-header-c p { margin:4px 0 0; font-size:13px; opacity:.8; }
        .card-body-c { padding:22px; }

        /* ========== STAT CARDS ========== */
        .stat-card {
            background:#fff; border-radius:var(--radius);
            padding:20px; display:flex; align-items:center; gap:16px;
            box-shadow:var(--shadow); transition:all .25s;
            min-width:0; overflow:hidden;
        }
        .stat-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-hover); }
        .stat-icon {
            width:52px; height:52px; border-radius:14px;
            display:flex; align-items:center; justify-content:center;
            font-size:22px; flex-shrink:0;
        }
        .si-purple { background:linear-gradient(135deg,#818cf8,#6366f1); color:#fff; }
        .si-blue   { background:linear-gradient(135deg,#38bdf8,#0ea5e9); color:#fff; }
        .si-green  { background:linear-gradient(135deg,#34d399,#10b981); color:#fff; }
        .si-orange { background:linear-gradient(135deg,#fbbf24,#f59e0b); color:#fff; }
        .si-pink   { background:linear-gradient(135deg,#f472b6,#ec4899); color:#fff; }
        .stat-num  { font-size:28px; font-weight:800; margin:0; color:#0f172a; line-height:1.1; }
        .stat-lbl  { font-size:12px; color:#64748b; margin:0; font-weight:500; }

        /* ========== TABLE ========== */
        .tbl-wrapper { background:#fff; border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
        .tbl-header {
            padding:18px 22px; display:flex; align-items:center;
            justify-content:space-between; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; gap:12px;
        }
        .tbl-header h5 { font-size:16px; font-weight:700; color:#0f172a; margin:0; }
        .table { margin:0; }
        .table thead th {
            background:#f8fafc; font-size:11px; font-weight:700;
            text-transform:uppercase; letter-spacing:.06em; color:#64748b;
            border:none; padding:13px 16px;
        }
        .table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
        .table tbody tr:hover { background:#f8fafc; }
        .table tbody td { padding:13px 16px; vertical-align:middle; font-size:14px; border:none; }
        .table tbody tr:last-child { border-bottom:none; }

        /* ========== BADGES ========== */
        .badge-c {
            padding:4px 11px; border-radius:20px;
            font-size:12px; font-weight:600; display:inline-block;
        }
        .badge-aktif    { background:rgba(16,185,129,.12); color:#065f46; }
        .badge-cuti     { background:rgba(245,158,11,.12);  color:#92400e; }
        .badge-lulus    { background:rgba(99,102,241,.12);  color:#3730a3; }
        .badge-do       { background:rgba(239,68,68,.12);   color:#7f1d1d; }
        .badge-jurusan  { background:rgba(99,102,241,.1);   color:var(--primary); }
        .badge-sks      { background:rgba(14,165,233,.1);   color:var(--secondary); }
        .badge-mk-aktif { background:rgba(16,185,129,.1);   color:#065f46; }
        .badge-mk-off   { background:rgba(100,116,139,.1);  color:#475569; }
        .badge-nilai-a  { background:rgba(16,185,129,.15);  color:#065f46; font-weight:700; }
        .badge-nilai-b  { background:rgba(99,102,241,.15);  color:#3730a3; font-weight:700; }
        .badge-nilai-c  { background:rgba(245,158,11,.15);  color:#92400e; font-weight:700; }
        .badge-nilai-d  { background:rgba(239,68,68,.15);   color:#7f1d1d; font-weight:700; }
        .badge-nilai-e  { background:rgba(100,116,139,.15); color:#475569; font-weight:700; }
        .badge-lulus-n  { background:rgba(16,185,129,.12);  color:#065f46; }
        .badge-gagal    { background:rgba(239,68,68,.12);   color:#7f1d1d; }
        .badge-ulang    { background:rgba(245,158,11,.12);  color:#92400e; }

        /* ========== BUTTONS ========== */
        .btn-primary-c {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            border:none; color:#fff; padding:9px 18px; border-radius:10px;
            font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:8px;
            transition:all .2s; text-decoration:none; cursor:pointer;
        }
        .btn-primary-c:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(99,102,241,.35); color:#fff; }
        .btn-secondary-c {
            background:#f1f5f9; border:none; color:#475569;
            padding:9px 18px; border-radius:10px; font-size:14px; font-weight:600;
            display:inline-flex; align-items:center; gap:8px;
            transition:all .2s; text-decoration:none; cursor:pointer;
        }
        .btn-secondary-c:hover { background:#e2e8f0; color:#334155; }
        .btn-action {
            width:32px; height:32px; border-radius:8px;
            display:inline-flex; align-items:center; justify-content:center;
            font-size:13px; border:none; cursor:pointer;
            transition:all .15s; text-decoration:none;
        }
        .btn-view   { background:rgba(14,165,233,.1);  color:var(--secondary); }
        .btn-edit   { background:rgba(99,102,241,.1);  color:var(--primary); }
        .btn-delete { background:rgba(239,68,68,.1);   color:var(--danger); }
        .btn-view:hover   { background:var(--secondary); color:#fff; }
        .btn-edit:hover   { background:var(--primary);   color:#fff; }
        .btn-delete:hover { background:var(--danger);    color:#fff; }

        /* ========== SEARCH / FILTER ========== */
        .filter-bar {
            display:flex; gap:10px; align-items:center; flex-wrap:wrap;
        }
        .search-wrap { position:relative; }
        .search-wrap input {
            border:2px solid #e2e8f0; border-radius:10px;
            padding:9px 36px 9px 14px; font-size:14px;
            transition:border-color .2s; width:240px; font-family:inherit;
        }
        .search-wrap input:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 4px rgba(99,102,241,.1); }
        .search-icon { position:absolute; right:11px; top:50%; transform:translateY(-50%); color:#94a3b8; }
        .filter-select {
            border:2px solid #e2e8f0; border-radius:10px;
            padding:9px 12px; font-size:14px; color:#475569;
            background:#fff; cursor:pointer; font-family:inherit;
        }
        .filter-select:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 4px rgba(99,102,241,.1); }

        /* ========== FORM ========== */
        .form-label-c { font-size:13px; font-weight:600; color:#475569; margin-bottom:6px; display:block; }
        .form-control-c, .form-select-c {
            border:2px solid #e2e8f0; border-radius:10px;
            padding:10px 14px; font-size:14px; transition:all .2s;
            width:100%; font-family:inherit; color:#334155;
            background:#fff;
        }
        .form-control-c:focus, .form-select-c:focus {
            outline:none; border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(99,102,241,.1);
        }
        .form-control-c.is-invalid, .form-select-c.is-invalid { border-color:var(--danger); }
        .invalid-msg { color:var(--danger); font-size:12px; margin-top:4px; }

        /* ========== AVATAR ========== */
        .mhs-avatar {
            width:36px; height:36px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:13px; font-weight:700; color:#fff; flex-shrink:0;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state { padding:60px 20px; text-align:center; }
        .empty-icon {
            width:80px; height:80px; border-radius:50%;
            background:rgba(99,102,241,.08);
            display:flex; align-items:center; justify-content:center;
            font-size:32px; color:var(--primary); margin:0 auto 18px;
        }

        /* ========== DETAIL ========== */
        .detail-item {
            padding:13px 0; border-bottom:1px solid #f1f5f9;
            display:flex; gap:12px; align-items:flex-start;
        }
        .detail-item:last-child { border-bottom:none; }
        .detail-icon {
            width:36px; height:36px; border-radius:10px;
            background:rgba(99,102,241,.08);
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); flex-shrink:0; font-size:14px;
        }
        .detail-lbl { font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
        .detail-val { font-size:15px; font-weight:600; color:#0f172a; }

        /* ========== PAGINATION ========== */
        .pg-btn {
            display:inline-flex; align-items:center; justify-content:center;
            min-width:36px; height:36px; padding:0 10px;
            border:2px solid #e2e8f0; border-radius:10px;
            font-size:13px; font-weight:600; color:#475569;
            background:#fff; text-decoration:none;
            transition:all .2s; cursor:pointer;
        }
        .pg-btn:hover { border-color:var(--primary); color:var(--primary); background:rgba(99,102,241,.05); }
        .pg-btn.pg-active {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            border-color:var(--primary); color:#fff;
        }
        .pg-btn.pg-disabled {
            opacity:.4; cursor:not-allowed; pointer-events:none;
        }
        .pg-btn.pg-dots { border:none; background:none; cursor:default; }
        .pg-btn.pg-dots:hover { background:none; color:#475569; }
        .pg-btn i { font-size:12px; }

        /* ========== ABSENSI BADGES ========== */
        .badge-hadir { background:rgba(16,185,129,.12); color:#065f46; }
        .badge-izin  { background:rgba(14,165,233,.12); color:#0c4a6e; }
        .badge-sakit { background:rgba(245,158,11,.12); color:#92400e; }
        .badge-alpha { background:rgba(239,68,68,.12);  color:#7f1d1d; }

        /* ========== RESPONSIVE ========== */

        /* Large tablets / small desktops with sidebar */
        @media (max-width:1200px) {
            .stat-num  { font-size:22px; }
            .stat-icon { width:44px; height:44px; font-size:18px; border-radius:12px; }
            .stat-card { padding:16px; gap:12px; }
        }

        /* Tablets */
        @media (max-width:992px) {
            .stat-num  { font-size:20px; }
            .stat-lbl  { font-size:11px; }
            .stat-icon { width:40px; height:40px; font-size:16px; border-radius:10px; }
            .stat-card { padding:14px; gap:10px; }
            .page-content { padding:20px; }
        }

        /* Mobile */
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .main-content { margin-left:0; }
            .page-content { padding:16px; }
            .topbar { padding:0 16px; }
            .sidebar-toggle { display:block; }
            .search-wrap input { width:180px; }

            .stat-card {
                padding:14px; gap:10px;
                flex-direction:row; align-items:center;
            }
            .stat-num  { font-size:20px; }
            .stat-lbl  { font-size:11px; white-space:nowrap; }
            .stat-icon { width:42px; height:42px; font-size:17px; border-radius:11px; }
        }

        /* Small phones */
        @media (max-width:480px) {
            .stat-card { padding:12px; gap:8px; }
            .stat-num  { font-size:18px; }
            .stat-lbl  { font-size:10px; }
            .stat-icon { width:36px; height:36px; font-size:15px; border-radius:10px; }
            .page-content { padding:12px; }
        }
        .sidebar.open { transform:translateX(0) !important; }
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="brand-text">
            <h5>SIMAK</h5>
            <p>Sistem Informasi Akademik</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
            Dashboard
        </a>

        <div class="nav-label mt-1">Akademik</div>

        @if(session('user_role') === 'mahasiswa')
            <a href="{{ route('profile') }}"
               class="nav-link {{ request()->routeIs('profile') || request()->routeIs('mahasiswa.show') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                Profil Saya
            </a>
        @else
            <a href="{{ route('mahasiswa.index') }}"
               class="nav-link {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-graduate"></i></span>
                Data Mahasiswa
            </a>

            <a href="{{ route('matakuliah.index') }}"
               class="nav-link {{ request()->routeIs('matakuliah.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-book-open"></i></span>
                Mata Kuliah
            </a>
        @endif

        <a href="{{ route('nilai.index') }}"
           class="nav-link {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-star-half-stroke"></i></span>
            Data Nilai
        </a>

        <a href="{{ route('absensi.index') }}"
           class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span>
            Absensi
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="footer-user-link" style="text-decoration:none; display:flex; align-items:center; gap:12px; margin-bottom:12px; transition:all 0.2s; padding:8px; border-radius:10px; cursor:pointer;">
            @if(session('user_foto_profil'))
                <img src="{{ asset(session('user_foto_profil')) }}" class="avatar" style="object-fit: cover; border-radius:50%;">
            @else
                <div class="avatar">{{ strtoupper(substr(session('user_name','A'),0,1)) }}</div>
            @endif
            <div style="flex-grow:1; min-width:0; text-align:left;">
                <p class="user-name" style="margin:0; font-size:13px; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ session('user_name','Administrator') }}</p>
                <p class="user-role" style="margin:2px 0 0; font-size:11px; color:rgba(255,255,255,0.5); display:flex; align-items:center; gap:6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    @if(session('user_role') === 'mahasiswa')
                        NIM: {{ session('user_nim') }}
                    @elseif(session('user_nidn'))
                        NIDN: {{ session('user_nidn') }}
                    @else
                        {{ session('user_email') }}
                    @endif
                    <i class="fas fa-pen-to-square" style="font-size:10px; color:rgba(255,255,255,0.4);" title="Edit Profil"></i>
                </p>
            </div>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-title-bar">{!! $__env->yieldContent('page-title', 'Dashboard') !!}</div>
        </div>
        <div class="topbar-right">
            <div class="status-badge">
                <div class="status-dot"></div>
                Sistem Aktif
            </div>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- BREADCRUMB -->
        @hasSection('breadcrumb')
        <div class="breadcrumb-custom">
            @yield('breadcrumb')
        </div>
        @endif

        <!-- FLASH SUCCESS -->
        @if(session('success'))
        <div class="alert-c alert-success-c">
            <i class="fas fa-circle-check" style="color:var(--success);font-size:18px;"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <!-- FLASH ERROR -->
        @if(session('error'))
        <div class="alert-c alert-danger-c">
            <i class="fas fa-triangle-exclamation" style="color:var(--danger);font-size:18px;"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert-c').forEach(el => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4500);
</script>

@if(session('login_success'))
<div id="welcomeToast" class="toast-welcome">
    <div class="toast-welcome-icon">
        <i class="fas fa-handshake"></i>
    </div>
    <div class="toast-welcome-content">
        <h5 class="toast-welcome-title">Login Berhasil!</h5>
        <p class="toast-welcome-msg">{{ session('login_success') }}</p>
    </div>
    <button class="toast-welcome-close" onclick="closeWelcomeToast()">
        <i class="fas fa-times"></i>
    </button>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('welcomeToast');
        setTimeout(() => {
            toast.classList.add('show');
        }, 300);

        // Auto close after 4.5 seconds
        setTimeout(() => {
            closeWelcomeToast();
        }, 4800);
    });

    function closeWelcomeToast() {
        const toast = document.getElementById('welcomeToast');
        if (toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }
</script>
@endif

@stack('scripts')
</body>
</html>
