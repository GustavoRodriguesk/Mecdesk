<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Minha Empresa
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-7xl mx-auto">

        {{-- Cabeçalho --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Dados da Empresa
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Atualize as informações da sua oficina e gerencie sua equipe
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Formulário da Empresa --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <form action="{{ route('empresa.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Oficina</label>
                            <input type="text" name="nome" value="{{ old('nome', $empresa->nome) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                            <input type="text" name="cnpj" value="{{ old('cnpj', $empresa->cnpj) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('cnpj') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $empresa->telefone) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('telefone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $empresa->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            {{-- Gestão de Funcionários --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Funcionários</h2>
                        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                            + Novo Funcionário
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Nome</th>
                                    <th class="text-left font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">E-mail</th>
                                    <th class="text-left font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Cargo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($funcionarios as $funcionario)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $funcionario->name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $funcionario->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($funcionario->isAdmin())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Funcionário
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                            Nenhum funcionário cadastrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
