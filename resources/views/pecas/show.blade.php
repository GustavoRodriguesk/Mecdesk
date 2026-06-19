<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalhes da Peça
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold uppercase select-none">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    {{ $peca->nome }}
                </h1>
            </div>
            
            <a href="{{ route('pecas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-500"></i>
                    Informações
                </h3>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('pecas.edit', $peca->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                    <i class="bi bi-pencil"></i>
                    Editar
                </a>
                @endif
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-200 text-gray-800 border border-gray-300 uppercase">
                        {{ $peca->codigo ?: '-' }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor Unitário</span>
                    <span class="text-sm font-medium text-gray-900">R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estoque</span>
                    <span class="text-sm font-medium {{ $peca->estoque <= 0 ? 'text-red-600' : ($peca->estoque < 5 ? 'text-yellow-600' : 'text-green-600') }}">{{ $peca->estoque }} unidades</span>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>