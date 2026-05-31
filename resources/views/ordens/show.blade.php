<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Ordem #{{ $ordem->id }}
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow">

        <p>
            <strong>Cliente:</strong>
            {{ $ordem->cliente->nome }}
        </p>

        <p>
            <strong>Veículo:</strong>

            {{ $ordem->veiculo->marca }}
            {{ $ordem->veiculo->modelo }}

            ({{ $ordem->veiculo->placa }})
        </p>

        <p>
            <strong>Status:</strong>
            {{ $ordem->status }}
        </p>

        <div class="mt-6">

            <h3 class="font-bold">
                Problema Relatado
            </h3>

            <p class="mt-2">
                {{ $ordem->descricao_problema }}
            </p>

        </div>

    </div>

</x-app-layout>