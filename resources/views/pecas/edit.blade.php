<form action="{{ route('pecas.update', $peca->id) }}" method="POST">

    @csrf
    @method('PUT')

    <input type="text"
           name="nome"
           value="{{ $peca->nome }}">

    <input type="text"
           name="codigo"
           value="{{ $peca->codigo }}">

    <input type="number"
           step="0.01"
           name="valor_unitario"
           value="{{ $peca->valor_unitario }}">

    <input type="number"
           name="estoque"
           value="{{ $peca->estoque }}">

</form>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <form action="{{ route('pecas.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label>Nome</label>

                <input
                    type="text"
                    name="nome"
                    class="w-full border rounded p-3"
                    value="{{ $peca->nome }}">

            </div>

            <div class="mb-4">

                <label>Código</label>

                <input
                    type="text"
                    name="codigo"
                    class="w-full border rounded p-3"
                    value="{{ $peca->codigo }}">

            </div>

            <div class="mb-4">

                <label>Valor Unitário</label>

                <input
                    type="number"
                    step="0.01"
                    name="valor_unitario"
                    class="w-full border rounded p-3"
                    value="{{ $peca->valor_unitario }}">

            </div>

            <div class="mb-4">

                <label>Estoque</label>

                <input
                    type="number"
                    name="estoque"
                    value="0"
                    min="0"
                    class="w-full border rounded p-3">

            </div>

            <button
                class="bg-green-600 text-white px-4 py-2 rounded">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>