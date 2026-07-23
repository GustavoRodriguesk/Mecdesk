<x-app-layout>
    <x-slot name="header">Checkout de Assinatura — Plano {{ $plano->nome }}</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="mb-6">
            <a href="{{ route('planos.upgrade') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar aos planos
            </a>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Resumo do Plano -->
            <div class="md:col-span-1 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
                <span class="text-xs uppercase font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">Resumo do Pedido</span>
                <h3 class="text-2xl font-black text-slate-900 mt-3 mb-1">{{ $plano->nome }}</h3>
                <p class="text-xs text-slate-500 mb-6">{{ $plano->descricao }}</p>

                <div class="border-t border-slate-100 pt-4 mb-6">
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-slate-600">Mensalidade</span>
                        <span class="font-bold text-slate-900">R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mb-2 text-emerald-600">
                        <span>Renovação Automática</span>
                        <span class="font-bold">Cartão de Crédito</span>
                    </div>
                    <div class="border-t border-slate-100 mt-4 pt-4 flex justify-between items-center text-base font-extrabold text-slate-900">
                        <span>Total Hoje</span>
                        <span class="text-blue-600 text-xl">R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Opções de Pagamento -->
            <div class="md:col-span-2 space-y-6">
                <!-- Opção 1: Cartão de Crédito (Recorrência Automática) -->
                <div class="bg-white p-6 rounded-2xl border-2 border-blue-600 shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">Cartão de Crédito</h4>
                                <span class="text-xs text-emerald-600 font-semibold">Renovação Mensal Automática</span>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs font-extrabold px-2.5 py-1 rounded-full">Recomendado</span>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Sua assinatura será renovada automaticamente todos os meses via Mercado Pago. Você pode cancelar a qualquer momento sem fidelidade.
                    </p>
                    <form method="POST" action="{{ route('checkout.cartao') }}">
                        @csrf
                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center space-x-2">
                            <span>Pagar com Cartão de Crédito (Mercado Pago)</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Opção 2: PIX (Pagamento por Ciclo) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">PIX (Pagamento por Ciclo)</h4>
                            <span class="text-xs text-slate-500 font-medium">Aprovação Imediata</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Gere o QR Code PIX para pagamento imediato. A cada vencimento mensal, uma nova cobrança PIX será gerada para renovação.
                    </p>
                    <form method="POST" action="{{ route('checkout.pix') }}">
                        @csrf
                        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center space-x-2">
                            <span>Gerar QR Code PIX</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
