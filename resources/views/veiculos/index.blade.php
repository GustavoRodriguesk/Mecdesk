<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Veículos
        </h2>

    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Lista de Veículos
            </h1>

            <a href="{{ route('veiculos.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                Novo Veículo

            </a>
            
        

</form>

        </div>

        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">

    <form
        method="GET"
        action="{{ route('veiculos.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Busca Global --}}
            <div>

                <label class="block text-sm font-medium mb-1">
                    Busca rápida
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Placa, marca, modelo ou cliente..."
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            {{-- Placa --}}
            <div>

                <label class="block text-sm font-medium mb-1">
                    Placa
                </label>

                <input
                    type="text"
                    name="placa"
                    value="{{ request('placa') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            {{-- Marca --}}
            <div>

                <label class="block text-sm font-medium mb-1">
                    Marca
                </label>

                <input
                    type="text"
                    name="marca"
                    value="{{ request('marca') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            {{-- Modelo --}}
            <div>

                <label class="block text-sm font-medium mb-1">
                    Modelo
                </label>

                <input
                    type="text"
                    name="modelo"
                    value="{{ request('modelo') }}"
                    class="w-full border rounded-lg px-4 py-2">

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
                        Todos
                    </option>

                    @foreach($clientes as $cliente)

                        <option
                            value="{{ $cliente->id }}"
                            @selected(
                                request('cliente_id') == $cliente->id
                            )>

                            {{ $cliente->nome }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="flex gap-2 mt-4">

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg">

                Filtrar

            </button>

            <a
                href="{{ route('veiculos.index') }}"
                class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                Limpar

            </a>

        </div>

    </form>

</div>
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="border-b dark:border-gray-700">

                    <tr>

                        <th class="text-left p-3">
                            Cliente
                        </th>

                        <th class="text-left p-3">
                            Marca
                        </th>

                        <th class="text-left p-3">
                            Modelo
                        </th>

                        <th class="text-left p-3">
                            Ano
                        </th>

                        <th class="text-left p-3">
                            Placa
                        </th>

                        <th class="text-left p-3">
                            KM
                        </th>

                        <th class="text-left p-3">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($veiculos as $veiculo)

                        <tr class="border-b dark:border-gray-700">

                            <td class="p-3">
                                {{ $veiculo->cliente->nome }}
                            </td>

                            <td class="p-3">
                                {{ $veiculo->marca }}
                            </td>

                            <td class="p-3">
                                {{ $veiculo->modelo }}
                            </td>

                            <td class="p-3">
                                {{ $veiculo->ano }}
                            </td>

                            <td class="p-3">
                                {{ $veiculo->placa }}
                            </td>

                            <td class="p-3">
                                {{ number_format($veiculo->quilometragem, 0, ',', '.') }}
                            </td>

                            <td class="p-3">

                                <div class="flex gap-2">

                                    <a href="{{ route('veiculos.edit', $veiculo->id) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

                                        Editar

                                    </a>

                                    <form action="{{ route('veiculos.destroy', $veiculo->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Deseja realmente excluir este veículo?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                                            Excluir

                                        </button>
                                        <a href="{{ route('veiculos.show', $veiculo->id) }}"
   class="bg-blue-500 text-white px-3 py-1 rounded">
    Histórico
</a>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center p-6 text-gray-500">

                                Nenhum veículo cadastrado.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">

            {{ $veiculos->links() }}

        </div>

    </div>

</x-app-layout>