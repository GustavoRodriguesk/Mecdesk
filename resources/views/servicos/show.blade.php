<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('servicos.index') }}"
                class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-tools"></i>
                <span>Serviços</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5 truncate max-w-xs sm:max-w-md">
                <i class="bi bi-info-circle text-blue-600"></i>
                Detalhes do Serviço: {{ $servico->nome }}
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
                        <i class="bi bi-wrench-adjustable"></i>
                    </span>
                    <span>{{ $servico->nome }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Consulta de especificações, precificação padrão e histórico de realizações
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('servicos.edit', $servico->id) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="bi bi-pencil-square"></i>
                    <span>Editar Serviço</span>
                </a>

                <a href="{{ route('servicos.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Card: Dados do Serviço --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-500"></i>
                    Informações do Serviço
                </h3>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                {{-- Nome --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 sm:col-span-2">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nome do Serviço</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $servico->nome }}</span>
                </div>

                {{-- Valor Base --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor Base</span>
                    <span class="text-base font-bold text-emerald-600 tabular-nums">
                        R$ {{ number_format($servico->valor_base, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Descrição --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 sm:col-span-2">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Descrição / Observações</span>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $servico->descricao ?: 'Nenhuma descrição cadastrada.' }}</p>
                </div>

                {{-- Cadastrado em --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data de Cadastro</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $servico->created_at ? $servico->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Card: Histórico de Realização em Ordens de Serviço --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-500"></i>
                    Histórico de Realização em Ordens de Serviço
                </h3>
                <span class="text-xs text-gray-500 font-medium">
                    {{ $servico->ordemServicoItens->count() }} {{ $servico->ordemServicoItens->count() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            @if ($servico->ordemServicoItens->isNotEmpty())
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
                            @foreach ($servico->ordemServicoItens as $item)
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
                                                {{ $item->ordemServico->veiculo->marca }} {{ $item->ordemServico->veiculo->modelo }}
                                                <span class="text-xs font-mono text-gray-400">({{ $item->ordemServico->veiculo->placa }})</span>
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
                                            R$ {{ number_format($item->valor_total ?? ($item->quantidade * $item->valor_unitario), 2, ',', '.') }}
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
                    <p class="text-sm font-medium">Este serviço ainda não foi vinculado a nenhuma ordem de serviço.</p>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
