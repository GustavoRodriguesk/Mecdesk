<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Peças
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Lista de Peças
            </h1>

            <a href="{{ route('pecas.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Nova Peça

            </a>

        </div>

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left p-2">Nome</th>
                    <th class="text-left p-2">Código</th>
                    <th class="text-left p-2">Valor</th>
                    <th class="text-left p-2">Estoque</th>
                    <th class="text-left p-2">Ações</th>

                </tr>

            </thead>

            <tbody>

                @foreach($pecas as $peca)

                    <tr class="border-b">

                        <td class="p-2">{{ $peca->nome }}</td>

                        <td class="p-2">
                            {{ $peca->codigo }}
                        </td>

                        <td class="p-2">
                            R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}
                        </td>

                        <td class="p-2">
                            {{ $peca->estoque }}
                        </td>

                        <td class="p-2 flex gap-2">

                            <a href="{{ route('pecas.show', $peca->id) }}"
                               class="bg-blue-500 text-white px-3 py-1 rounded">
                                Ver
                            </a>

                            <a href="{{ route('pecas.edit', $peca->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Editar
                            </a>

                            <form action="{{ route('pecas.destroy', $peca->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="bg-red-600 text-white px-3 py-1 rounded">
                                    Excluir
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="mt-6">
            {{ $pecas->links() }}
        </div>

    </div>

</x-app-layout>