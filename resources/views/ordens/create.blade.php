<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Nova Ordem de Serviço
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow">

        <form action="{{ route('ordens.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label>Cliente</label>

                    <select id="cliente_id" name="cliente_id">                        class="w-full border rounded p-3">

                    @foreach($clientes as $cliente)

                        <option value="{{ $cliente->id }}">
                            {{ $cliente->nome }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label>Veículo</label>

                <select id="veiculo_id" name="veiculo_id">                        class="w-full border rounded p-3">

                    @foreach($veiculos as $veiculo)

                        <option value="{{ $veiculo->id }}">

                            {{ $veiculo->marca }}
                            {{ $veiculo->modelo }}
                            -
                            {{ $veiculo->placa }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label>Problema Relatado</label>

                <textarea
                    name="descricao_problema"
                    rows="5"
                    class="w-full border rounded p-3"></textarea>

            </div>

            <button
                class="bg-green-600 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>
<script>
document.getElementById('cliente_id').addEventListener('change', function () {

    let clienteId = this.value;

    fetch(`/clientes/${clienteId}/veiculos`)
        .then(response => response.json())
        .then(data => {

            let select = document.getElementById('veiculo_id');

            select.innerHTML = '';

            data.forEach(veiculo => {

                select.innerHTML += `
                    <option value="${veiculo.id}">
                        ${veiculo.marca} ${veiculo.modelo} - ${veiculo.placa}
                    </option>
                `;

            });

        });

});
</script>