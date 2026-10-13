<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="bi bi-box-seam text-blue-600 text-lg"></i>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                Peças
            </h2>
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

        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        @media (prefers-reduced-motion: reduce) {

            .data-row,
            .btn-action {
                transition: none;
            }
        }
    </style>

    <div class="w-full">

        {{-- Card principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Filtros --}}
            @php
                $filtrosAvancadosAtivos = request('valor_min') || request('valor_max');
                $temFiltros =
                    request('search') ||
                    request('nome') ||
                    request('codigo') ||
                    $filtrosAvancadosAtivos;
                $qtdAvancados = collect(['valor_min', 'valor_max'])->filter(fn($k) => request($k))->count();
                $inputClass =
                    'search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-150';
            @endphp
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/70"
                x-data="{ maisFiltros: {{ $filtrosAvancadosAtivos ? 'true' : 'false' }} }">
                <form method="GET" action="{{ route('pecas.index') }}" class="space-y-3">

                    {{-- Linha principal: busca + pesquisar --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        {{-- Busca Global --}}
                        <div class="relative flex-1 min-w-0">
                            <label for="filtro-search" class="sr-only">Buscar</label>
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <i class="bi bi-search text-sm"></i>
                            </span>
                            <input id="filtro-search" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nome, marca ou código..."
                                class="search-input w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-150">
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Mais filtros --}}
                            <button type="button" @click="maisFiltros = !maisFiltros"
                                :aria-expanded="maisFiltros.toString()" title="Mais filtros"
                                :class="maisFiltros ? 'bg-blue-50 border-blue-300 text-blue-700' :
                                    'bg-white border-gray-300 text-gray-600 hover:bg-gray-100'"
                                class="relative h-[38px] px-3 text-sm font-medium border rounded-lg transition-colors duration-150 flex items-center gap-1.5 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <i class="bi bi-sliders"></i>
                                <span>Filtros</span>
                                @if ($qtdAvancados > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-semibold text-white bg-blue-600 rounded-full">
                                        {{ $qtdAvancados }}
                                    </span>
                                @endif
                            </button>

                            {{-- Pesquisar --}}
                            <button type="submit" title="Pesquisar" aria-label="Pesquisar"
                                class="h-[38px] w-[38px] shrink-0 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-150 flex items-center justify-center shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Filtros avançados (recolhíveis) --}}
                    <div x-show="maisFiltros" x-transition.opacity.duration.150ms
                        @if (!$filtrosAvancadosAtivos) style="display: none" @endif
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        {{-- Valor Mínimo --}}
                        <div>
                            <label for="filtro-valor-min" class="block text-xs font-medium text-gray-500 mb-1">Valor
                                Mínimo</label>
                            <input id="filtro-valor-min" type="number" step="0.01" name="valor_min"
                                value="{{ request('valor_min') }}" placeholder="R$ 0,00" class="{{ $inputClass }}">
                        </div>

                        {{-- Valor Máximo --}}
                        <div>
                            <label for="filtro-valor-max" class="block text-xs font-medium text-gray-500 mb-1">Valor
                                Máximo</label>
                            <input id="filtro-valor-max" type="number" step="0.01" name="valor_max"
                                value="{{ request('valor_max') }}" placeholder="R$ 0,00" class="{{ $inputClass }}">
                        </div>
                    </div>

                    {{-- Indicadores de Filtros Ativos e Contagem --}}
                    @if ($temFiltros)
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200/70 text-xs">
                            <div class="flex items-center gap-2 text-gray-500">
                                <i class="bi bi-funnel-fill text-blue-600"></i>
                                @if (isset($pecas) && method_exists($pecas, 'total'))
                                    <span>
                                        <strong class="text-gray-800">{{ $pecas->total() }}</strong>
                                        {{ $pecas->total() === 1 ? 'resultado encontrado' : 'resultados encontrados' }}
                                    </span>
                                @else
                                    <span>Filtros aplicados</span>
                                @endif
                            </div>

                            <a href="{{ route('pecas.index') }}"
                                class="text-gray-500 hover:text-red-600 font-medium flex items-center gap-1 transition-colors">
                                <i class="bi bi-x-lg"></i>
                                Limpar filtros
                            </a>
                        </div>
                    @endif

                </form>
            </div>

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed min-w-[700px]">
                    <colgroup>
                        @if (auth()->user()->empresa?->hasControleEstoque())
                            <col style="width: 40%">
                            <col style="width: 18%">
                            <col style="width: 18%">
                            <col style="width: 12%">
                            <col style="width: 12%">
                        @else
                            <col style="width: 48%">
                            <col style="width: 20%">
                            <col style="width: 20%">
                            <col style="width: 12%">
                        @endif
                    </colgroup>
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Peça / Marca</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Código / Barras</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Preços (Venda / Custo)</th>
                            @if (auth()->user()->empresa?->hasControleEstoque())
                                <th
                                    class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                    Estoque</th>
                            @endif
                            <th
                                class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($pecas as $peca)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 truncate">
                                        {{ $peca->nome }}
                                    </div>
                                    @if ($peca->marca)
                                        <div class="inline-flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                                            <i class="bi bi-tag text-[11px] text-blue-500"></i>
                                            <span>{{ $peca->marca }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 items-start">
                                        @if ($peca->codigo)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $peca->codigo }}
                                            </span>
                                        @endif
                                        @if ($peca->codigo_barras)
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] text-gray-500 font-mono"
                                                title="Código de Barras">
                                                <i class="bi bi-upc-scan text-gray-400"></i> {{ $peca->codigo_barras }}
                                            </span>
                                        @endif
                                        @if (!$peca->codigo && !$peca->codigo_barras)
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 tabular-nums">
                                    <div class="font-semibold text-gray-900">
                                        R$
                                        {{ number_format($peca->preco_venda ?? $peca->valor_unitario, 2, ',', '.') }}
                                    </div>
                                    @if ($peca->preco_custo > 0)
                                        <div class="text-[11px] text-gray-400">
                                            Custo: R$ {{ number_format($peca->preco_custo, 2, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                @if (auth()->user()->empresa?->hasControleEstoque())
                                    <td class="px-6 py-4 text-gray-500 tabular-nums">
                                        <span
                                            class="font-medium {{ $peca->estoque <= 0 ? 'text-red-600' : ($peca->estoque < 5 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ $peca->estoque }}
                                        </span>
                                    </td>
                                @endif

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pecas.show', $peca->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                                            title="Visualizar">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>

                                        <a href="{{ route('pecas.edit', $peca->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                            title="Editar">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>

                                        @if (auth()->user()->canDelete())
                                            <form action="{{ route('pecas.destroy', $peca->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir {{ addslashes($peca->nome) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-action inline-flex items-center justify-center w-8 h-8 text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                                                    title="Excluir">
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->empresa?->hasControleEstoque() ? 5 : 4 }}"
                                    class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        @if (request('search') || request('nome') || request('codigo') || request('estoque_min') || request('estoque_max'))
                                            <p class="text-sm font-medium text-gray-500">Nenhuma peça encontrada com os
                                                filtros aplicados</p>
                                            <a href="{{ route('pecas.index') }}"
                                                class="text-xs text-blue-600 hover:underline">Limpar filtros e ver
                                                todas</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhuma peça cadastrada ainda
                                            </p>
                                            <a href="{{ route('pecas.create') }}"
                                                class="text-xs text-blue-600 hover:underline">Cadastrar primeira
                                                peça</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if (isset($pecas) && method_exists($pecas, 'hasPages') && $pecas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $pecas->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
