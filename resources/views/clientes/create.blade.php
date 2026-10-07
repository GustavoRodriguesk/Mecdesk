<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('clientes.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-people"></i>
                <span>Clientes</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                <i class="bi bi-person-fill-add text-blue-600"></i>
                Novo Cliente
            </span>
        </div>
    </x-slot>

    <div class="w-full mx-auto py-2">

        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Novo Cliente
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Insira abaixo os dados do novo cliente do seu negócio
                </p>
            </div>

            <a href="{{ route('clientes.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors shadow-xs">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        {{-- Card do Formulário --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <form action="{{ route('clientes.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf

                {{-- ── 1. Informações Pessoais ── --}}
                <div>
                    <div
                        class="flex items-center gap-2 text-sm font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-5">
                        <i class="bi bi-person text-gray-500 text-base"></i>
                        <span>Informações Pessoais</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Nome --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Nome <span class="text-red-500 font-bold">*</span>
                            </label>
                            <input type="text" name="nome" value="{{ old('nome') }}"
                                placeholder="Ex: João da Silva" maxlength="100"
                                class="w-full px-3 py-2 text-sm border @error('nome') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors duration-150"
                                required>
                            @error('nome')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- CPF/CNPJ --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                CPF/CNPJ
                            </label>
                            <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}"
                                placeholder="000.000.000-00 ou 00.000.000/0000-00" maxlength="18"
                                class="w-full px-3 py-2 text-sm border @error('cpf_cnpj') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors duration-150">
                            @error('cpf_cnpj')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── 2. Contato ── --}}
                <div>
                    <div
                        class="flex items-center gap-2 text-sm font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-5">
                        <i class="bi bi-telephone text-gray-500 text-base"></i>
                        <span>Contato</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        {{-- Telefone 1 (Principal) --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Celular / Telefone 1 <span class="text-red-500 font-bold">*</span>
                            </label>
                            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
                                placeholder="(00) 00000-0000" maxlength="15"
                                class="w-full px-3 py-2 text-sm border @error('telefone') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors duration-150"
                                required>
                            @error('telefone')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror

                            {{-- Checkbox WhatsApp --}}
                            <div class="mt-3 flex items-center">
                                <input type="hidden" name="possui_whatsapp" value="0">
                                <label for="possui_whatsapp"
                                    class="inline-flex items-center gap-2 cursor-pointer select-none text-xs font-medium text-gray-700 hover:text-gray-900">
                                    <input type="checkbox" id="possui_whatsapp" name="possui_whatsapp" value="1"
                                        {{ old('possui_whatsapp', '1') == '1' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-whatsapp text-emerald-600 text-sm"></i>
                                        Possui WhatsApp?
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Telefone 2 --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Telefone 2 <span class="text-xs text-gray-400 font-normal">(Recado / Fixo)</span>
                            </label>
                            <input type="text" id="telefone_2" name="telefone_2" value="{{ old('telefone_2') }}"
                                placeholder="(00) 0000-0000" maxlength="15"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('telefone_2')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Telefone 3 --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Telefone 3 <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="text" id="telefone_3" name="telefone_3" value="{{ old('telefone_3') }}"
                                placeholder="(00) 0000-0000" maxlength="15"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('telefone_3')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                        {{-- E-mail --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                E-mail
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="Ex: contato@email.com" maxlength="100"
                                class="w-full px-3 py-2 text-sm border @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors duration-150">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Instagram --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Instagram
                            </label>
                            <div class="relative rounded-lg">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm font-medium">@</span>
                                <input type="text" name="instagram" value="{{ old('instagram') }}"
                                    placeholder="usuario" maxlength="100"
                                    class="w-full pl-8 pr-3 py-2 text-sm border @error('instagram') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 transition-colors duration-150">
                            </div>
                            @error('instagram')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── 3. Endereço ── --}}
                <div>
                    <div
                        class="flex items-center gap-2 text-sm font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-5">
                        <i class="bi bi-geo-alt text-gray-500 text-base"></i>
                        <span>Endereço</span>
                    </div>

                    {{-- Campo CEP com botão Buscar --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                CEP
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="cep" name="cep" value="{{ old('cep') }}"
                                    placeholder="00000-000" maxlength="9"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                                <button type="button" id="btn-buscar-cep"
                                    class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-xs cursor-pointer">
                                    <i class="bi bi-search text-xs" id="cep-search-icon"></i>
                                    <span id="cep-btn-text">Buscar</span>
                                </button>
                            </div>
                            <p id="cep-feedback" class="text-xs mt-1 hidden"></p>
                            @error('cep')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Campos de Logradouro, Número e Complemento --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-5">
                        <div class="md:col-span-6">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Rua / Logradouro
                            </label>
                            <input type="text" id="rua" name="rua" value="{{ old('rua') }}"
                                placeholder="Ex: Av. Paulista" maxlength="255"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('rua')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Número
                            </label>
                            <input type="text" id="numero" name="numero" value="{{ old('numero') }}"
                                placeholder="Ex: 123" maxlength="20"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('numero')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-4">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Complemento
                            </label>
                            <input type="text" id="complemento" name="complemento"
                                value="{{ old('complemento') }}" placeholder="Apto, Sala, Bloco..." maxlength="100"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('complemento')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Campos de Bairro, Cidade e Estado --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-5">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Bairro
                            </label>
                            <input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}"
                                placeholder="Ex: Centro" maxlength="100"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('bairro')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-5">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Cidade
                            </label>
                            <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}"
                                placeholder="Ex: São Paulo" maxlength="100"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('cidade')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Estado (UF)
                            </label>
                            <input type="text" id="estado" name="estado" value="{{ old('estado') }}"
                                placeholder="SP" maxlength="2"
                                class="w-full px-3 py-2 text-sm uppercase border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                            @error('estado')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Rodapé com Informação e Ações ── --}}
                <div
                    class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <i class="bi bi-info-circle text-gray-400"></i>
                        Somente campos com asterisco (<span class="text-red-500 font-bold">*</span>) são obrigatórios.
                    </p>

                    <div class="flex items-center gap-3 justify-end">
                        <a href="{{ route('clientes.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-xs cursor-pointer">
                            <i class="bi bi-check2"></i>
                            Salvar Cliente
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

    {{-- ── Scripts de Máscaras e Consulta de CEP (ViaCEP API) ── --}}
    <script>
        // Função utilitária para máscara de telefones
        function aplicarMascaraTelefone(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value.substring(0, 15);
            });
        }

        aplicarMascaraTelefone('telefone');
        aplicarMascaraTelefone('telefone_2');
        aplicarMascaraTelefone('telefone_3');

        // Máscara CPF/CNPJ
        const cpfCnpjInput = document.getElementById('cpf_cnpj');
        if (cpfCnpjInput) {
            cpfCnpjInput.addEventListener('input', function(e) {
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
                e.target.value = value.substring(0, 18);
            });
        }

        // Estado (UF) em maiúsculas
        const estadoInput = document.getElementById('estado');
        if (estadoInput) {
            estadoInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase().substring(0, 2);
            });
        }

        // ── Integração ViaCEP ──
        const cepInput = document.getElementById('cep');
        const btnBuscarCep = document.getElementById('btn-buscar-cep');
        const cepFeedback = document.getElementById('cep-feedback');
        const cepSearchIcon = document.getElementById('cep-search-icon');
        const cepBtnText = document.getElementById('cep-btn-text');

        async function consultarCep() {
            if (!cepInput) return;
            const rawCep = cepInput.value.replace(/\D/g, '');

            if (rawCep.length !== 8) {
                if (cepFeedback) {
                    cepFeedback.className = 'text-xs mt-1 text-amber-600 block';
                    cepFeedback.textContent = 'Informe um CEP válido com 8 dígitos.';
                }
                return;
            }

            // Exibe estado de carregando
            if (btnBuscarCep) btnBuscarCep.disabled = true;
            if (cepSearchIcon) cepSearchIcon.className = 'bi bi-arrow-repeat text-xs animate-spin';
            if (cepBtnText) cepBtnText.textContent = 'Buscando...';
            if (cepFeedback) {
                cepFeedback.className = 'text-xs mt-1 text-blue-600 block';
                cepFeedback.textContent = 'Buscando endereço...';
            }

            try {
                const response = await fetch(`https://viacep.com.br/ws/${rawCep}/json/`);
                const data = await response.json();

                if (data.erro) {
                    if (cepFeedback) {
                        cepFeedback.className = 'text-xs mt-1 text-red-600 block';
                        cepFeedback.textContent = 'CEP não encontrado.';
                    }
                    return;
                }

                // Preenche os campos de endereço
                const ruaEl = document.getElementById('rua');
                const bairroEl = document.getElementById('bairro');
                const cidadeEl = document.getElementById('cidade');
                const estadoEl = document.getElementById('estado');
                const numeroEl = document.getElementById('numero');

                if (ruaEl && data.logradouro) ruaEl.value = data.logradouro;
                if (bairroEl && data.bairro) bairroEl.value = data.bairro;
                if (cidadeEl && data.localidade) cidadeEl.value = data.localidade;
                if (estadoEl && data.uf) estadoEl.value = data.uf;

                if (cepFeedback) {
                    cepFeedback.className = 'text-xs mt-1 text-emerald-600 block';
                    cepFeedback.textContent = 'Endereço localizado com sucesso!';
                    setTimeout(() => {
                        cepFeedback.classList.add('hidden');
                    }, 4000);
                }

                // Move o cursor diretamente para o campo número
                if (numeroEl) {
                    numeroEl.focus();
                }
            } catch (err) {
                if (cepFeedback) {
                    cepFeedback.className = 'text-xs mt-1 text-red-600 block';
                    cepFeedback.textContent = 'Não foi possível consultar o CEP no momento.';
                }
            } finally {
                if (btnBuscarCep) btnBuscarCep.disabled = false;
                if (cepSearchIcon) cepSearchIcon.className = 'bi bi-search text-xs';
                if (cepBtnText) cepBtnText.textContent = 'Buscar';
            }
        }

        if (btnBuscarCep) {
            btnBuscarCep.addEventListener('click', consultarCep);
        }

        if (cepInput) {
            // Máscara 00000-000
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value.substring(0, 9);

                // Ao preencher os 8 dígitos, consulta automaticamente
                if (value.replace(/\D/g, '').length === 8) {
                    consultarCep();
                }
            });

            // Dispara também com a tecla Enter
            cepInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    consultarCep();
                }
            });
        }
    </script>

</x-app-layout>
