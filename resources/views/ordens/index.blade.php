<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ordens de Serviço
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

        {{-- Cabeçalho da seção --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Ordens de Serviço
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Acompanhe e gerencie as OS
                </p>
            </div>

            <a href="{{ route('ordens.create') }}"
               class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-3 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nova Ordem
            </a>
        </div>

        {{-- Card principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Formulário de Filtros Avançados --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-4">
                <form method="GET" action="{{ route('ordens.index') }}" class="flex flex-col gap-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                        {{-- Busca Global --}}
                        <div class="xl:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="OS, cliente, placa..." class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Status --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="">Todos</option>
                                <option value="aberta" @selected(request('status') == 'aberta')>Aberta</option>
                                <option value="aguardando_aprovacao" @selected(request('status') == 'aguardando_aprovacao')>Aguardando Aprovação</option>
                                <option value="concluida" @selected(request('status') == 'concluida')>Concluída</option>
                                <option value="cancelada" @selected(request('status') == 'cancelada')>Cancelada</option>
                            </select>
                        </div>

                        {{-- Cliente --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente</label>
                            <select name="cliente_id" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="">Todos</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(request('cliente_id') == $cliente->id)>{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Data Inicial --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data Início</label>
                            <input type="date" name="inicio" value="{{ request('inicio') }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Data Final --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data Fim</label>
                            <input type="date" name="fim" value="{{ request('fim') }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Ordenação (pode ficar abaixo se faltar espaço, ou no lugar de uma das datas dependendo do layout, mas colocarei na linha) --}}
                        <div class="xl:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ordenar por</label>
                            <select name="sort" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="recentes" @selected(request('sort') == 'recentes' || !request('sort'))>Mais recentes</option>
                                <option value="antigas" @selected(request('sort') == 'antigas')>Mais antigas</option>
                                <option value="valor_maior" @selected(request('sort') == 'valor_maior')>Maior valor</option>
                                <option value="valor_menor" @selected(request('sort') == 'valor_menor')>Menor valor</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            @if(request('search') || request('status') || request('cliente_id') || request('inicio') || request('fim'))
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                    </svg>
                                    Filtros ativos
                                </span>
                            @endif
                            @if(isset($ordens) && method_exists($ordens, 'total'))
                                <span class="ml-2 text-gray-500">{{ $ordens->total() }} {{ $ordens->total() === 1 ? 'ordem' : 'ordens' }}</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('ordens.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-200">
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
                <table class="w-full text-sm table-fixed min-w-[800px]">
                    <colgroup>
                        <col style="width: 15%">
                        <col style="width: 25%">
                        <col style="width: 20%">
                        <col style="width: 15%">
                        <col style="width: 25%">
                    </colgroup>
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Nº OS</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Cliente</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Veículo</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($ordens as $ordem)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                        #{{ $ordem->numero_os }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 truncate">
                                    {{ $ordem->cliente->nome }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ordem->status_color }}">
                                        {{ $ordem->status_formatado }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('ordens.show', $ordem->id) }}" class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 whitespace-nowrap">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>

                                        <a href="{{ route('ordens.edit', $ordem->id) }}" class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 whitespace-nowrap">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                        <form action="{{ route('ordens.destroy', $ordem->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir a OS #{{ $ordem->numero_os }}?')">
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @if(request('search') || request('status') || request('cliente_id') || request('inicio') || request('fim'))
                                            <p class="text-sm font-medium text-gray-500">Nenhuma ordem encontrada com os filtros aplicados</p>
                                            <a href="{{ route('ordens.index') }}" class="text-xs text-blue-600 hover:underline">Limpar filtros e ver todas</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhuma ordem de serviço cadastrada ainda</p>
                                            <a href="{{ route('ordens.create') }}" class="text-xs text-blue-600 hover:underline">Criar a primeira OS</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if(isset($ordens) && method_exists($ordens, 'hasPages') && $ordens->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $ordens->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>