<x-app-layout>
    <x-slot name="header">Ativação da Assinatura</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 mb-2">Sua conta precisa de uma assinatura ativa</h2>
            <p class="text-slate-600 max-w-lg mx-auto mb-6">
                Para ter acesso completo ao sistema MecDesk e gerenciar suas ordens de serviço, ative seu plano escolhido ou selecione uma opção abaixo.
            </p>

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 text-rose-700 p-4 rounded-xl mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-8 text-left max-w-xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-xs uppercase font-bold text-slate-400">Plano Selecionado</span>
                        <h3 class="text-lg font-bold text-slate-800">{{ auth()->user()->empresa->plano->nome ?? 'Pendente' }}</h3>
                    </div>
                    <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">Aguardando Pagamento</span>
                </div>
                <div class="text-2xl font-black text-slate-900 mb-1">
                    R$ {{ number_format(auth()->user()->empresa->plano->preco_mensal ?? 0, 2, ',', '.') }} <span class="text-sm font-normal text-slate-500">/mês</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('planos.upgrade') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all text-center">
                    Realizar Pagamento
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all">
                        Sair da Conta
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
