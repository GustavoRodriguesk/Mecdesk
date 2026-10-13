<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('pecas.index') }}"
                class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
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

    <style>
        .data-row {
            transition: background-color 0.12s ease;
        }

        .data-row:hover {
            background-color: #F0F4FA;
        }

        .btn-action {
            transition: opacity 0.12s ease;
        }

        .btn-action:hover {
            opacity: 0.85;
        }
    </style>

    <div class="w-full space-y-6">

        {{-- Cabeçalho da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span
                        class="shrink-0 inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold select-none">
                        <i class="bi bi-box-seam-fill"></i>
                    </span>
                    <span>{{ $peca->nome }}</span>
                    @if ($peca->codigo)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                            {{ $peca->codigo }}
                        </span>
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Consulta de especificações, precificação, estoque e histórico de utilizações
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('pecas.edit', $peca->id) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="bi bi-pencil-square"></i>
                    <span>Editar Peça</span>
                </a>

                <a href="{{ route('pecas.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Card: Dados da Peça --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-500"></i>
                    Informações da Peça
                </h3>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                {{-- Nome --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nome</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $peca->nome }}</span>
                </div>

                {{-- Marca --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Marca</span>
                    <span class="text-sm font-medium text-gray-900">{{ $peca->marca ?: '-' }}</span>
                </div>

                {{-- Código --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código</span>
                    <span class="text-sm font-medium text-gray-900 font-mono">{{ $peca->codigo ?: '-' }}</span>
                </div>

                {{-- Código de Barras --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código de
                        Barras</span>
                    <span class="text-sm font-medium text-gray-900 font-mono">{{ $peca->codigo_barras ?: '-' }}</span>
                </div>

                {{-- Preço de Venda --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Preço de
                        Venda</span>
                    <span class="text-base font-bold text-emerald-600 tabular-nums">
                        R$ {{ number_format($peca->preco_venda ?? $peca->valor_unitario, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Preço de Custo --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Preço de
                        Custo</span>
                    <span class="text-sm font-medium text-gray-900 tabular-nums">
                        R$ {{ number_format($peca->preco_custo, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Estoque --}}
                @if (auth()->user()->empresa?->hasControleEstoque())
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estoque
                            Atual</span>
                        <span
                            class="text-base font-bold tabular-nums {{ $peca->estoque <= 0 ? 'text-red-600' : ($peca->estoque < 5 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $peca->estoque }}
                        </span>
                    </div>
                @endif

                {{-- Cadastrado em --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data de
                        Cadastro</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $peca->created_at ? $peca->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Card: Histórico de Utilização em Ordens de Serviço --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-500"></i>
                    Histórico de Utilização em Ordens de Serviço
                </h3>
                <span class="text-xs text-gray-500 font-medium">
                    {{ $peca->ordemServicoItens->count() }}
                    {{ $peca->ordemServicoItens->count() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            @if ($peca->ordemServicoItens->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nº OS</th>
                                <th class="px-6 py-3 font-semibold">Cliente</th>
                                <th class="px-6 py-3 font-semibold">Veículo</th>
                                <th class="px-6 py-3 font-semibold text-center">Qtd</th>
                                <th class="px-6 py-3 font-semibold">Valor Unit.</th>
                                <th class="px-6 py-3 font-semibold">Subtotal</th>
                                <th class="px-6 py-3 font-semibold">Data</th>
                                <th class="px-6 py-3 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($peca->ordemServicoItens as $item)
                                @if ($item->ordemServico)
                                    <tr class="data-row">
                                        <td class="px-6 py-4 font-mono font-medium text-blue-600">
                                            #{{ $item->ordemServico->numero_os ?? $item->ordemServico->id }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $item->ordemServico->cliente?->nome ?: 'Cliente não identificado' }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            @if ($item->ordemServico->veiculo)
                                                {{ $item->ordemServico->veiculo->marca }}
                                                {{ $item->ordemServico->veiculo->modelo }}
                                                <span
                                                    class="text-xs font-mono text-gray-400">({{ $item->ordemServico->veiculo->placa }})</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center tabular-nums font-semibold text-gray-900">
                                            {{ $item->quantidade }}
                                        </td>
                                        <td class="px-6 py-4 tabular-nums text-gray-600">
                                            R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 tabular-nums font-semibold text-gray-900">
                                            R$
                                            {{ number_format($item->valor_total ?? $item->quantidade * $item->valor_unitario, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $item->ordemServico->created_at ? $item->ordemServico->created_at->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('ordens.show', $item->ordemServico->id) }}"
                                                class="btn-action inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                                <i class="bi bi-eye"></i>
                                                <span>Ver OS</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center text-gray-400">
                    <i class="bi bi-inbox text-3xl block mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">Esta peça ainda não foi vinculada a nenhuma ordem de serviço.</p>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
