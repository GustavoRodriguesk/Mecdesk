<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="bi bi-tools text-blue-600 text-lg"></i>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                Serviços
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
                $temFiltros = request('search') || $filtrosAvancadosAtivos;
                $qtdAvancados = collect(['valor_min', 'valor_max'])->filter(fn($k) => request($k))->count();
                $inputClass =
                    'search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-150';
            @endphp
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/70"
                x-data="{ maisFiltros: {{ $filtrosAvancadosAtivos ? 'true' : 'false' }} }">
                <form method="GET" action="{{ route('servicos.index') }}" class="space-y-3">

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
                                placeholder="Buscar por nome do serviço..."
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
                                @if (isset($servicos) && method_exists($servicos, 'total'))
                                    <span>
                                        <strong class="text-gray-800">{{ $servicos->total() }}</strong>
                                        {{ $servicos->total() === 1 ? 'resultado encontrado' : 'resultados encontrados' }}
                                    </span>
                                @else
                                    <span>Filtros aplicados</span>
                                @endif
                            </div>

                            <a href="{{ route('servicos.index') }}"
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
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col style="width: 55%">
                        <col style="width: 27%">
                        <col style="width: 18%">
                    </colgroup>
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Nome</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Valor Base</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($servicos as $servico)
                            <tr class="data-row">
                                <td class="px-6 py-4 font-medium text-gray-900 truncate">
                                    {{ $servico->nome }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 tabular-nums">
                                    R$ {{ number_format($servico->valor_base, 2, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('servicos.show', $servico->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                                            title="Visualizar">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>

                                        <a href="{{ route('servicos.edit', $servico->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                            title="Editar">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>

                                        @if (auth()->user()->canDelete())
                                            <form action="{{ route('servicos.destroy', $servico->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir o serviço {{ addslashes($servico->nome) }}?')">
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
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                        @if (request('search') || request('valor_min') || request('valor_max'))
                                            <p class="text-sm font-medium text-gray-500">Nenhum serviço encontrado com
                                                os filtros aplicados</p>
                                            <a href="{{ route('servicos.index') }}"
                                                class="text-xs text-blue-600 hover:underline">Limpar filtros e ver
                                                todos</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhum serviço cadastrado ainda
                                            </p>
                                            <a href="{{ route('servicos.create') }}"
                                                class="text-xs text-blue-600 hover:underline">Cadastrar o primeiro
                                                serviço</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if (isset($servicos) && method_exists($servicos, 'hasPages') && $servicos->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $servicos->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
