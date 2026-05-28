<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Novo Cliente
        </h2>

    </x-slot>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <form action="{{ route('clientes.store') }}"
              method="POST"
              class="space-y-4">

            @csrf

            <div>

                <label class="block mb-1 font-medium">
                    Nome
                </label>

                <input type="text"
                       name="nome"
                       placeholder="Nome"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    CPF/CNPJ
                </label>

                <input type="text"
                       id="cpf_cnpj"
                       name="cpf_cnpj"
                       placeholder="CPF ou CNPJ"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Telefone
                </label>

                <input type="text"
                       id="telefone"
                       name="telefone"
                       placeholder="(00) 00000-0000"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    E-mail
                </label>

                <input type="email"
                       name="email"
                       placeholder="E-mail"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium">
                    Endereço
                </label>

                <textarea name="endereco"
                          placeholder="Endereço"
                          class="w-full border rounded-lg p-3"></textarea>

            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">

                Salvar Cliente

            </button>

        </form>

    </div>

    <script>

    // MÁSCARA TELEFONE
    document.getElementById('telefone').addEventListener('input', function (e) {

        let value = e.target.value.replace(/\D/g, '');

        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');

        value = value.replace(/(\d{5})(\d)/, '$1-$2');

        e.target.value = value;

    });

    // MÁSCARA CPF/CNPJ
    document.getElementById('cpf_cnpj').addEventListener('input', function (e) {

        let value = e.target.value.replace(/\D/g, '');

        if (value.length <= 11) {

            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        } else {

            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');

        }

        e.target.value = value;

    });

    </script>

</x-app-layout>