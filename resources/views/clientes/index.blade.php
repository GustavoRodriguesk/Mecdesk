<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Clientes
        </h2>

    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Lista de Clientes
            </h1>

            <a href="{{ route('clientes.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Novo Cliente

            </a>

        </div>
<form method="GET"
      action="{{ route('clientes.index') }}"
      class="mb-6 flex gap-2">

    <input type="text"
           name="search"
           value="{{ $search }}"
           placeholder="Buscar cliente..."
           class="border rounded-lg px-4 py-2 w-full">

    <button type="submit"
            class="bg-gray-800 text-white px-4 py-2 rounded-lg">

        Buscar

    </button>

</form>
        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left p-2">Nome</th>
                    <th class="text-left p-2">Telefone</th>
                    <th class="text-left p-2">Ações</th>

                </tr>

            </thead>

            <tbody>

                @foreach($clientes as $cliente)

                    <tr class="border-b">

                        <td class="p-2">
                            {{ $cliente->nome }}
                        </td>

                        <td class="p-2">
                            {{ $cliente->telefone }}
                        </td>

                        <td class="p-2 flex gap-2">

                            <a href="{{ route('clientes.edit', $cliente->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded">

                                Editar

                            </a>

                            <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
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

    {{ $clientes->links() }}

</div>
    </div>

</x-app-layout>