<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MecDesk')</title>

    <!-- Inter Font & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/auth.css'])
</head>
<body class="auth-body">

    <div class="auth-wrapper">
        <header class="auth-header">
            <a href="{{ route('planos.index') }}" class="auth-brand" title="MecDesk — Ir para a página inicial">
                <div class="auth-brand-logo">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <div class="auth-brand-info">
                    <span class="auth-brand-title">MecDesk</span>
                    <span class="auth-brand-subtitle">Gestão de Oficina</span>
                </div>
            </a>
        </header>

        <main class="auth-card">
            @yield('content')
        </main>

        <footer class="auth-footer">
            <p>&copy; {{ date('Y') }} MecDesk. Todos os direitos reservados.</p>
        </footer>
    </div>

    @stack('scripts')

</body>
</html>
