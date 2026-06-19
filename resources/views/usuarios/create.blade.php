<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Novo Funcionário
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Cadastrar Funcionário
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Adicione um novo membro à equipe da oficina
                </p>
            </div>
            
            <a href="{{ route('empresa.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nome Completo
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Maria Pereira" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            E-mail
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="funcionario@oficina.com" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Senha
                        </label>
                        <input type="password" name="password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Confirmar Senha
                        </label>
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cargo
                        </label>
                        <select name="role" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            <option value="funcionario" {{ old('role') == 'funcionario' ? 'selected' : '' }}>Funcionário (Acesso restrito)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Acesso total)</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        
                        <div class="bg-blue-50 rounded-md p-4 mt-3 border border-blue-100">
                            <h4 class="text-sm font-semibold text-blue-800 mb-1 flex items-center gap-2"><i class="bi bi-info-circle"></i> Sobre os Cargos</h4>
                            <p class="text-xs text-blue-700 mt-1">
                                <strong class="font-semibold">Funcionário:</strong> Não pode apagar registros, nem acessar as configurações da empresa.<br>
                                <strong class="font-semibold mt-0.5 block">Admin:</strong> Possui controle total sobre o sistema.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('empresa.edit') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Cadastrar Funcionário
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>
