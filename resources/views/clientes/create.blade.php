<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Clientes
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Novo Cliente
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Preencha os dados abaixo para cadastrar um novo cliente
                </p>
            </div>
            
            <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('clientes.store') }}" method="POST" class="space-y-5">

                @csrf

                <div class="mb-6">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nome
                    </label>
                    <input type="text"
                           name="nome"
                           placeholder="Ex: João da Silva"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                           required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            CPF/CNPJ
                        </label>
                        <input type="text"
                               id="cpf_cnpj"
                               name="cpf_cnpj"
                               placeholder="Apenas números"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>

                    <div class="mb-6">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Telefone
                        </label>
                        <input type="text"
                               id="telefone"
                               name="telefone"
                               placeholder="(00) 00000-0000"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input type="email"
                           name="email"
                           placeholder="Ex: contato@email.com"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Endereço
                    </label>
                    <textarea name="endereco"
                              placeholder="Endereço completo"
                              rows="3"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('clientes.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Salvar Cliente
                    </button>
                </div>

            </form>

        </div>

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