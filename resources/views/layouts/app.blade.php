<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MecDesk</title>

        <!-- Inter Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; }
            body { background-color: #F5F7FA; }

            /* Sidebar */
            .sidebar { background-color: #081A3A; width: 256px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40; display: flex; flex-direction: column; transition: transform 0.3s ease; }
            .sidebar-brand { padding: 24px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
            .sidebar-brand h1 { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: 0.5px; }
            .sidebar-brand p { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 2px; text-transform: uppercase; letter-spacing: 1px; }
            .sidebar-nav { padding: 16px 12px; flex: 1; }
            .sidebar-label { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1.5px; padding: 8px 8px 4px; margin-top: 8px; }
            .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: rgba(255,255,255,0.65); font-size: 13.5px; font-weight: 500; text-decoration: none; transition: all 0.18s ease; margin-bottom: 2px; }
            .sidebar-link:hover { background: rgba(255,255,255,0.08); color: #fff; }
            .sidebar-link.active { background: #2563EB; color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.35); }
            .sidebar-link svg { width: 18px !important; height: 18px !important; flex-shrink: 0; display: block; }
            .topbar-btn svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
            .alert svg { width: 16px; height: 16px; flex-shrink: 0; display: block; }
            .sidebar-footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08); }
            .sidebar-user { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; }
            .sidebar-avatar { width: 34px; height: 34px; border-radius: 50%; background: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; color: #fff; flex-shrink: 0; }
            .sidebar-user-info p { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.2; }
            .sidebar-user-info span { font-size: 11px; color: rgba(255,255,255,0.4); }

            /* Main content */
            .main-content { margin-left: 256px; min-height: 100vh; display: flex; flex-direction: column; }

            /* Topbar */
            .topbar { background: #fff; border-bottom: 1px solid #E8ECF0; height: 60px; display: flex; align-items: center; padding: 0 24px; gap: 16px; position: sticky; top: 0; z-index: 30; }
            .topbar-title { font-size: 16px; font-weight: 600; color: #0F172A; flex: 1; }
            .topbar-actions { display: flex; align-items: center; gap: 8px; }
            .topbar-btn { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #E8ECF0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748B; transition: all 0.15s; }
            .topbar-btn:hover { background: #F5F7FA; color: #0F172A; }
            .mobile-menu-btn { display: none; }

            /* Content */
            .content-area { padding: 24px; flex: 1; }

            /* Cards */
            .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); padding: 24px; }
            .card-sm { padding: 16px 20px; }

            /* Stats cards */
            .stat-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); padding: 20px; border-left: 4px solid transparent; transition: box-shadow 0.18s; }
            .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .stat-card.blue { border-left-color: #2563EB; }
            .stat-card.green { border-left-color: #22C55E; }
            .stat-card.amber { border-left-color: #F59E0B; }
            .stat-card.red { border-left-color: #EF4444; }
            .stat-card.purple { border-left-color: #8B5CF6; }
            .stat-label { font-size: 12px; font-weight: 500; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; margin-bottom: 8px; }
            .stat-value { font-size: 28px; font-weight: 700; color: #0F172A; line-height: 1; }
            .stat-sub { font-size: 12px; color: #94A3B8; margin-top: 4px; }

            /* Tables */
            .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; }
            .table-header { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; }
            .table-title { font-size: 15px; font-weight: 600; color: #0F172A; }
            table.md-table { width: 100%; border-collapse: collapse; }
            table.md-table thead tr { background: #F8FAFC; }
            table.md-table th { text-align: left; padding: 12px 16px; font-size: 11.5px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #F1F5F9; }
            table.md-table td { padding: 13px 16px; font-size: 13.5px; color: #334155; border-bottom: 1px solid #F8FAFC; }
            table.md-table tbody tr:hover { background: #FAFCFF; }
            table.md-table tbody tr:last-child td { border-bottom: none; }

            /* Badges */
            .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
            .badge-blue { background: #EFF6FF; color: #2563EB; }
            .badge-green { background: #F0FDF4; color: #16A34A; }
            .badge-amber { background: #FFFBEB; color: #D97706; }
            .badge-red { background: #FEF2F2; color: #DC2626; }
            .badge-purple { background: #F5F3FF; color: #7C3AED; }
            .badge-gray { background: #F1F5F9; color: #475569; }

            /* Buttons */
            .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 500; cursor: pointer; transition: all 0.18s; border: none; text-decoration: none; }
            .btn-sm { padding: 6px 12px; font-size: 12.5px; border-radius: 6px; }
            .btn-primary { background: #2563EB; color: #fff; }
            .btn-primary:hover { background: #1D4ED8; box-shadow: 0 4px 12px rgba(37,99,235,0.3); color: #fff; }
            .btn-success { background: #22C55E; color: #fff; }
            .btn-success:hover { background: #16A34A; color: #fff; }
            .btn-warning { background: #F59E0B; color: #fff; }
            .btn-warning:hover { background: #D97706; color: #fff; }
            .btn-danger { background: #EF4444; color: #fff; }
            .btn-danger:hover { background: #DC2626; color: #fff; }
            .btn-secondary { background: #F1F5F9; color: #475569; }
            .btn-secondary:hover { background: #E2E8F0; color: #334155; }
            .btn-outline { background: transparent; color: #2563EB; border: 1.5px solid #2563EB; }
            .btn-outline:hover { background: #EFF6FF; color: #1D4ED8; }

            /* Forms */
            .form-group { margin-bottom: 18px; }
            .form-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
            .form-input { width: 100%; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1E293B; background: #fff; transition: border-color 0.15s, box-shadow 0.15s; outline: none; }
            .form-input:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
            .form-input::placeholder { color: #94A3B8; }
            select.form-input { cursor: pointer; }
            textarea.form-input { resize: vertical; min-height: 100px; }

            /* Alerts */
            .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
            .alert-success { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }
            .alert-error { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

            /* Page header */
            .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
            .page-title { font-size: 20px; font-weight: 700; color: #0F172A; }
            .page-sub { font-size: 13px; color: #64748B; margin-top: 2px; }

            /* Section labels */
            .section-label { font-size: 11px; font-weight: 600; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }

            /* Mobile overlay */
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 35; }

            @media (max-width: 768px) {
                .sidebar { transform: translateX(-100%); }
                .sidebar.open { transform: translateX(0); }
                .sidebar-overlay.open { display: block; }
                .main-content { margin-left: 0; }
                .mobile-menu-btn { display: flex; }
                .content-area { padding: 16px; }
            }
        </style>
    </head>
    <body>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h1>⚙ MecDesk</h1>
            <p>Gestão de Oficina</p>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Principal</div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="sidebar-label" style="margin-top:16px">Cadastros</div>

            <a href="{{ route('clientes.index') }}"
               class="sidebar-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clientes
            </a>

            <a href="{{ route('veiculos.index') }}"
               class="sidebar-link {{ request()->routeIs('veiculos.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
                Veículos
            </a>

            <div class="sidebar-label" style="margin-top:16px">Operações</div>

            <a href="{{ route('ordens.index') }}"
               class="sidebar-link {{ request()->routeIs('ordens.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
                Ordens de Serviço
            </a>

            <a href="{{ route('servicos.index') }}"
               class="sidebar-link {{ request()->routeIs('servicos.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75"/>
                </svg>
                Serviços
            </a>

            <a href="{{ route('pecas.index') }}"
               class="sidebar-link {{ request()->routeIs('pecas.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
                Peças
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <p>{{ Auth::user()->name ?? 'Usuário' }}</p>
                    <span>{{ Auth::user()->email ?? '' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
                @csrf
                <button type="submit" class="sidebar-link" style="width:100%;border:none;cursor:pointer;background:none;text-align:left;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <button class="topbar-btn mobile-menu-btn" onclick="toggleSidebar()" id="menuBtn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <span class="topbar-title">
                @isset($header)
                    {{ $header }}
                @else
                    MecDesk
                @endisset
            </span>

            <div class="topbar-actions">
                <a href="{{ route('profile.edit') }}" class="topbar-btn" title="Perfil">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('open');
        }
    </script>

    </body>
</html>
