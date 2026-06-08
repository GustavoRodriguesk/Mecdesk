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

        <div class="mb-4 flex gap-4">

            <form method="GET"
                action="{{ route('ordens.index') }}"
                class="flex-1">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar OS..."
                    class="border rounded-lg px-4 py-2 w-full">

            </form>

            <form method="GET"
                action="{{ route('ordens.index') }}">

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="border rounded-lg px-3 py-2 w-64">

                    <option value="">Todas as OS</option>

                    <option value="aberta"
                        {{ request('status') == 'aberta' ? 'selected' : '' }}>
                        Abertas
                    </option>

                    <option value="concluida"
                        {{ request('status') == 'concluida' ? 'selected' : '' }}>
                        Concluídas
                    </option>

                    <option value="cancelada"
                        {{ request('status') == 'cancelada' ? 'selected' : '' }}>
                        Canceladas
                    </option>

                    <option value="aguardando_aprovacao"
                        {{ request('status') == 'aguardando_aprovacao' ? 'selected' : '' }}>
                        Aguardando Aprovação
                    </option>

                </select>

            </form>

        </div>
        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left p-2">Cliente</th>
                    <th class="text-left p-2">Veículo</th>
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

                            <form action="{{ route('ordens.destroy', $ordem->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="bg-red-600 text-white px-3 py-1 rounded">
                                    Excluir
                                </button>

                            </form>

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