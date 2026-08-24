<x-app-layout>
    <x-slot name="header">Ativação da Assinatura</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">

        {{-- Mensagens de sessão --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-4 rounded-xl mb-6 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 text-blue-800 border border-blue-200 p-4 rounded-xl mb-6 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 text-rose-700 border border-rose-200 p-4 rounded-xl mb-6 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">

            {{-- Ícone de status (muda via JS) --}}
            <div id="statusIcon" class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                {{-- Ícone de relógio / aguardando --}}
                <svg class="w-8 h-8 animate-spin opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h2 id="statusTitle" class="text-2xl font-bold text-slate-800 mb-2">Aguardando confirmação do pagamento</h2>
            <p id="statusDesc" class="text-slate-500 max-w-lg mx-auto mb-8">
                Verificando o status do seu pagamento junto ao Mercado Pago. Isso acontece automaticamente — não feche esta página.
            </p>

            {{-- Card com info do plano --}}
            @php
                $empresa = auth()->user()->empresa;
                $assinatura = $empresa?->assinaturaAtiva()->first() ?? $empresa?->assinaturas()->latest()->first();
                $plano = $empresa?->plano;
            @endphp

            @if($plano)
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-8 text-left max-w-md mx-auto">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Plano Selecionado</span>
                        <h3 class="text-lg font-bold text-slate-800 mt-0.5">{{ $plano->nome }}</h3>
                    </div>
                    <span id="statusBadge" class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
                        Aguardando Pagamento
                    </span>
                </div>
                <div class="text-2xl font-black text-slate-900 mb-1">
                    R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}
                    <span class="text-sm font-normal text-slate-500">/mês</span>
                </div>
                @if($assinatura)
                    <div class="text-xs text-slate-500 mt-2">
                        Método de pagamento: <span class="font-medium capitalize">{{ $assinatura->metodo_pagamento }}</span>
                    </div>
                @endif
            </div>
            @endif

            {{-- Indicador de verificação automática --}}
            <div id="pollingIndicator" class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-8">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                <span id="pollingText">Verificando pagamento automaticamente...</span>
            </div>

            {{-- Ações --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('checkout.show') }}"
                   class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all text-center text-sm">
                    Realizar / Trocar Pagamento
                </a>
                <button onclick="verificarStatus()"
                        class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all text-sm">
                    Verificar Agora
                </button>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 text-slate-500 hover:text-slate-700 font-medium rounded-xl transition-all text-sm">
                        Sair da Conta
                    </button>
                </form>
            </div>

            {{-- Instruções PIX / Boleto --}}
            <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-4 text-left max-w-lg mx-auto">
                <p class="text-xs font-semibold text-blue-800 mb-1">💡 Pagou por PIX ou Boleto?</p>
                <p class="text-xs text-blue-700">
                    O Mercado Pago pode levar alguns minutos para confirmar o pagamento.
                    Esta página verifica automaticamente a cada 10 segundos.
                    Para PIX: a liberação costuma ser quase imediata.
                    Para Boleto: pode levar até 3 dias úteis.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const statusUrl = '{{ route('assinatura.status') }}';
        const dashboardUrl = '{{ route('assinatura.sucesso') }}';
        let tentativas = 0;
        const MAX_TENTATIVAS = 60; // 10 min no máximo

        async function verificarStatus() {
            try {
                const res  = await fetch(statusUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                tentativas++;

                if (data.ativa) {
                    // Pagamento confirmado — mostra feedback visual antes de redirecionar
                    ativarVisual();
                    setTimeout(() => { window.location.href = dashboardUrl; }, 1800);
                    return;
                }

                atualizarBadge(data.status);

                if (tentativas >= MAX_TENTATIVAS) {
                    pararPolling();
                    return;
                }
            } catch (e) {
                console.warn('Erro ao verificar status:', e);
            }
        }

        function ativarVisual() {
            // Ícone de sucesso
            document.getElementById('statusIcon').className =
                'w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4';
            document.getElementById('statusIcon').innerHTML = `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`;

            document.getElementById('statusTitle').textContent = '🎉 Pagamento Confirmado!';
            document.getElementById('statusDesc').textContent  = 'Seu acesso foi ativado. Redirecionando ao dashboard...';

            const badge = document.getElementById('statusBadge');
            if (badge) {
                badge.className = 'px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full';
                badge.textContent = 'Ativo';
            }

            const indicator = document.getElementById('pollingIndicator');
            if (indicator) {
                indicator.innerHTML = `<span class="text-emerald-600 font-medium text-sm">✓ Pagamento confirmado — redirecionando...</span>`;
            }
        }

        function atualizarBadge(status) {
            const badge = document.getElementById('statusBadge');
            if (!badge) return;

            const mapa = {
                'pending':    { label: 'Aguardando Pagamento', cls: 'px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full' },
                'in_process': { label: 'Em Processamento',     cls: 'px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full' },
                'authorized': { label: 'Autorizado',           cls: 'px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full' },
                'overdue':    { label: 'Pagamento Atrasado',   cls: 'px-3 py-1 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full' },
                'cancelled':  { label: 'Cancelado',            cls: 'px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full' },
            };

            const info = mapa[status] ?? mapa['pending'];
            badge.className   = info.cls;
            badge.textContent = info.label;
        }

        function pararPolling() {
            clearInterval(pollingInterval);
            const el = document.getElementById('pollingText');
            if (el) el.textContent = 'Verificação pausada após 10 minutos. Clique em "Verificar Agora" para tentar novamente.';
            const dot = document.querySelector('#pollingIndicator span.relative.flex');
            if (dot) dot.remove();
        }

        // Polling a cada 10 segundos
        const pollingInterval = setInterval(verificarStatus, 10000);

        // Primeira verificação após 3 segundos (caso o cartão tenha sido aprovado na hora)
        setTimeout(verificarStatus, 3000);
    </script>
    @endpush
</x-app-layout>
