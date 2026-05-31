<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            {{ $cliente->nome }}
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow">

        <p><strong>Telefone:</strong> {{ $cliente->telefone }}</p>
        <p><strong>Email:</strong> {{ $cliente->email }}</p>
        <p><strong>CPF/CNPJ:</strong> {{ $cliente->cpf_cnpj }}</p>

    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">

        <div class="flex justify-between items-center mb-4">

            <h3 class="text-xl font-bold">
                Veículos
            </h3>

            <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}"
               class="bg-green-600 text-white px-4 py-2 rounded">

                Novo Veículo

            </a>

        </div>

        @forelse($cliente->veiculos as $veiculo)

            <div class="border-b py-3">

                <strong>
                    {{ $veiculo->marca }}
                    {{ $veiculo->modelo }}
                </strong>

                <br>

                Placa: {{ $veiculo->placa }}

                <br>

                Ano: {{ $veiculo->ano }}

            </div>

        @empty

            <p>Nenhum veículo cadastrado.</p>

        @endforelse

    </div>

</x-app-layout>