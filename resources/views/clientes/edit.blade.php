<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Cliente
        </h2>
    </x-slot>

    <div class="w-full space-y-6">

        {{-- Cabeçalho da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span
                        class="shrink-0 inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold uppercase select-none">
                        {{ mb_substr($cliente->nome, 0, 1) }}
                    </span>
                    {{ $cliente->nome }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Visualize e atualize as informações do cliente e gerencie seus veículos
                </p>
            </div>

            <a href="{{ route('clientes.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        {{-- Card de Dados do Cliente --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
            <div class="border-b border-gray-100 pb-4 mb-6 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-person-lines-fill text-gray-500"></i>
                    Dados Cadastrais
                </h3>
            </div>

            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nome Completo
                    </label>
                    <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}"
                        placeholder="Ex: João da Silva" maxlength="50"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                        required>
                    @error('nome')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            CPF/CNPJ
                        </label>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" maxlength="18"
                            value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}" placeholder="Apenas números"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('cpf_cnpj')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Telefone
                        </label>
                        <input type="text" id="telefone" name="telefone" maxlength="15"
                            value="{{ old('telefone', $cliente->telefone) }}" placeholder="(00) 00000-0000"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                        @error('telefone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        E-mail
                    </label>
                    <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                        placeholder="Ex: contato@email.com" maxlength="100"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Endereço
                    </label>
                    <textarea name="endereco" placeholder="Endereço completo" rows="3" maxlength="100"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">{{ old('endereco', $cliente->endereco) }}</textarea>
                    @error('endereco')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('clientes.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Atualizar Cliente
                    </button>
                </div>
            </form>
        </div>

        {{-- Seção de Veículos Associados --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-car-front-fill text-gray-500"></i>
                    Veículos Associados ({{ $cliente->veiculos->count() }})
                </h3>
                <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-car-front"></i> Novo Veículo
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($cliente->veiculos as $veiculo)
                    <div
                        class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="shrink-0 inline-flex items-center bg-gray-100 justify-center h-8 w-8 rounded-full text-gray-500">
                                <i class="bi bi-car-front text-lg"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">
                                    {{ $veiculo->marca }} {{ $veiculo->modelo }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Placa: <span class="font-medium text-gray-700">{{ $veiculo->placa }}</span> <span
                                        class="mx-1">•</span> Ano: <span
                                        class="font-medium text-gray-700">{{ $veiculo->ano ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('veiculos.edit', $veiculo->id) }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                            <i class="bi bi-car-front text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Nenhum veículo cadastrado para este cliente.</p>
                        <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}"
                            class="mt-2 inline-block text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline">
                            Cadastrar o primeiro veículo
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        // MÁSCARA TELEFONE
        document.getElementById('telefone')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // MÁSCARA CPF/CNPJ
        document.getElementById('cpf_cnpj')?.addEventListener('input', function(e) {
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
