<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Inventory System') }} – {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: #0f1117; color: #e2e8f0; display: flex; min-height: 100vh; overflow-x: hidden; }

        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: rgba(15, 17, 23, 0.5); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.4); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.8); }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px; height: 100vh; background: #121620; /* Darker, deeper aesthetic */
            border-right: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 50;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideInLeft 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .sidebar-logo {
            padding: 24px 20px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05); position: relative; overflow: hidden;
        }
        .sidebar-logo .logo-icon {
            width: 38px; height: 38px; background: linear-gradient(135deg, #6366f1, #c084fc);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff; flex-shrink: 0;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
            animation: pulse-glow 3s infinite alternate;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 10px rgba(99, 102, 241, 0.2); transform: scale(1); }
            100% { box-shadow: 0 0 20px rgba(192, 132, 252, 0.6); transform: scale(1.05); }
        }
        /* React Bits Shiny Text Effect */
        .sidebar-logo .logo-text { 
            font-size: 18px; font-weight: 800; line-height: 1.2; 
            background: linear-gradient(120deg, #f1f5f9 20%, #818cf8 40%, #c084fc 60%, #f1f5f9 80%);
            background-size: 200% auto;
            color: #000;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shiny 4s linear infinite;
        }
        @keyframes shiny {
            to { background-position: 200% center; }
        }
        .sidebar-logo .logo-sub  { font-size: 11px; color: #64748b; font-weight: 500; letter-spacing: 0.5px; }

        .sidebar-section-label {
            padding: 24px 20px 8px; font-size: 11px; font-weight: 700; color: #475569;
            text-transform: uppercase; letter-spacing: 1.5px;
        }
        .sidebar-nav { flex: 1; overflow-y: auto; padding-bottom: 16px; }
        
        /* React Bits Magnetic/Spring Hover Effect */
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px; margin: 4px 12px; border-radius: 10px;
            color: #94a3b8; font-size: 14px; font-weight: 600;
            text-decoration: none; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden;
            border: 1px solid transparent;
        }
        .nav-item:hover { 
            background: rgba(30, 42, 58, 0.6); 
            color: #f1f5f9; 
            transform: translateX(6px) scale(1.02);
            border-color: rgba(99, 102, 241, 0.2);
        }
        /* React Bits Animated Border Active State */
        .nav-item.active { 
            background: rgba(99, 102, 241, 0.1); 
            color: #fff; 
            border: 1px solid rgba(99, 102, 241, 0.4);
            box-shadow: inset 0 0 20px rgba(99, 102, 241, 0.1), 0 4px 15px rgba(99, 102, 241, 0.2);
        }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: linear-gradient(to bottom, #6366f1, #c084fc);
            border-radius: 4px;
        }
        .nav-item .nav-icon { width: 22px; text-align: center; font-size: 16px; flex-shrink: 0; transition: transform 0.3s; }
        .nav-item:hover .nav-icon { transform: scale(1.15) rotate(5deg); color: #818cf8; }
        .nav-item.active .nav-icon { color: #c084fc; }
        .nav-badge {
            margin-left: auto; background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px;
        }
        .sidebar-footer {
            padding: 20px;
            background: rgba(15, 23, 42, 0.4);
            border-top: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            margin-top: auto;
        }
        .user-card {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.03);
            padding: 12px; border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }
        .user-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(99, 102, 241, 0.3); }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #c084fc);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px; color: #fff; flex-shrink: 0;
            position: relative; z-index: 1;
        }
        /* React bits animated avatar border */
        .user-avatar::before {
            content: ''; position: absolute; inset: -3px; border-radius: 50%;
            background: conic-gradient(from 0deg, transparent, #6366f1, #c084fc, transparent);
            z-index: -1; animation: spin-border 4s linear infinite;
        }
        @keyframes spin-border { 100% { transform: rotate(360deg); } }
        
        .user-info { flex: 1; overflow: hidden; }
        .user-name  { font-size: 14px; font-weight: 700; color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role  { font-size: 11px; color: #818cf8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-top: 2px; }
        
        .logout-btn {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(239, 68, 68, 0.1); color: #f87171; border: none; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .logout-btn:hover { background: #ef4444; color: #fff; transform: scale(1.1) rotate(5deg); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

        /* ── Main Content ── */
        .main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: #161b27; border-bottom: 1px solid #1e2536;
            padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 20px; font-weight: 700; color: #f1f5f9; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-badge {
            position: relative; color: #64748b; font-size: 18px; cursor: pointer; transition: color 0.2s;
        }
        .topbar-badge:hover { color: #6366f1; }
        .topbar-badge .dot {
            position: absolute; top: -2px; right: -3px;
            width: 8px; height: 8px; background: #ef4444; border-radius: 50%;
        }
        .topbar-user {
            display: flex; align-items: center; gap: 10px; cursor: pointer;
            padding: 6px 12px; border-radius: 8px; transition: background 0.2s;
        }
        .topbar-user:hover { background: #1e2536; }
        .page-content { flex: 1; padding: 32px; }

        /* ── Cards & Stats ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .stat-card {
            background: #161b27; border: 1px solid #1e2536; border-radius: 14px;
            padding: 22px; display: flex; align-items: center; gap: 18px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.purple { background: rgba(99,102,241,0.15); color: #818cf8; }
        .stat-icon.green  { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .stat-icon.orange { background: rgba(249,115,22,0.15); color: #fb923c; }
        .stat-icon.red    { background: rgba(239,68,68,0.15);  color: #f87171; }
        .stat-value { font-size: 28px; font-weight: 800; color: #f1f5f9; line-height: 1; }
        .stat-label { font-size: 13px; color: #64748b; margin-top: 4px; }
        .stat-change { font-size: 11px; color: #4ade80; margin-top: 6px; }

        /* ── Tables ── */
        .card {
            background: #161b27; border: 1px solid #1e2536;
            border-radius: 14px; overflow: hidden;
        }
        .card-header {
            padding: 20px 24px; border-bottom: 1px solid #1e2536;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 15px; font-weight: 700; color: #f1f5f9; }
        .card-body { padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 20px; text-align: left; font-size: 11px;
            font-weight: 600; color: #475569; text-transform: uppercase;
            letter-spacing: 0.7px; background: #0f1117; border-bottom: 1px solid #1e2536;
        }
        tbody tr { border-bottom: 1px solid #1e2536; transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #1e2a3a; }
        tbody td { padding: 14px 20px; font-size: 14px; color: #cbd5e1; vertical-align: middle; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; padding: 3px 10px;
            border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .badge-success { background: rgba(34,197,94,0.15); color: #4ade80; }
        .badge-warning { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-info    { background: rgba(99,102,241,0.15); color: #818cf8; }
        .badge-gray    { background: rgba(100,116,139,0.2); color: #94a3b8; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; border: none; text-decoration: none;
        }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #7c3aed); color: #fff; }
        .btn-primary:hover { box-shadow: 0 4px 14px rgba(99,102,241,0.4); transform: translateY(-1px); }
        .btn-secondary { background: #1e2a3a; color: #94a3b8; border: 1px solid #2d3748; }
        .btn-secondary:hover { background: #253044; color: #e2e8f0; }
        .btn-danger { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #94a3b8; margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 10px 14px; background: #0f1117;
            border: 1px solid #1e2536; border-radius: 8px; color: #e2e8f0;
            font-size: 14px; font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control::placeholder { color: #475569; }
        select.form-control { cursor: pointer; }

        /* ── Alerts ── */
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #4ade80; }
        .alert-danger  { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);  color: #f87171; }

        /* ── Chart area ── */
        .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 28px; }
        .chart-container { padding: 24px; position: relative; height: 280px; }

        /* ── Page header ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .page-title { font-size: 22px; font-weight: 800; color: #f1f5f9; }
        .page-subtitle { font-size: 13px; color: #64748b; margin-top: 2px; }

        /* ── Low stock warning ── */
        .warning-row td { color: #fbbf24 !important; }

        /* ── Notifications Dropdown ── */
        .bell-shake:hover { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0) rotate(-5deg); }
            20%, 80% { transform: translate3d(2px, 0, 0) rotate(5deg); }
            30%, 50%, 70% { transform: translate3d(-2px, 0, 0) rotate(-5deg); }
            40%, 60% { transform: translate3d(2px, 0, 0) rotate(5deg); }
        }
        .pulse-badge { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .notification-dropdown {
            display: none; position: absolute; top: 48px; right: -10px; width: 360px;
            background: rgba(18, 22, 32, 0.7); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.05);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            z-index: 100; overflow: hidden; cursor: default;
            transform-origin: top right;
            animation: dropdown-pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes dropdown-pop {
            0% { opacity: 0; transform: scale(0.9) translateY(-10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .notification-dropdown.show { display: block; }
        .dropdown-header {
            padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(255,255,255,0.02);
        }
        .dropdown-header-title { font-weight: 800; color: #f1f5f9; font-size: 15px; letter-spacing: 0.5px; }
        .dropdown-body { max-height: 380px; overflow-y: auto; padding: 8px; }
        .dropdown-item {
            display: flex; gap: 14px; padding: 14px; margin-bottom: 4px;
            border-radius: 12px; text-decoration: none; transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden;
        }
        .dropdown-item:hover { background: rgba(255,255,255,0.04); transform: translateX(6px) scale(1.01); }
        .dropdown-item-icon {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(239,68,68,0.2), rgba(239,68,68,0.05));
            color: #f87171; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; border: 1px solid rgba(239,68,68,0.2);
            box-shadow: 0 4px 10px rgba(239,68,68,0.1); transition: all 0.3s;
        }
        .dropdown-item:hover .dropdown-item-icon {
            background: linear-gradient(135deg, rgba(239,68,68,0.8), rgba(239,68,68,0.4));
            color: #fff; transform: scale(1.1) rotate(10deg);
        }
        
        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .charts-grid { grid-template-columns: 1fr; }
        }

        /* ── Mobile Hamburger Button ── */
        .hamburger {
            display: none;
            flex-direction: column; justify-content: center; align-items: center;
            gap: 5px; width: 40px; height: 40px; cursor: pointer;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px; transition: all .25s;
        }
        .hamburger:hover { background: rgba(99,102,241,.15); border-color: rgba(99,102,241,.3); }
        .hamburger span {
            display: block; width: 18px; height: 2px;
            background: #94a3b8; border-radius: 2px; transition: all .3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ── Sidebar Overlay ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
            z-index: 49; transition: opacity .3s;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 768px) {
            /* Sidebar slides off canvas */
            .sidebar {
                transform: translateX(-100%);
                animation: none !important;   /* kill the slide-in so it starts hidden */
                box-shadow: none;
                transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,.5);
            }

            /* Main fills full width */
            .main { margin-left: 0; }

            /* Topbar */
            .topbar { padding: 0 16px; }
            .hamburger { display: flex; }
            .topbar-title { font-size: 16px; }

            /* Page content */
            .page-content { padding: 16px; }

            /* Stats grid → 2 cols, then 1 col */
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 16px; }
            .stat-card { padding: 16px; gap: 12px; }
            .stat-value { font-size: 22px; }
            .stat-icon { width: 42px; height: 42px; font-size: 18px; }

            /* Charts stacked */
            .charts-grid { grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
            .chart-container { height: 220px; padding: 16px; }

            /* Page header stacked */
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
            .page-header > div:last-child { width: 100%; }
            .page-header .btn { width: 100%; justify-content: center; }

            /* Tables → horizontal scroll */
            .card { border-radius: 12px; }
            .card-header { padding: 14px 16px; }
            .card-body { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table { min-width: 600px; }
            thead th { padding: 10px 14px; font-size: 10px; }
            tbody td { padding: 12px 14px; font-size: 13px; }

            /* Notification dropdown — full width on mobile */
            .notification-dropdown { width: calc(100vw - 32px); right: -60px; }

            /* QR scanner 2-col → 1-col */
            div[style*="grid-template-columns:1fr 360px"],
            div[style*="grid-template-columns:1fr 350px"],
            div[style*="grid-template-columns:1fr 340px"],
            div[style*="grid-template-columns:1fr 300px"] {
                display: flex !important;
                flex-direction: column !important;
            }

            /* Dashboard 3-col → 1-col */
            div[style*="grid-template-columns:1.8fr 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }

            /* Product create/edit, inventory, suppliers 2-col → 1-col */
            div[style*="grid-template-columns:2fr 1fr"],
            div[style*="grid-template-columns:1fr 1fr"],
            div[style*="grid-template-columns:150px 1fr"] {
                grid-template-columns: 1fr !important;
            }

            /* General repeat(2, ...) → 1-col */
            div[style*="grid-template-columns:repeat(2"] {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-value { font-size: 20px; }
            .topbar-user span { display: none; } /* hide name, show only avatar */
            .page-title { font-size: 18px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="logo-text">InvenTrack</div>
            <div class="logo-sub">QR Inventory System</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-box"></i></span> Products
        </a>
        <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-warehouse"></i></span> Inventory
        </a>
        <a href="{{ route('qr.scanner') }}" class="nav-item {{ request()->routeIs('qr.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-qrcode"></i></span> QR Scanner
        </a>

        @if(Auth::user()->role === 'admin')
        <div class="sidebar-section-label">Admin</div>
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-tags"></i></span> Categories
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-truck"></i></span> Suppliers
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-bar"></i></span> Reports
        </a>
        @endif

        <div class="sidebar-section-label">Account</div>
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-circle"></i></span> Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn" title="Logout securely">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="main">
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            {{-- Hamburger (mobile only) --}}
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
            <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
        </div>
        <div class="topbar-right">
            @php
                $lowStockAlerts = \App\Models\Product::whereColumn('quantity', '<=', 'min_stock_level')->get();
            @endphp
            <div class="topbar-badge" id="notificationBtn" style="position:relative;">
                <i class="fas fa-bell bell-shake"></i>
                @if($lowStockAlerts->count() > 0)
                    <span class="dot pulse-badge"></span>
                @endif

                {{-- Notification Dropdown --}}
                <div id="notificationDropdown" class="notification-dropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-header-title">Notifications</div>
                        <span class="badge badge-danger pulse-badge">{{ $lowStockAlerts->count() }} New</span>
                    </div>
                    <div class="dropdown-body">
                        @if($lowStockAlerts->isEmpty())
                            <div style="padding:40px 20px; text-align:center; color:#64748b;">
                                <i class="fas fa-check-circle" style="font-size:38px; color:#4ade80; margin-bottom:16px; opacity:0.8; filter:drop-shadow(0 0 10px rgba(74,222,128,0.4));"></i>
                                <div style="font-size:15px; font-weight:700; color:#e2e8f0;">All Caught Up!</div>
                                <div style="font-size:13px; margin-top:6px;">No new alerts at this time.</div>
                            </div>
                        @else
                            @foreach($lowStockAlerts as $alert)
                            <a href="{{ route('products.show', $alert->id) }}" class="dropdown-item">
                                <div class="dropdown-item-icon">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </div>
                                <div>
                                    <div style="color:#f1f5f9; font-size:14px; font-weight:700;">Low Stock: {{ $alert->name }}</div>
                                    <div style="color:#94a3b8; font-size:12px; margin-top:4px; font-weight:500;">Only <span style="color:#f87171; font-weight:800; font-size:13px;">{{ $alert->quantity }}</span> left in stock! (Min: {{ $alert->min_stock_level }})</div>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="topbar-user">
                <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> {{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>
</div>

{{-- Mobile sidebar overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
    // Mobile sidebar toggle
    const hamburger     = document.getElementById('hamburgerBtn');
    const sidebar       = document.getElementById('sidebar');
    const overlay       = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        hamburger.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        hamburger.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (hamburger) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
    }
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Close sidebar on nav item click (mobile)
    document.querySelectorAll('.nav-item').forEach(el => {
        el.addEventListener('click', () => {
            if (window.innerWidth <= 768) closeSidebar();
            document.querySelectorAll('.nav-item').forEach(e => e.classList.remove('active'));
            el.classList.add('active');
        });
    });

    // Notification dropdown toggle
    const notifBtn = document.getElementById('notificationBtn');
    const notifDrop = document.getElementById('notificationDropdown');
    if (notifBtn && notifDrop) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDrop.classList.toggle('show');
        });
        notifDrop.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', () => {
            notifDrop.classList.remove('show');
        });
    }
</script>
@stack('scripts')
</body>
</html>
