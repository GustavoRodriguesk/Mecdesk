<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Ordem de Serviço #{{ $ordem->id }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        {{-- Dados da Ordem --}}
        <div class="mb-6">

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
                {{ $ordem->status_formatado ?? $ordem->status }}
            </p>

        </div>

        {{-- Problema --}}
        <div class="mb-6">

            <h3 class="font-bold text-lg">
                Problema Relatado
            </h3>

            <p class="mt-2">
                {{ $ordem->descricao_problema }}
            </p>

        </div>

        {{-- Adicionar Serviço --}}
        <div class="mb-6">

            <h3 class="font-bold text-lg mb-4">
                Adicionar Serviço
            </h3>

            <form
                action="{{ route('ordens.itens.store', $ordem->id) }}"
                method="POST">

                @csrf

                <div class="mb-4">

                    <label class="block mb-2">
                        Serviço
                    </label>

                    <select
                        name="servico_id"
                        class="w-full border rounded p-3">

                        @foreach($servicos as $servico)

                            <option value="{{ $servico->id }}">

                                {{ $servico->nome }}
                                -
                                R$ {{ number_format($servico->valor_base, 2, ',', '.') }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="block mb-2">
                        Quantidade
                    </label>

                    <input
                        type="number"
                        name="quantidade"
                        value="1"
                        min="1"
                        class="w-full border rounded p-3">

                </div>

                <button
                    type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded">

                    Adicionar Serviço

                </button>

            </form>
            <h3 class="font-bold mt-6">
    Adicionar Peça
</h3>

<form
    action="{{ route('ordens.itens.peca.store', $ordem->id) }}"
    method="POST"
    class="mt-4">

    @csrf

    <div class="mb-4">

        <label>Peça</label>

        <select
            name="peca_id"
            class="w-full border rounded p-3">

            @foreach($pecas as $peca)

                <option value="{{ $peca->id }}">

                    {{ $peca->nome }}
                    -
                    Estoque: {{ $peca->estoque }}
                    -
                    R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="mb-4">

        <label>Quantidade</label>

        <input
            type="number"
            name="quantidade"
            min="1"
            value="1"
            class="w-full border rounded p-3">

    </div>

    <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded">

        Adicionar Peça

    </button>

</form>

        </div>

        {{-- Itens da Ordem --}}
        <div class="mb-6">

            <h3 class="font-bold text-lg mb-4">
                Serviços da Ordem
            </h3>

            @if($ordem->itens->count())

                <table class="w-full border">

                    <thead>

                        <tr class="border-b bg-gray-100">

                            <th class="text-left p-2">
                                Serviço
                            </th>

                            <th class="text-left p-2">
                                Qtd
                            </th>

                            <th class="text-left p-2">
                                Valor Unitário
                            </th>

                            <th class="text-left p-2">
                                Total
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($ordem->itens as $item)

                            <tr class="border-b">

                                <td class="p-2">
                                    {{ $item->descricao }}
                                </td>

                                <td class="p-2">
                                    {{ $item->quantidade }}
                                </td>

                                <td class="p-2">
                                    R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                </td>

                                <td class="p-2">
                                    R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <p>
                    Nenhum serviço adicionado.
                </p>

            @endif

        </div>

        {{-- Total --}}
        <div>

            <h3 class="text-2xl font-bold">

                Total da Ordem:
                R$ {{ number_format($ordem->itens->sum('valor_total'), 2, ',', '.') }}

            </h3>

        </div>

    </div>

</x-app-layout>