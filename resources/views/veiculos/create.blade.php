<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Novo Veículo
        </h2>
    </x-slot>

    <div class="bg-white shadow rounded-lg p-6">

        <form action="{{ route('veiculos.store') }}"
              method="POST"
              class="space-y-4">

            @csrf

            <div>

                <label>Cliente</label>

                <select name="cliente_id"
        class="w-full border rounded p-3">

    @foreach($clientes as $cliente)

        <option value="{{ $cliente->id }}"
            {{ ($clienteId ?? null) == $cliente->id ? 'selected' : '' }}>

            {{ $cliente->nome }}

        </option>

    @endforeach

</select>

            </div>

            <div>
                <label>Marca</label>

                <input type="text"
                       name="marca"
                       class="w-full border rounded p-3">
            </div>

            <div>
                <label>Modelo</label>

                <input type="text"
                       name="modelo"
                       class="w-full border rounded p-3">
            </div>

            <div>
                <label>Ano</label>

                <input type="number"
                       name="ano"
                       class="w-full border rounded p-3">
            </div>

            <div>
                <label>Placa</label>

                <input type="text"
                       name="placa"
                       class="w-full border rounded p-3">
            </div>

            <div>
                <label>Cor</label>

                <input type="text"
                       name="cor"
                       class="w-full border rounded p-3">
            </div>

            <div>
                <label>Quilometragem</label>

                <input type="number"
                       name="quilometragem"
                       value="0"
                       class="w-full border rounded p-3">
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>