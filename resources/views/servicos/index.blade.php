<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Serviços
        </h2>
    </x-slot>

    <style>
        .data-row { transition: background-color 0.12s ease; }
        .data-row:hover { background-color: #F0F4FA; }
        .btn-action { transition: opacity 0.12s ease; }
        .btn-action:hover { opacity: 0.85; }
        .search-input:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        @media (prefers-reduced-motion: reduce) {
            .data-row, .btn-action { transition: none; }
        }
    </style>

    <div class="py-8 px-8 max-w-5xl mx-auto">

        {{-- Card principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Formulário de Filtros Avançados --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-4">
                <form method="GET" action="{{ route('servicos.index') }}" class="flex flex-col gap-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Busca Global --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Busca rápida</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do serviço..." class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Valor Mínimo --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor Mínimo</label>
                            <input type="number" step="0.01" name="valor_min" value="{{ request('valor_min') }}" placeholder="R$ 0,00" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Valor Máximo --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor Máximo</label>
                            <input type="number" step="0.01" name="valor_max" value="{{ request('valor_max') }}" placeholder="R$ 0,00" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            @if(request('search') || request('valor_min') || request('valor_max'))
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                    </svg>
                                    Filtros ativos
                                </span>
                            @endif
                            @if(isset($servicos) && method_exists($servicos, 'total'))
                                <span class="ml-2 text-gray-500">{{ $servicos->total() }} {{ $servicos->total() === 1 ? 'serviço' : 'serviços' }}</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('servicos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                Limpar
                            </a>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-700">
                                Filtrar
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col style="width: 50%">
                        <col style="width: 25%">
                        <col style="width: 25%">
                    </colgroup>
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Nome</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Valor Base</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Ações</th>
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
                                    <div class="flex items-center justify-end gap-2">
                                        @if(auth()->user()->isAdmin())
                                        <a href="{{ route('servicos.edit', $servico->id) }}" class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 whitespace-nowrap">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        <form action="{{ route('servicos.destroy', $servico->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o serviço {{ addslashes($servico->nome) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-100 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 whitespace-nowrap">
                                                <i class="bi bi-trash"></i> Excluir
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                        @if(request('search') || request('valor_min') || request('valor_max'))
                                            <p class="text-sm font-medium text-gray-500">Nenhum serviço encontrado com os filtros aplicados</p>
                                            <a href="{{ route('servicos.index') }}" class="text-xs text-blue-600 hover:underline">Limpar filtros e ver todos</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhum serviço cadastrado ainda</p>
                                            @if(auth()->user()->isAdmin())
                                            <a href="{{ route('servicos.create') }}" class="text-xs text-blue-600 hover:underline">Cadastrar o primeiro serviço</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if(isset($servicos) && method_exists($servicos, 'hasPages') && $servicos->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $servicos->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>