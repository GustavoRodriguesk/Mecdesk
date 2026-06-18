<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Novo Funcionário
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-2xl mx-auto">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Cadastrar Funcionário
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Adicione um novo membro à equipe da oficina
                </p>
            </div>
            <a href="{{ route('empresa.edit') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <input type="password" name="password" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                    <select name="role" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="funcionario" {{ old('role') == 'funcionario' ? 'selected' : '' }}>Funcionário (Acesso restrito)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Acesso total)</option>
                    </select>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-2">
                        <strong>Funcionário:</strong> Não pode apagar registros, nem acessar as configurações da empresa.<br>
                        <strong>Admin:</strong> Possui controle total sobre o sistema.
                    </p>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-6 rounded-md transition-colors">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
