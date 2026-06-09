<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Ordem de Serviço #{{ $ordem->numero_os }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        {{-- Dados da Ordem --}}
        <div class="mb-6 border-b pb-4">

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

                <span class="px-2 py-1 rounded text-sm {{ $ordem->status_color }}">
                    {{ $ordem->status_formatado }}
                </span>
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
        <div class="mb-8">

            <h3 class="font-bold text-lg mb-4">
                Adicionar Serviço
            </h3>

            <form
                action="{{ route('ordens.itens.store', $ordem->id) }}"
                method="POST">

                @csrf

                <div class="mb-4">

                    <label>Serviço</label>

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
                    class="bg-green-600 text-white px-4 py-2 rounded">

                    Adicionar Serviço

                </button>

            </form>

        </div>

        {{-- Adicionar Peça --}}
        <div class="mb-8">

            <h3 class="font-bold text-lg mb-4">
                Adicionar Peça
            </h3>

            <form
                action="{{ route('ordens.itens.peca.store', $ordem->id) }}"
                method="POST">

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

        {{-- Itens --}}
        <div class="mb-8">

            <h3 class="font-bold text-lg mb-4">
                Itens da Ordem
            </h3>

            @if($ordem->itens->count())

                <table class="w-full border">

                    <thead>

                        <tr class="bg-gray-100 border-b">

                            <th class="p-2 text-left">Descrição</th>
                            <th class="p-2 text-left">Qtd</th>
                            <th class="p-2 text-left">Valor Unitário</th>
                            <th class="p-2 text-left">Total</th>
                            <th class="p-2 text-left">Ações</th>

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

                                <td class="p-2">

                                    <form
                                        action="{{ route('ordens.itens.destroy', $item->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="bg-red-600 text-white px-3 py-1 rounded">

                                            Remover

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <p>Nenhum item adicionado.</p>

            @endif

        </div>

        {{-- Timeline --}}
        <div class="mb-8">

            <h3 class="font-bold text-lg mb-4">
                Histórico da Ordem
            </h3>

            @forelse($ordem->historicos as $historico)

                <div class="border-l-4 pl-4 mb-4

                    @if($historico->status == 'aberta')
                        border-blue-500
                    @elseif($historico->status == 'em_andamento')
                        border-yellow-500
                    @elseif($historico->status == 'concluida')
                        border-green-500
                    @elseif($historico->status == 'cancelada')
                        border-red-500
                    @else
                        border-gray-500
                    @endif
                ">

                    <div class="font-semibold">

                        {{ match($historico->status) {
                            'aberta' => 'Aberta',
                            'em_andamento' => 'Em andamento',
                            'aguardando_aprovacao' => 'Aguardando aprovação',
                            'concluida' => 'Concluída',
                            'cancelada' => 'Cancelada',
                            default => $historico->status
                        } }}

                    </div>

                    <small class="text-gray-500">
                        {{ $historico->created_at->format('d/m/Y H:i') }}
                    </small>

                </div>

            @empty

                <p class="text-gray-500">
                    Nenhuma movimentação registrada.
                </p>

            @endforelse

        </div>

        {{-- Total --}}
        <div class="border-t pt-6">

            <a
                href="{{ route('ordens.pdf', $ordem->id) }}"
                class="bg-red-600 text-white px-4 py-2 rounded">

                Gerar PDF

            </a>

            <h3 class="text-2xl font-bold mt-4">

                Total da Ordem:
                R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}

            </h3>

        </div>

    </div>

</x-app-layout>