<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Histórico do Veículo
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow">

        <h3 class="text-xl font-bold">
            {{ $veiculo->marca }}
            {{ $veiculo->modelo }}
        </h3>

        <p>
            Placa: {{ $veiculo->placa }}
        </p>

        <p>
            Cliente:
            {{ $veiculo->cliente->nome }}
        </p>

    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">

        <h3 class="font-bold text-lg mb-4">
            Histórico de Ordens
        </h3>

        <table class="w-full">

            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">OS</th>
                    <th class="text-left p-2">Status</th>
                    <th class="text-left p-2">Valor</th>
                    <th class="text-left p-2">Data</th>
                </tr>
            </thead>

            <tbody>

                @foreach($veiculo->ordensServico as $ordem)

                    <tr class="border-b">

                        <td class="p-2">
                            {{ $ordem->numero_os }}
                        </td>

                        <td class="p-2">
                            {{ $ordem->status }}
                        </td>

                        <td class="p-2">
                            R$
                            {{ number_format($ordem->valor_total, 2, ',', '.') }}
                        </td>

                        <td class="p-2">
                            {{ $ordem->created_at->format('d/m/Y') }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</x-app-layout>