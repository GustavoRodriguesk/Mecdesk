<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark"><i class="ti ti-bolt"></i></div>
        <span class="logo-text">MeuSaaS</span>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-label">Principal</span>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="ti ti-layout-dashboard"></i> Dashboard
        </a>

        {{-- <a href="{{ route('clientes.index') }}"
           class="nav-item {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            <i class="ti ti-users"></i> Clientes
        </a> --}}

        {{-- <a href="{{ route('faturas.index') }}"
           class="nav-item {{ request()->routeIs('faturas.*') ? 'active' : '' }}">
            <i class="ti ti-file-invoice"></i> Faturas
        </a> --}}

        {{-- <a href="{{ route('relatorios.index') }}"
           class="nav-item {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
            <i class="ti ti-chart-bar"></i> Relatórios
        </a> --}}

        <span class="nav-label">Sistema</span>

        {{-- <a href="{{ route('equipe.index') }}"
           class="nav-item {{ request()->routeIs('equipe.*') ? 'active' : '' }}">
            <i class="ti ti-users-group"></i> Equipe
        </a> --}}

        {{-- <a href="{{ route('configuracoes') }}"
           class="nav-item {{ request()->routeIs('configuracoes') ? 'active' : '' }}">
            <i class="ti ti-settings"></i> Configurações
        </a> --}}
    </nav>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-plan">{{ auth()->user()->plan ?? 'Plano Grátis' }}</div>
            </div>
        </div>

        @include('partials._logout')
    </div>
</aside>