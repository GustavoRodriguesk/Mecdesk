<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            {{ $peca->nome }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <p>
            <strong>Nome:</strong>
            {{ $peca->nome }}
        </p>

        <p class="mt-2">
            <strong>Código:</strong>
            {{ $peca->codigo }}
        </p>

        <p class="mt-2">
            <strong>Valor Unitário:</strong>
            R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}
        </p>

        <p class="mt-2">
            <strong>Estoque:</strong>
            {{ $peca->estoque }}
        </p>

    </div>

</x-app-layout>