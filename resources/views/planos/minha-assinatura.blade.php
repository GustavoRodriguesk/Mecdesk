<x-app-layout>
    <x-slot name="header">Minha Assinatura</x-slot>

    <div class="w-full">

        {{-- Alerts de sessão --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-4 rounded-xl text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 text-rose-700 border border-rose-200 p-4 rounded-xl text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @php
            $isAssinanteAtivo = $assinatura && $assinatura->status === 'authorized' && $empresa->isAtiva();
            $isPendente = $assinatura && $assinatura->status === 'pending';
            $isCancelada = $assinatura && $assinatura->status === 'cancelled';
        @endphp

        @if ($isAssinanteAtivo)
            {{-- ── Cenário 1: Assinatura Ativa ── --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Plano Atual</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-0.5">MecDesk {{ $plano->nome ?? 'Pro' }}</h2>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                        Ativa
                    </span>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Valor</p>
                        <p class="text-2xl font-black text-slate-900">
                            R$ {{ number_format($assinatura->preco_contratado ?? 99.9, 2, ',', '.') }}
                            <span class="text-xs font-medium text-slate-500">/mês</span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Próxima cobrança
                        </p>
                        <p class="text-lg font-bold text-slate-900">
                            @if ($assinatura->proximo_vencimento)
                                {{ $assinatura->proximo_vencimento->format('d/m/Y') }}
                            @elseif($assinatura->valido_ate)
                                {{ $assinatura->valido_ate->format('d/m/Y') }}
                            @else
                                {{ now()->addMonth()->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Forma de pagamento
                        </p>
                        <p class="text-sm font-bold text-slate-800 flex items-center gap-1.5 mt-1">
                            <i class="bi bi-credit-card-2-front text-blue-600 text-base"></i>
                            Cartão de crédito
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Início da
                            assinatura</p>
                        <p class="text-sm font-semibold text-slate-700 mt-1">
                            {{ $assinatura->data_inicio ? $assinatura->data_inicio->format('d/m/Y') : ($assinatura->created_at ? $assinatura->created_at->format('d/m/Y') : '—') }}
                        </p>
                    </div>
                </div>

                <div
                    class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Sua assinatura é renovada automaticamente a cada mês no cartão cadastrado.
                    </p>
                    <button type="button"
                        onclick="document.getElementById('modalCancelarAssinatura').classList.remove('hidden')"
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline">
                        Cancelar assinatura
                    </button>
                </div>
            </div>

            {{-- Modal de Confirmação de Cancelamento --}}
            <div id="modalCancelarAssinatura"
                class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/50 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
                    <div
                        class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 text-center mb-2">Deseja realmente cancelar?</h3>
                    <p class="text-xs text-slate-600 text-center leading-relaxed mb-6">
                        Ao cancelar a assinatura do MecDesk Pro, sua cobrança automática será interrompida e o acesso
                        aos recursos do sistema será desativado ao fim do período pago.
                    </p>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button"
                            onclick="document.getElementById('modalCancelarAssinatura').classList.add('hidden')"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                            Manter assinatura
                        </button>
                        <form method="POST" action="{{ route('assinatura.cancelar') }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-sm">
                                Confirmar cancelamento
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($isPendente)
            {{-- ── Cenário 2: Assinatura Pendente ── --}}
            <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-8 text-center space-y-4">
                <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto">
                    <i class="bi bi-hourglass-split text-2xl animate-spin"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Assinatura em processamento</h2>
                <p class="text-slate-600 text-sm max-w-md mx-auto leading-relaxed">
                    Seu pagamento está aguardando confirmação junto ao Mercado Pago. O acesso ao sistema será liberado
                    automaticamente assim que for autorizado.
                </p>
                <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('assinatura.pendente') }}"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                        Acompanhar status em tempo real
                    </a>
                    <a href="{{ route('checkout.show') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
                        Tentar outro cartão
                    </a>
                </div>
            </div>
        @else
            {{-- ── Cenário 3: Sem Assinatura Ativa ou Cancelada ── --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center space-y-4">
                <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <i class="bi bi-shield-lock-fill text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Você ainda não possui uma assinatura ativa</h2>
                <p class="text-slate-600 text-sm max-w-md mx-auto leading-relaxed">
                    Assine o MecDesk Pro e tenha acesso completo à gestão de ordens de serviço, clientes, veículos e
                    catálogo da sua oficina mecânica.
                </p>
                <div class="pt-4">
                    <a href="{{ route('checkout.show') }}"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 transition-all">
                        <span>Assinar MecDesk Pro — R$ 99,90/mês</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
