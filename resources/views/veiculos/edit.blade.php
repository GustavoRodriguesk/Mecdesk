<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Veículo
        </h2>

    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <form action="{{ route('veiculos.update', $veiculo->id) }}"
              method="POST"
              class="space-y-4">

            @csrf
            @method('PUT')

            <div>

                <label class="block mb-1 font-medium">
                    Cliente
                </label>

                <select name="cliente_id"
                        class="w-full border rounded-lg p-3">

                    @foreach($clientes as $cliente)

                        <option value="{{ $cliente->id }}"
                            {{ $veiculo->cliente_id == $cliente->id ? 'selected' : '' }}>

                            {{ $cliente->nome }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Marca
                </label>

                <input type="text"
                       name="marca"
                       value="{{ old('marca', $veiculo->marca) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Modelo
                </label>

                <input type="text"
                       name="modelo"
                       value="{{ old('modelo', $veiculo->modelo) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Ano
                </label>

                <input type="number"
                       name="ano"
                       value="{{ old('ano', $veiculo->ano) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Placa
                </label>

                <input type="text"
                       name="placa"
                       value="{{ old('placa', $veiculo->placa) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Cor
                </label>

                <input type="text"
                       name="cor"
                       value="{{ old('cor', $veiculo->cor) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Quilometragem
                </label>

                <input type="number"
                       name="quilometragem"
                       value="{{ old('quilometragem', $veiculo->quilometragem) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            <div class="flex gap-2">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                    Atualizar

                </button>

                <a href="{{ route('veiculos.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</x-app-layout>