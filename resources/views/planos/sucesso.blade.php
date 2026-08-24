<x-app-layout>
    <x-slot name="header">Assinatura Confirmada</x-slot>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        {{-- Card Principal de Sucesso --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 sm:p-12 text-center relative overflow-hidden">
            
            {{-- Background Accent Glow --}}
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Ícone com Animação de Sucesso --}}
            <div class="relative z-10 w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/10 border border-emerald-200/60 ring-8 ring-emerald-50">
                <i class="bi bi-check-circle-fill text-4xl"></i>
            </div>

            {{-- Título & Mensagem de Boas-Vindas --}}
            <h1 class="relative z-10 text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
                Parabéns! Sua compra foi concluída 🎉
            </h1>
            <p class="relative z-10 text-base text-slate-600 max-w-xl mx-auto leading-relaxed mb-8">
                Sua assinatura do <strong class="text-slate-900">MecDesk Pro</strong> já está ativa. Seja muito bem-vindo! Agora sua oficina conta com a gestão mais moderna e intuitiva.
            </p>

            {{-- Card Detalhado da Assinatura --}}
            @php
                $plano = $empresa->plano;
            @endphp

            <div class="relative z-10 bg-slate-50/80 border border-slate-200/80 rounded-2xl p-6 sm:p-8 max-w-lg mx-auto mb-10 text-left space-y-4 backdrop-blur-sm">
                <div class="flex items-center justify-between border-b border-slate-200/60 pb-4">
                    <div>
                        <span class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Empresa / Oficina</span>
                        <h2 class="text-lg font-bold text-slate-900 mt-0.5">{{ $empresa->nome_fantasia }}</h2>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full flex items-center gap-1.5 border border-emerald-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Ativo
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                    <div>
                        <span class="text-slate-500 block mb-0.5 font-medium">Plano Contratado</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $plano?->nome ?? 'MecDesk Pro' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block mb-0.5 font-medium">Valor Mensal</span>
                        <span class="font-bold text-blue-600 text-sm">R$ {{ number_format($plano?->preco_mensal ?? 99.90, 2, ',', '.') }} <span class="font-normal text-slate-500 text-xs">/ mês</span></span>
                    </div>
                    <div>
                        <span class="text-slate-500 block mb-0.5 font-medium">Forma de Pagamento</span>
                        <span class="font-semibold text-slate-800 flex items-center gap-1">
                            <i class="bi bi-credit-card-2-front text-blue-600"></i>
                            Cartão de Crédito
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-500 block mb-0.5 font-medium">Próximo Vencimento</span>
                        <span class="font-semibold text-slate-800">
                            {{ $assinatura?->valido_ate ? \Carbon\Carbon::parse($assinatura->valido_ate)->format('d/m/Y') : now()->addMonth()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Dicas / Guia Rápido de Início --}}
            <div class="relative z-10 border-t border-slate-100 pt-8 mb-10">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Três passos simples para começar:</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left max-w-3xl mx-auto">
                    {{-- Passo 1 --}}
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 font-black flex items-center justify-center text-sm">1</div>
                        <h4 class="font-bold text-slate-900 text-sm">Cadastre Clientes</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Adicione seus clientes e vincule os veículos com placa, marca e modelo.</p>
                    </div>

                    {{-- Passo 2 --}}
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-sm">2</div>
                        <h4 class="font-bold text-slate-900 text-sm">Catálogo de Serviços</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Cadastre sua tabela de mão de obra e peças com preço e estoque.</p>
                    </div>

                    {{-- Passo 3 --}}
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 font-black flex items-center justify-center text-sm">3</div>
                        <h4 class="font-bold text-slate-900 text-sm">Abra a 1ª Ordem</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Gere orçamentos e envie o link de aprovação direta pelo celular do cliente.</p>
                    </div>
                </div>
            </div>

            {{-- Botões de Ação Principal --}}
            <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('dashboard') }}"
                   class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2 text-base">
                    <span>Acessar o Painel da Oficina</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('assinatura.minha') }}"
                   class="w-full sm:w-auto px-7 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-2xl transition-all text-center flex items-center justify-center gap-2 text-sm">
                    <i class="bi bi-card-heading text-slate-500"></i>
                    <span>Ver Minha Assinatura</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
