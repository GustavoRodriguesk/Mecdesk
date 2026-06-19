<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Minha Empresa
        </h2>
    </x-slot>

    <style>
        .data-row { transition: background-color 0.12s ease; }
        .data-row:hover { background-color: #F0F4FA; }
        .btn-action { transition: opacity 0.12s ease; }
        .btn-action:hover { opacity: 0.85; }
        .search-input:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        @media (prefers-reduced-motion: reduce) {
            .data-row, .btn-action { transition: none; }
        }
    </style>

    <div class="py-8 px-8 max-w-5xl mx-auto">

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
                            <input type="text" name="nome" value="{{ old('nome', $empresa->nome) }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                            @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                            <input type="text" name="cnpj" value="{{ old('cnpj', $empresa->cnpj) }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                            @error('cnpj') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $empresa->telefone) }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                            @error('telefone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $empresa->email) }}" class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-2.5 px-4 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            {{-- Gestão de Funcionários --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Funcionários</h2>
                            <p class="text-sm text-gray-500">Gerencie o acesso da sua equipe</p>
                        </div>
                        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Novo Funcionário
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Nome</th>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">E-mail</th>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Cargo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($funcionarios as $funcionario)
                                    <tr class="data-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold uppercase select-none">
                                                    {{ mb_substr($funcionario->name, 0, 1) }}
                                                </span>
                                                <span class="font-medium text-gray-900 truncate">
                                                    {{ $funcionario->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $funcionario->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($funcionario->isAdmin())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Funcionário
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500">Nenhum funcionário cadastrado.</p>
                                                <a href="{{ route('usuarios.create') }}" class="text-xs text-blue-600 hover:underline">Cadastrar o primeiro funcionário</a>
                                            </div>
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
