<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('empresa.edit') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-building"></i>
                <span>Minha Empresa</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                <i class="bi bi-person-gear text-blue-600"></i>
                Editar Funcionário: {{ $usuario->name }}
            </span>
        </div>
    </x-slot>

    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Editar Funcionário
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Atualize os dados e o perfil de acesso do funcionário
                </p>
            </div>

            <a href="{{ route('empresa.edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            E-mail <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Perfil de Acesso <span class="text-red-500">*</span>
                        </label>
                        <select name="perfil_acesso_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                            <option value="">Selecione o perfil de acesso...</option>
                            @foreach($perfis as $perfil)
                                <option value="{{ $perfil->id }}" {{ old('perfil_acesso_id', $usuario->perfil_acesso_id) == $perfil->id ? 'selected' : '' }}>
                                    {{ $perfil->nome }} @if($perfil->descricao) — {{ $perfil->descricao }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('perfil_acesso_id')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nova Senha (deixe em branco para não alterar)
                        </label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Confirmar Nova Senha
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('empresa.edit') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
