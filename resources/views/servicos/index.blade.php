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

            @if(auth()->user()->isAdmin())
            <a href="{{ route('servicos.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Novo Serviço

            </a>
            @endif

        </div>
<div class="bg-gray-50 rounded-lg p-4 mb-6">

    <form method="GET">

        <div class="grid md:grid-cols-4 gap-4">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Busca rápida"
                class="border rounded-lg px-4 py-2">

            <input
                type="number"
                step="0.01"
                name="valor_min"
                value="{{ request('valor_min') }}"
                placeholder="Valor mínimo"
                class="border rounded-lg px-4 py-2">

            <input
                type="number"
                step="0.01"
                name="valor_max"
                value="{{ request('valor_max') }}"
                placeholder="Valor máximo"
                class="border rounded-lg px-4 py-2">

        </div>

        <div class="mt-4 flex gap-2">

            <button
                class="bg-blue-600 text-white px-4 py-2 rounded">

                Filtrar

            </button>

            <a
                href="{{ route('pecas.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">

                Limpar

            </a>

        </div>

    </form>

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

                            @if(auth()->user()->isAdmin())
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
                            @endif

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