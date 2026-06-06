<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MecDesk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css'])
</head>
<body class="auth-page">

<div class="auth-layout">

    {{-- Painel de marca --}}
    <aside class="auth-brand-panel">
        <div class="auth-brand-content">
            <a href="{{ url('/') }}" class="auth-logo">
                <span class="auth-logo-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75"/>
                    </svg>
                </span>
                <span class="auth-logo-text">MecDesk</span>
            </a>

            <h1 class="auth-brand-title">Gestão inteligente<br>para sua oficina</h1>
            <p class="auth-brand-subtitle">Controle clientes, veículos, ordens de serviço e faturamento em um só lugar.</p>

            <ul class="auth-features">
                <li>
                    <span class="auth-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Ordens de serviço organizadas
                </li>
                <li>
                    <span class="auth-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Controle de peças e serviços
                </li>
                <li>
                    <span class="auth-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Relatórios e faturamento
                </li>
            </ul>
        </div>

        <div class="auth-brand-footer">
            <p>&copy; {{ date('Y') }} MecDesk. Todos os direitos reservados.</p>
        </div>
    </aside>

    {{-- Painel do formulário --}}
    <main class="auth-form-panel">
        <div class="auth-form-container">
            @yield('content')
        </div>
    </main>

</div>

@stack('scripts')

</body>
</html>
