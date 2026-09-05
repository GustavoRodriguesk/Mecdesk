<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Clientes
        </h2>
    </x-slot>

    <style>
        .cliente-row {
            transition: background-color 0.12s ease;
        }

        .cliente-row:hover {
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

            .cliente-row,
            .btn-action {
                transition: none;
            }
        }
    </style>

    <div class="w-full">
        {{-- Card principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Barra de busca e metadados --}}
            <div
                class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                {{-- Formulário de busca --}}
                <form method="GET" action="{{ route('clientes.index') }}" class="flex items-center gap-2 w-full sm:w-96">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Buscar por nome ou CPF/CNPJ..."
                            class="search-input w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors duration-150">
                    </div>
                    <button type="submit"
                        class="shrink-0 px-4 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                        Buscar
                    </button>
                </form>

                {{-- Contador de resultados --}}
                <div class="flex items-center gap-2 text-sm text-gray-400 shrink-0">
                    @if ($search)
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                            </svg>
                            Filtro ativo: "{{ $search }}"
                        </span>
                        <a href="{{ route('clientes.index') }}"
                            class="text-gray-400 hover:text-gray-600 text-xs transition-colors">
                            Limpar
                        </a>
                        <span class="text-gray-200">|</span>
                    @endif
                    <span>{{ $clientes->total() }} {{ $clientes->total() === 1 ? 'cliente' : 'clientes' }}</span>
                </div>

            </div>

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col style="width: 32%">
                        <col style="width: 22%">
                        <col style="width: 22%">
                        <col style="width: 24%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Nome
                            </th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                CPF/CNPJ
                            </th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Telefone
                            </th>
                            <th
                                class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($clientes as $cliente)
                            <tr class="cliente-row">

                                {{-- Nome --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold uppercase select-none">
                                            {{ mb_substr($cliente->nome, 0, 1) }}
                                        </span>
                                        <span class="font-medium text-gray-900 truncate">
                                            {{ $cliente->nome }}
                                        </span>
                                    </div>
                                </td>

                                {{-- CPF/CNPJ --}}
                                <td class="px-6 py-4 text-gray-500 tabular-nums">
                                    {{ $cliente->cpf_cnpj_formatado ?: '-' }}
                                </td>

                                {{-- Telefone --}}
                                <td class="px-6 py-4 text-gray-500 tabular-nums">
                                    {{ $cliente->telefone_formatado ?: $cliente->telefone ?? '-' }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('clientes.edit', $cliente->id) }}"
                                            class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 whitespace-nowrap">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </a>

                                        {{-- Excluir --}}
                                        @if (auth()->user()->canDelete())
                                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir {{ addslashes($cliente->nome) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-action inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 whitespace-nowrap">
                                                    <i class="bi bi-trash"></i>
                                                    Excluir
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        @if ($search)
                                            <p class="text-sm font-medium text-gray-500">Nenhum cliente encontrado para
                                                "{{ $search }}"</p>
                                            <a href="{{ route('clientes.index') }}"
                                                class="text-xs text-blue-600 hover:underline">Limpar filtro e ver
                                                todos</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhum cliente cadastrado ainda
                                            </p>
                                            <a href="{{ route('clientes.create') }}"
                                                class="text-xs text-blue-600 hover:underline">Cadastrar primeiro
                                                cliente</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if ($clientes->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $clientes->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
