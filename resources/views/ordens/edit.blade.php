<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Ordem de Serviço
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <form action="{{ route('ordens.update', $ordem->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="block mb-2 text-gray-700 dark:text-gray-300">
                    Cliente
                </label>

                <select name="cliente_id"
                        class="w-full border rounded p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    @foreach($clientes as $cliente)

                        <option value="{{ $cliente->id }}"
                            {{ $ordem->cliente_id == $cliente->id ? 'selected' : '' }}>

                            {{ $cliente->nome }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="block mb-2 text-gray-700 dark:text-gray-300">
                    Veículo
                </label>

                <select name="veiculo_id"
                        class="w-full border rounded p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    @foreach($veiculos as $veiculo)

                        <option value="{{ $veiculo->id }}"
                            {{ $ordem->veiculo_id == $veiculo->id ? 'selected' : '' }}>

                            {{ $veiculo->marca }}
                            {{ $veiculo->modelo }}
                            -
                            {{ $veiculo->placa }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="block mb-2 text-gray-700 dark:text-gray-300">
                    Problema Relatado
                </label>

                <textarea
                    name="descricao_problema"
                    rows="5"
                    class="w-full border rounded p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('descricao_problema', $ordem->descricao_problema) }}</textarea>

            </div>

            <div class="mb-6">

                <label class="block mb-2 text-gray-700 dark:text-gray-300">
                    Status
                </label>

                <select name="status"
                        class="w-full border rounded p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <option value="aberta" {{ $ordem->status == 'aberta' ? 'selected' : '' }}>
                        Aberta
                    </option>

                    <option value="em_andamento" {{ $ordem->status == 'em_andamento' ? 'selected' : '' }}>
                        Em andamento
                    </option>

                    <option value="aguardando_aprovacao" {{ $ordem->status == 'aguardando_aprovacao' ? 'selected' : '' }}>
                        Aguardando aprovação
                    </option>

                    <option value="concluida" {{ $ordem->status == 'concluida' ? 'selected' : '' }}>
                        Concluída
                    </option>

                    <option value="cancelada" {{ $ordem->status == 'cancelada' ? 'selected' : '' }}>
                        Cancelada
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>