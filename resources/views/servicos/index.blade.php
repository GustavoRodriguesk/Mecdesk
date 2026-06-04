<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Serviços
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Lista de Serviços
            </h1>

            <a href="{{ route('servicos.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Novo Serviço

            </a>

        </div>

        <table class="w-full">

            <thead>

                <tr>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

                @foreach($servicos as $servico)

                    <tr>

                        <td>{{ $servico->nome }}</td>

                        <td>
                            R$ {{ number_format($servico->valor_base, 2, ',', '.') }}
                        </td>

                        <td class="flex gap-2">

                            <a href="{{ route('servicos.edit', $servico->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded">

                                Editar

                            </a>

                            <form action="{{ route('servicos.destroy', $servico->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-600 text-white px-3 py-1 rounded">

                                    Excluir

                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="mt-6">
            {{ $servicos->links() }}
        </div>

    </div>

</x-app-layout>