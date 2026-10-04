<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                Dashboard
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('ordens.create') }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold text-xs sm:text-sm px-3.5 py-2 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nova OS
                </a>
            </div>
        </div>
    </x-slot>

    {{-- 1. STATUS DAS ORDENS DE SERVIÇO (Destaque Principal no Topo) --}}
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Status das ordens</p>
        <a href="{{ route('ordens.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 transition">
            Ver todas as OS &rarr;
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-8">
        {{-- Abertas --}}
        <a href="{{ route('ordens.index', ['status' => 'aberta']) }}"
           class="bg-blue-50/80 hover:bg-blue-100/70 dark:bg-blue-950/40 dark:hover:bg-blue-950/60 border border-blue-100 dark:border-blue-900/60 rounded-2xl p-4 sm:p-5 transition hover:shadow-sm group">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Abertas</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-blue-700 dark:text-blue-300">{{ $osAbertas }}</p>
            <span class="text-[11px] text-blue-500/80 dark:text-blue-400/80 mt-1 block group-hover:underline">Aguardando início</span>
        </a>

        {{-- Em Andamento --}}
        <a href="{{ route('ordens.index', ['status' => 'em_andamento']) }}"
           class="bg-amber-50/80 hover:bg-amber-100/70 dark:bg-amber-950/40 dark:hover:bg-amber-950/60 border border-amber-100 dark:border-amber-900/60 rounded-2xl p-4 sm:p-5 transition hover:shadow-sm group">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Em andamento</span>
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-amber-700 dark:text-amber-300">{{ $osAndamento }}</p>
            <span class="text-[11px] text-amber-500/80 dark:text-amber-400/80 mt-1 block group-hover:underline">Na oficina</span>
        </a>

        {{-- Aguardando Aprovação --}}
        <a href="{{ route('ordens.index', ['status' => 'aguardando_aprovacao']) }}"
           class="bg-purple-50/80 hover:bg-purple-100/70 dark:bg-purple-950/40 dark:hover:bg-purple-950/60 border border-purple-100 dark:border-purple-900/60 rounded-2xl p-4 sm:p-5 transition hover:shadow-sm group">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 truncate">Aguard. Aprovação</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-purple-700 dark:text-purple-300">{{ $osAguardandoAprovacao }}</p>
            <span class="text-[11px] text-purple-500/80 dark:text-purple-400/80 mt-1 block group-hover:underline">Com o cliente</span>
        </a>

        {{-- Concluídas --}}
        <a href="{{ route('ordens.index', ['status' => 'concluida']) }}"
           class="bg-green-50/80 hover:bg-green-100/70 dark:bg-green-950/40 dark:hover:bg-green-950/60 border border-green-100 dark:border-green-900/60 rounded-2xl p-4 sm:p-5 transition hover:shadow-sm group">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-green-600 dark:text-green-400">Concluídas</span>
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-green-700 dark:text-green-300">{{ $osConcluidas }}</p>
            <span class="text-[11px] text-green-500/80 dark:text-green-400/80 mt-1 block group-hover:underline">Finalizadas</span>
        </a>
    </div>

    {{-- 2. FATURAMENTO --}}
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Faturamento</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mb-8">
        <div class="bg-gradient-to-br from-emerald-50 to-green-50/40 dark:from-emerald-950/30 dark:to-green-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl p-5 shadow-xs">
            <p class="flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Faturamento Total Acumulado
            </p>
            <p class="text-2xl sm:text-3xl font-black text-emerald-800 dark:text-emerald-200 tracking-tight">
                R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}
            </p>
            <span class="text-[11px] text-emerald-600/80 dark:text-emerald-400/80 mt-1 block">Considerando todas as ordens concluídas</span>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-green-50/40 dark:from-emerald-950/30 dark:to-green-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl p-5 shadow-xs">
            <p class="flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                Faturamento do Mês Atual
            </p>
            <p class="text-2xl sm:text-3xl font-black text-emerald-800 dark:text-emerald-200 tracking-tight">
                R$ {{ number_format($faturamentoMes, 2, ',', '.') }}
            </p>
            <span class="text-[11px] text-emerald-600/80 dark:text-emerald-400/80 mt-1 block">Total faturado em {{ now()->translatedFormat('F/Y') }}</span>
        </div>
    </div>

    {{-- 3. GRÁFICOS E INDICADORES --}}
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Indicadores</p>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        {{-- Gráfico de Faturamento --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-5 sm:p-6">
            <h3 class="font-bold text-base text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                Faturamento Mensal (R$)
            </h3>
            <div class="h-72">
                <canvas id="faturamentoChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Status --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-2xl p-5 sm:p-6">
            <h3 class="font-bold text-base text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                </svg>
                Distribuição de Ordens por Status
            </h3>
            <div class="h-72">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 4. CADASTROS GERAIS --}}
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Cadastros</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-8">
        <a href="{{ route('clientes.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 rounded-2xl p-4 sm:p-5 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-slate-50/60 dark:hover:bg-gray-700/40 transition hover:shadow-xs group">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                Clientes
            </p>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $clientes }}</p>
        </a>

        <a href="{{ route('veiculos.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 rounded-2xl p-4 sm:p-5 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-slate-50/60 dark:hover:bg-gray-700/40 transition hover:shadow-xs group">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                Veículos
            </p>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $veiculos }}</p>
        </a>

        <a href="{{ route('ordens.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 rounded-2xl p-4 sm:p-5 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-slate-50/60 dark:hover:bg-gray-700/40 transition hover:shadow-xs group">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                Total de Ordens
            </p>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $ordens }}</p>
        </a>

        <a href="{{ route('servicos.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 rounded-2xl p-4 sm:p-5 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-slate-50/60 dark:hover:bg-gray-700/40 transition hover:shadow-xs group">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>
                Serviços Cadastrados
            </p>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $servicos }}</p>
        </a>
    </div>

    {{-- 5. ESTOQUE BAIXO E AÇÕES RÁPIDAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Estoque Baixo (Se ativo) --}}
        @if(auth()->user()->empresa?->hasControleEstoque())
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Peças com Estoque Baixo
                    </h3>
                    <a href="{{ route('pecas.index') }}" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                        Ver peças &rarr;
                    </a>
                </div>

                @if($pecasBaixas->count())
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach($pecasBaixas as $peca)
                            <li class="flex items-center justify-between py-2.5">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $peca->nome }}
                                </span>
                                <span class="text-xs font-bold text-red-600 bg-red-50 dark:bg-red-950/50 border border-red-100 dark:border-red-900/40 px-2.5 py-1 rounded-md">
                                    {{ $peca->estoque }} un.
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Nenhuma peça com estoque baixo no momento.
                    </p>
                @endif
            </div>
        @endif

        {{-- Ações Rápidas --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl p-5 sm:p-6 {{ !auth()->user()->empresa?->hasControleEstoque() ? 'lg:col-span-2' : '' }}">
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                Atalhos Rápidos
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('ordens.create') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl border border-blue-100 dark:border-blue-900/50 bg-blue-50/40 hover:bg-blue-50 dark:bg-blue-950/30 dark:hover:bg-blue-950/60 transition group">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 block transition">Nova Ordem de Serviço</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Criar orçamento ou OS</span>
                    </div>
                </a>

                <a href="{{ route('clientes.create') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 hover:bg-gray-100/60 dark:bg-gray-700/30 dark:hover:bg-gray-700/60 transition group">
                    <div class="w-9 h-9 rounded-lg bg-slate-800 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 block transition">Cadastrar Cliente</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Novo cliente no sistema</span>
                    </div>
                </a>

                <a href="{{ route('veiculos.create') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 hover:bg-gray-100/60 dark:bg-gray-700/30 dark:hover:bg-gray-700/60 transition group">
                    <div class="w-9 h-9 rounded-lg bg-slate-800 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 block transition">Cadastrar Veículo</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Vincular a um cliente</span>
                    </div>
                </a>

                <a href="{{ route('servicos.create') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 hover:bg-gray-100/60 dark:bg-gray-700/30 dark:hover:bg-gray-700/60 transition group">
                    <div class="w-9 h-9 rounded-lg bg-slate-800 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 block transition">Cadastrar Serviço</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Tabela de mão de obra</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Gráfico de Faturamento por Mês
        const faturamentoCtx = document.getElementById('faturamentoChart');
        if (faturamentoCtx) {
            new Chart(faturamentoCtx, {
                type: 'bar',
                data: {
                    labels: @json($faturamentoChart->pluck('mes_pt')),
                    datasets: [{
                        label: 'Faturamento',
                        data: @json($faturamentoChart->pluck('total')),
                        backgroundColor: '#3B82F6',
                        hoverBackgroundColor: '#2563EB',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.y || 0;
                                    return 'Faturamento: ' + new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + Number(value).toLocaleString('pt-BR');
                                }
                            },
                            grid: {
                                color: 'rgba(226, 232, 240, 0.6)'
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Gráfico de Ordens por Status
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusLabels = @json($statusChart->pluck('status_label'));
            const statusTotals = @json($statusChart->pluck('total'));

            // Paleta de cores semântica e elegante para os status
            const colorMap = {
                'Aberta': '#3B82F6',               // Azul
                'Em Andamento': '#F59E0B',         // Âmbar
                'Aguardando Aprovação': '#8B5CF6', // Roxo
                'Aprovada': '#10B981',             // Esmeralda
                'Reprovada': '#EF4444',            // Vermelho
                'Concluída': '#059669',            // Verde
                'Entregue': '#6366F1',             // Índigo
                'Cancelada': '#9CA3AF'             // Cinza
            };

            const bgColors = statusLabels.map(label => colorMap[label] || '#64748B');

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusTotals,
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 14,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.parsed || 0;
                                    return ` ${context.label}: ${total} ${total === 1 ? 'ordem' : 'ordens'}`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    </script>

</x-app-layout>