<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('empresa.edit') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-building"></i>
                <span>Minha Empresa</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                <i class="bi bi-shield-lock text-blue-600"></i>
                Novo Perfil de Acesso
            </span>
        </div>
    </x-slot>

    <div class="w-full max-w-5xl mx-auto py-2">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Criar Perfil de Acesso
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Defina o nome do perfil e selecione quais permissões operacionais os funcionários vinculados terão.
                </p>
            </div>

            <a href="{{ route('empresa.edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <form action="{{ route('perfis.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Dados Gerais do Perfil --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">
                <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-600"></i> Identificação do Perfil
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nome" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nome do Perfil <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                            placeholder="Ex: Mecânico, Atendente, Financeiro, Gerente"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-150"
                            required>
                        @error('nome')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="descricao" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Descrição (opcional)
                        </label>
                        <input type="text" id="descricao" name="descricao" value="{{ old('descricao') }}"
                            placeholder="Ex: Responsável pela abertura e acompanhamento de OS"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-150">
                        @error('descricao')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Matriz de Permissões Agrupadas por Módulo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-5">
                <div class="border-b border-gray-100 pb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-toggles text-blue-600"></i> Permissões Operacionais
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Selecione as ações autorizadas. Privilégios administrativos fiscais e de cobrança são reservados ao proprietário e não podem ser atribuídos aqui.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-xs">
                        <button type="button" onclick="marcarTodas(true)"
                            class="text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 transition">
                            Marcar todas
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="marcarTodas(false)"
                            class="text-gray-600 hover:text-gray-800 font-medium px-2 py-1 rounded hover:bg-gray-100 transition">
                            Desmarcar todas
                        </button>
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach($modulos as $chaveModulo => $modulo)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/50 modulo-box" data-modulo="{{ $chaveModulo }}">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-sm text-gray-900">{{ $modulo['nome'] }}</span>
                                    <span class="text-xs text-gray-400">({{ count($modulo['permissoes']) }} permissões)</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" onclick="marcarModulo('{{ $chaveModulo }}', true)"
                                        class="text-blue-600 hover:underline">
                                        Selecionar todas
                                    </button>
                                    <span class="text-gray-300">·</span>
                                    <button type="button" onclick="marcarModulo('{{ $chaveModulo }}', false)"
                                        class="text-gray-500 hover:underline">
                                        Limpar
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($modulo['permissoes'] as $slug => $descricao)
                                    @php
                                        $checked = is_array(old('permissoes')) && in_array($slug, old('permissoes'), true);
                                    @endphp
                                    <label class="flex items-start gap-2.5 p-2.5 rounded-lg border border-gray-200 bg-white hover:bg-blue-50/50 hover:border-blue-200 cursor-pointer transition select-none text-xs">
                                        <input type="checkbox" name="permissoes[]" value="{{ $slug }}"
                                            class="perm-check mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            data-modulo="{{ $chaveModulo }}"
                                            {{ $checked ? 'checked' : '' }}>
                                        <span class="text-gray-700 leading-snug">
                                            <strong class="block font-medium text-gray-900">{{ $descricao }}</strong>
                                            <span class="text-[10px] text-gray-400 font-mono">{{ $slug }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Barra de Ações --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('empresa.edit') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <i class="bi bi-check2"></i>
                    Salvar Perfil
                </button>
            </div>
        </form>
    </div>

    <script>
        function marcarTodas(marcar) {
            document.querySelectorAll('.perm-check').forEach(cb => cb.checked = marcar);
        }

        function marcarModulo(modulo, marcar) {
            document.querySelectorAll(`.perm-check[data-modulo="${modulo}"]`).forEach(cb => cb.checked = marcar);
        }
    </script>
</x-app-layout>
