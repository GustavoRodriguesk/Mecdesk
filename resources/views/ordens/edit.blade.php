<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Editar Ordem de Serviço
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow">

        <form action="{{ route('ordens.update', $ordem->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label>Cliente</label>

                <select name="cliente_id"
                        class="w-full border rounded p-3">

                    @foreach($clientes as $cliente)
<option value="{{ $cliente->id }}"
    {{ $ordem->cliente_id == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label>Veículo</label>

                <select name="veiculo_id"
                        class="w-full border rounded p-3">

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

                <label>Problema Relatado</label>
<textarea
    name="descricao_problema"
    rows="5"
    class="w-full border rounded p-3"></textarea>

            </div>
<div class="mb-4">

    <label>Status</label>

    <select name="status"
            class="w-full border rounded p-3">

        <option value="aberta"
            {{ $ordem->status == 'aberta' ? 'selected' : '' }}>
            Aberta
        </option>

        <option value="em_andamento"
            {{ $ordem->status == 'em_andamento' ? 'selected' : '' }}>
            Em andamento
        </option>

        <option value="aguardando_aprovacao"
            {{ $ordem->status == 'aguardando_aprovacao' ? 'selected' : '' }}>
            Aguardando aprovação
        </option>

        <option value="concluida"
            {{ $ordem->status == 'concluida' ? 'selected' : '' }}>
            Concluída
        </option>

        <option value="cancelada"
            {{ $ordem->status == 'cancelada' ? 'selected' : '' }}>
            Cancelada
        </option>

    </select>

</div>
            <button
                class="bg-green-600 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>