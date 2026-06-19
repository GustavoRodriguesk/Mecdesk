<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ordem de Serviço #{{ $ordem->numero_os }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ str_replace('bg-', 'bg-opacity-20 text-', $ordem->status_color) }} {{ $ordem->status_color }}">
                    {{ $ordem->status_formatado }}
                </span>
                <a href="{{ route('ordens.pdf', $ordem->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-colors" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i>
                    PDF
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .data-row { transition: background-color 0.12s ease; }
        .data-row:hover { background-color: #F0F4FA; }
    </style>

    <div class="py-8 px-8 max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Coluna Principal --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Detalhes da OS --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-info-circle text-gray-500"></i>
                        Informações Gerais
                    </h3>
                    @if(auth()->user()->isAdmin() || $ordem->status !== 'concluida')
                    <a href="{{ route('ordens.edit', $ordem->id) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                        Editar OS
                    </a>
                    @endif
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente</span>
                            <span class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                <i class="bi bi-person text-gray-400"></i> {{ $ordem->cliente->nome }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Veículo</span>
                            <span class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                <i class="bi bi-car-front text-gray-400"></i> {{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }} <span class="text-gray-500">({{ $ordem->veiculo->placa }})</span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Problema Relatado</span>
                        <div class="p-3 bg-gray-50 rounded-md text-sm text-gray-700 border border-gray-100">
                            {{ $ordem->descricao_problema }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Itens da Ordem --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-list-check text-gray-500"></i>
                        Serviços e Peças Executados
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    @if($ordem->itens->count())
                        <table class="w-full text-sm">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Descrição</th>
                                    <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Qtd</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">V. Unitário</th>
                                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Total</th>
                                    @if($ordem->status !== 'concluida')
                                    <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($ordem->itens as $item)
                                    <tr class="data-row">
                                        <td class="px-6 py-3 text-gray-900 font-medium">
                                            {{ $item->descricao }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500">
                                            {{ $item->quantidade }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-500">
                                            R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-gray-900">
                                            R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                        </td>
                                        @if($ordem->status !== 'concluida')
                                        <td class="px-4 py-3 text-center">
                                            <form action="{{ route('ordens.itens.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition-colors" title="Remover Item">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900 text-lg">Total da Ordem:</td>
                                    <td class="px-4 py-4 text-right font-bold text-blue-700 text-lg whitespace-nowrap">R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}</td>
                                    @if($ordem->status !== 'concluida')
                                    <td></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="px-6 py-12 text-center text-gray-500">
                            <i class="bi bi-box-seam text-3xl mb-2 block text-gray-300"></i>
                            <p class="text-sm font-medium">Nenhum serviço ou peça adicionado ainda.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Formulários de Adição (Apenas se não estiver concluída) --}}
            @if($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Adicionar Serviço --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                    <h4 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-wrench"></i>
                        Adicionar Serviço
                    </h4>
                    <form action="{{ route('ordens.itens.store', $ordem->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Serviço</label>
                            <select name="servico_id" class="mb-4 w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                                <option value="">Selecione...</option>
                                @foreach($servicos as $servico)
                                    <option value="{{ $servico->id }}">{{ $servico->nome }} (R$ {{ number_format($servico->valor_base, 2, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade</label>
                            <input type="number" name="quantidade" min="1" value="1" class="mb-4 w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 rounded-md transition-colors">
                            Adicionar Serviço
                        </button>
                    </form>
                </div>

                {{-- Adicionar Peça --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                    <h4 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-box-seam"></i>
                        Adicionar Peça
                    </h4>
                    <form action="{{ route('ordens.itens.peca.store', $ordem->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Peça</label>
                            <select name="peca_id" class="mb-4 w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                                <option value="">Selecione...</option>
                                @foreach($pecas as $peca)
                                    <option value="{{ $peca->id }}">{{ $peca->nome }} (R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}) - Estq: {{ $peca->estoque }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade</label>
                            <input type="number" name="quantidade" min="1" value="1" class="mb-4 w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2 rounded-md transition-colors">
                            Adicionar Peça
                        </button>
                    </form>
                </div>

            </div>
            @endif

        </div>

        {{-- Coluna Lateral (Timeline) --}}
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 sticky top-6">
                <h3 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-500"></i>
                    Histórico da Ordem
                </h3>
                
                <div>
                    @forelse($ordem->historicos as $historico)
                        @php
                            $statusText = match($historico->status) {
                                'aberta' => 'Aberta',
                                'em_andamento' => 'Em andamento',
                                'aguardando_aprovacao' => 'Aguardando aprovação',
                                'concluida' => 'Concluída',
                                'cancelada' => 'Cancelada',
                                default => $historico->status
                            };
                        @endphp
                        <div class="relative pl-6">
                            <div class="text-sm font-semibold text-gray-900">{{ $statusText }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $historico->created_at->format('d/m/Y \à\s H:i') }}
                            </div>
                        </div>
                    @empty
                        <div class="pl-6 text-sm text-gray-500">
                            Nenhuma movimentação registrada.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</x-app-layout>