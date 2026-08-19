<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MecDesk — Gestão de Oficinas Mecânicas' }}</title>
    <meta name="description" content="O sistema completo e descomplicado para organizar ordens de serviço, clientes, veículos e faturamento da sua oficina mecânica.">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #FAFCFF; color: #1E293B; }
        .gradient-brand { background: linear-gradient(135deg, #081A3A 0%, #173264 100%); }
        .gradient-blue { background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); }
        .badge-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-slate-50 text-slate-900">

    <!-- Public Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('planos.index') }}" class="flex items-center gap-3 group focus:outline-none">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform">
                    <i class="bi bi-gear-fill text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tight text-slate-950">MecDesk</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 -mt-1">Gestão de Oficina</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="{{ route('planos.index') }}#recursos" class="hover:text-blue-600 transition-colors">Recursos</a>
                <a href="{{ route('planos.index') }}#plano" class="hover:text-blue-600 transition-colors">Plano & Preço</a>
                <a href="{{ route('planos.index') }}#faq" class="hover:text-blue-600 transition-colors">Dúvidas Frequentes</a>
            </nav>

            <!-- Action Buttons -->
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition-all">
                        <i class="bi bi-speedometer2"></i>
                        Acessar Sistema
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 transition-colors">
                        Já possui uma conta? <span class="text-blue-600 underline underline-offset-4">Entrar</span>
                    </a>
                    <a href="{{ route('planos.assinar') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-sm shadow-blue-500/20 hover:shadow-md hover:shadow-blue-500/30 transition-all">
                        Começar agora
                        <i class="bi bi-arrow-right text-xs"></i>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="flex sm:hidden items-center">
                <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden sm:hidden border-b border-slate-200 bg-white px-4 pt-3 pb-5 space-y-3">
            <a href="{{ route('planos.index') }}#recursos" class="block text-sm font-semibold text-slate-700 hover:text-blue-600 py-1">Recursos</a>
            <a href="{{ route('planos.index') }}#plano" class="block text-sm font-semibold text-slate-700 hover:text-blue-600 py-1">Plano & Preço</a>
            <a href="{{ route('planos.index') }}#faq" class="block text-sm font-semibold text-slate-700 hover:text-blue-600 py-1">Dúvidas Frequentes</a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl">
                        Acessar Sistema
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl">
                        Entrar na conta
                    </a>
                    <a href="{{ route('planos.assinar') }}" class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-sm">
                        Começar agora
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Public Footer -->
    <footer class="bg-slate-950 text-slate-400 text-sm border-t border-slate-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: Brand -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white">
                            <i class="bi bi-gear-fill text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-white tracking-tight">MecDesk</span>
                    </div>
                    <p class="text-slate-400 text-sm max-w-md leading-relaxed">
                        A plataforma completa para oficinas mecânicas que buscam organizar o fluxo de ordens de serviço, clientes, estoque de peças e equipe em um único sistema.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-slate-500 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-emerald-400 font-medium">
                            <i class="bi bi-shield-check"></i> Pagamento Seguro Mercado Pago
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-blue-400 font-medium">
                            <i class="bi bi-lock-fill"></i> Criptografia SSL
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-4">Navegação</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('planos.index') }}#recursos" class="hover:text-white transition-colors">Funcionalidades</a></li>
                        <li><a href="{{ route('planos.index') }}#plano" class="hover:text-white transition-colors">MecDesk Pro</a></li>
                        <li><a href="{{ route('planos.index') }}#faq" class="hover:text-white transition-colors">Perguntas Frequentes</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Área do Cliente</a></li>
                    </ul>
                </div>

                <!-- Col 3: Legal & Security -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-4">MecDesk SaaS</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">
                        Sistema de gestão desenvolvido especificamente para o fluxo de trabalho de oficinas mecânicas e centros automotivos.
                    </p>
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} MecDesk. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
