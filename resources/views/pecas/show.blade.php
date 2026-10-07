<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('pecas.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-box-seam"></i>
                <span>Peças</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5 truncate max-w-xs sm:max-w-md">
                <i class="bi bi-info-circle text-blue-600"></i>
                Detalhes da Peça: {{ $peca->nome }}
            </span>
        </div>
    </x-slot>

    <div class="w-full max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span
                        class="shrink-0 inline-flex items-center justify-center h-9 w-9 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold select-none">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    {{ $peca->nome }}
                </h1>
                @if($peca->marca)
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <i class="bi bi-tag text-blue-500"></i> Marca: <span class="font-medium text-gray-700">{{ $peca->marca }}</span>
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pecas.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>
                <a href="{{ route('pecas.edit', $peca->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="bi bi-pencil"></i>
                    Editar Peça
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <i class="bi bi-info-circle text-gray-500"></i>
                <h3 class="text-sm font-semibold text-gray-800">
                    Informações Gerais
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Marca</span>
                    <span class="text-sm font-medium text-gray-800">
                        {{ $peca->marca ?: 'Não informada' }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código Interno</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800 border border-gray-300 uppercase">
                        {{ $peca->codigo ?: '-' }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código de Barras</span>
                    <span class="text-sm font-medium font-mono text-gray-800 flex items-center gap-1.5">
                        <i class="bi bi-upc-scan text-gray-400"></i> {{ $peca->codigo_barras ?: 'Não informado' }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Preço de Custo</span>
                    <span class="text-base font-semibold text-gray-700 tabular-nums">
                        R$ {{ number_format($peca->preco_custo, 2, ',', '.') }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Preço de Venda</span>
                    <span class="text-base font-bold text-blue-700 tabular-nums">
                        R$ {{ number_format($peca->preco_venda ?? $peca->valor_unitario, 2, ',', '.') }}
                    </span>
                    @php
                        $lucro = ($peca->preco_venda ?? $peca->valor_unitario) - $peca->preco_custo;
                        $margem = $peca->preco_custo > 0 ? ($lucro / $peca->preco_custo) * 100 : 0;
                    @endphp
                    @if($peca->preco_custo > 0 && $lucro > 0)
                        <span class="block text-xs text-emerald-600 font-medium mt-1">
                            Margem: +{{ number_format($margem, 1, ',', '.') }}% (Lucro R$ {{ number_format($lucro, 2, ',', '.') }})
                        </span>
                    @endif
                </div>

                @if(auth()->user()->empresa?->hasControleEstoque())
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estoque</span>
                        <span class="text-base font-semibold tabular-nums {{ $peca->estoque <= 0 ? 'text-red-600' : ($peca->estoque < 5 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $peca->estoque }} unidades
                        </span>
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>
