<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Ordens de Serviço
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Lista de Ordens
            </h1>

            <a href="{{ route('ordens.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Nova Ordem

            </a>

        </div>

   <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">

    <form method="GET" action="{{ route('ordens.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Busca Geral --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Buscar
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="OS, cliente, placa..."
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">Todos</option>

                    <option value="aberta"
                        @selected(request('status') == 'aberta')>
                        Aberta
                    </option>

                    <option value="aguardando_aprovacao"
                        @selected(request('status') == 'aguardando_aprovacao')>
                        Aguardando Aprovação
                    </option>

                    <option value="concluida"
                        @selected(request('status') == 'concluida')>
                        Concluída
                    </option>

                    <option value="cancelada"
                        @selected(request('status') == 'cancelada')>
                        Cancelada
                    </option>

                </select>
            </div>

            {{-- Cliente --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Cliente
                </label>

                <select
                    name="cliente_id"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">
                        Todos os clientes
                    </option>

                    @foreach($clientes as $cliente)

                        <option
                            value="{{ $cliente->id }}"
                            @selected(request('cliente_id') == $cliente->id)>

                            {{ $cliente->nome }}

                        </option>

                    @endforeach

                </select>
            </div>

            {{-- Ordenação --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Ordenar
                </label>

                <select
                    name="sort"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="recentes">
                        Mais recentes
                    </option>

                    <option value="antigas"
                        @selected(request('sort') == 'antigas')>
                        Mais antigas
                    </option>

                    <option value="valor_maior"
                        @selected(request('sort') == 'valor_maior')>
                        Maior valor
                    </option>

                    <option value="valor_menor"
                        @selected(request('sort') == 'valor_menor')>
                        Menor valor
                    </option>

                </select>
            </div>

            {{-- Data Inicial --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Data Inicial
                </label>

                <input
                    type="date"
                    name="inicio"
                    value="{{ request('inicio') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            {{-- Data Final --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Data Final
                </label>

                <input
                    type="date"
                    name="fim"
                    value="{{ request('fim') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

        </div>

        <div class="flex gap-2 mt-4">

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg">

                Filtrar

            </button>

            <a
                href="{{ route('ordens.index') }}"
                class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                Limpar

            </a>

        </div>

    </form>

</div>
        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left p-2">Cliente</th>
                    <th class="text-left p-2">Veículo</th>
                    <th class="text-left p-2">Numero Os</th>
                    <th class="text-left p-2">Status</th>
                    <th class="text-left p-2">Ações</th>

                </tr>

            </thead>

            <tbody>

                @foreach($ordens as $ordem)

                    <tr class="border-b">

                        <td class="p-2">
                            {{ $ordem->cliente->nome }}
                        </td>

                        <td class="p-2">
                            {{ $ordem->veiculo->marca }}
                            {{ $ordem->veiculo->modelo }}
                        </td>

                        <td class="p-2">
                            {{ $ordem->numero_os }}
                        </td>

   <td class="p-2">

    <span class="{{ $ordem->status_color }} px-3 py-1 rounded-full text-sm">

        {{ $ordem->status_formatado }}

    </span>

</td>

</td>

                        <td class="p-2 flex gap-2">

                            <a href="{{ route('ordens.show', $ordem->id) }}"
                               class="bg-blue-500 text-white px-3 py-1 rounded">
                                Ver
                            </a>

                            <a href="{{ route('ordens.edit', $ordem->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Editar
                            </a>

                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('ordens.destroy', $ordem->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="bg-red-600 text-white px-3 py-1 rounded">
                                    Excluir
                                </button>

                            </form>
                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="mt-6">
            {{ $ordens->links() }}
        </div>

    </div>

</x-app-layout>