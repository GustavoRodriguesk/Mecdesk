<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Novo Serviço
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <form action="{{ route('servicos.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label>Nome</label>

                <input
                    type="text"
                    name="nome"
                    class="w-full border rounded p-3">

            </div>

            <div class="mb-4">

                <label>Descrição</label>

                <textarea
                    name="descricao"
                    class="w-full border rounded p-3"></textarea>

            </div>

            <div class="mb-4">

                <label>Valor Padrão</label>

                <input
                    type="number"
                    step="0.01"
                    name="valor_base"
                    class="w-full border rounded p-3">

            </div>

            <button
                class="bg-green-600 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>