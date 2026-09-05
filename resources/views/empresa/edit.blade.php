<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Minha Empresa
        </h2>
    </x-slot>

    <style>
        .data-row {
            transition: background-color 0.12s ease;
        }

        .data-row:hover {
            background-color: #F8FAFC;
        }

        .btn-action {
            transition: opacity 0.12s ease;
        }

        .btn-action:hover {
            opacity: 0.85;
        }

        .field-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        @media (prefers-reduced-motion: reduce) {

            .data-row,
            .btn-action {
                transition: none;
            }
        }
    </style>

    <div class="w-full mx-auto space-y-6">

        {{-- ── Cabeçalho da página ── --}}
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                @if (!empty($empresa->logo_url))
                    <div
                        class="flex items-center justify-center w-14 h-14 rounded-xl border border-gray-200 bg-white p-1 shadow-sm shrink-0 overflow-hidden">
                        <img src="{{ $empresa->logo_url }}" alt="Logo {{ $empresa->nome_fantasia }}"
                            class="w-full h-full object-contain rounded-lg">
                    </div>
                @else
                    <div
                        class="flex items-center justify-center w-14 h-14 rounded-xl bg-gray-900 text-white shadow-sm shrink-0">
                        <div class="bi bi-gear"> </div>
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $empresa->nome_fantasia }}</h1>
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold tracking-wide uppercase bg-blue-600 text-white">
                            {{ $empresa->plano->nome ?? 'Free' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Plano {{ $empresa->plano->nome ?? 'Free' }} &middot; {{ $empresa->ativo ? 'Ativo' : 'Inativo' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ── 1. Dados da Empresa ── --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-900">Dados da empresa</h2>
                <p class="text-sm text-gray-500 mt-0.5">Informações principais da oficina</p>
            </div>
            <form action="{{ route('empresa.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 grid md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia"
                            value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('nome_fantasia')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Razão Social</label>
                        <input type="text" name="razao_social"
                            value="{{ old('razao_social', $empresa->razao_social ?? '') }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('razao_social')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $empresa->cnpj) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('cnpj')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $empresa->email) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $empresa->telefone) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('telefone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $empresa->whatsapp ?? '') }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('whatsapp')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- ── 2. Endereço (dentro do mesmo form) ── --}}
                <div class="px-6 py-5 border-t border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Endereço</h3>
                    <p class="text-sm text-gray-500 mb-5">Localização física da oficina</p>
                    <div class="grid md:grid-cols-3 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CEP</label>
                            <input type="text" name="cep" id="cep"
                                value="{{ old('cep', $empresa->cep ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors"
                                placeholder="00000-000">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Rua / Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro"
                                value="{{ old('logradouro', $empresa->logradouro ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Número</label>
                            <input type="text" name="numero" value="{{ old('numero', $empresa->numero ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bairro</label>
                            <input type="text" name="bairro" id="bairro"
                                value="{{ old('bairro', $empresa->bairro ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Cidade</label>
                            <input type="text" name="cidade" id="localidade"
                                value="{{ old('cidade', $empresa->cidade ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                            <input type="text" name="estado" id="uf"
                                value="{{ old('estado', $empresa->estado ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors"
                                maxlength="2" placeholder="SP">
                        </div>

                    </div>
                </div>

                {{-- ── 3. Identidade Visual (dentro do mesmo form) ── --}}
                <div class="px-6 py-5 border-t border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Identidade Visual</h3>
                    <p class="text-sm text-gray-500 mb-5">Logo exibida nos documentos e relatórios gerados pelo sistema
                    </p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        {{-- Preview da Imagem Atual ou Nova --}}
                        <div class="relative">
                            <div id="logo-preview-container"
                                class="w-24 h-24 rounded-xl border border-gray-200 bg-white p-1 shadow-sm shrink-0 flex items-center justify-center overflow-hidden">
                                @if (!empty($empresa->logo_url))
                                    <img id="logo-preview-img" src="{{ $empresa->logo_url }}"
                                        alt="Logo {{ $empresa->nome_fantasia }}"
                                        class="w-full h-full object-contain rounded-lg">
                                @else
                                    <div id="logo-placeholder"
                                        class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <span class="text-xs font-medium">Sem logo</span>
                                    </div>
                                    <img id="logo-preview-img" src=""
                                        class="w-full h-full object-contain rounded-lg hidden">
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 flex-1">
                            <div>
                                <input type="file" name="logo" id="logo-input"
                                    accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml,image/gif"
                                    class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 transition-colors">
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, WEBP, GIF ou SVG, até 5 MB.
                                    Recomendado: 512 × 512 px.</p>
                                @error('logo')
                                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            @if (!empty($empresa->logo))
                                <div class="flex items-center gap-2 pt-1">
                                    <label
                                        class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-700 cursor-pointer gap-1.5 select-none">
                                        <input type="checkbox" name="remover_logo" value="1"
                                            class="rounded border-gray-300 text-red-600 focus:ring-red-500 h-3.5 w-3.5">
                                        Remover logotipo atual
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── 4. Controle de Estoque (dentro do mesmo form) ── --}}
                <div class="px-6 py-5 border-t border-gray-100" x-data="{ controleEstoque: '{{ old('controle_estoque', $empresa->hasControleEstoque() ? '1' : '0') }}' }">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Controle de estoque
                        </h3>
                        <p class="text-sm text-gray-500">
                            Controle automaticamente a quantidade de peças disponíveis no estoque ao adicionar, alterar
                            ou remover peças das Ordens de Serviço.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                        {{-- Opção: Ativado --}}
                        <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all"
                            :class="controleEstoque === '1' ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/20' :
                                'border-gray-200 bg-white hover:bg-gray-50'">
                            <input type="radio" name="controle_estoque" value="1" x-model="controleEstoque"
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="pl-5">
                                <span class="block text-sm font-semibold text-gray-900">Ativado</span>
                                <span class="block text-xs text-gray-500">Movimenta e valida o saldo de peças</span>
                            </div>
                        </label>

                        {{-- Opção: Desativado --}}
                        <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all"
                            :class="controleEstoque === '0' ?
                                'border-amber-600 bg-amber-50/50 ring-2 ring-amber-500/20' :
                                'border-gray-200 bg-white hover:bg-gray-50'">
                            <input type="radio" name="controle_estoque" value="0" x-model="controleEstoque"
                                class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                            <div class="pl-5">
                                <span class="block text-sm font-semibold text-gray-900">Desativado</span>
                                <span class="block text-xs text-gray-500">Sem validação de saldo físico</span>
                            </div>
                        </label>
                    </div>

                    {{-- Aviso explicativo quando desativado --}}
                    <div x-show="controleEstoque === '0'" x-transition
                        class="mt-4 p-3.5 rounded-lg bg-amber-50 border border-amber-200 flex items-start gap-2.5 text-xs text-amber-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 mt-0.5 shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong>O controle de estoque está desativado.</strong> As peças adicionadas às Ordens de
                            Serviço não irão gerar movimentações ou validações de estoque.
                        </div>
                    </div>
                </div>

                {{-- Rodapé do form --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- ── 5. Plano & Utilização ── --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Plano atual</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $empresa->plano->nome ?? 'Free' }}</p>
                </div>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                    Ativo
                </span>
            </div>
            <div class="px-6 py-6 grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Funcionários</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $funcionarios->count() }}<span
                            class="text-base font-normal text-gray-400">/{{ $empresa->plano->max_usuarios ?? 1 }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Clientes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalClientes ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Ordens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrdens ?? '—' }}</p>
                </div>
                <!--  <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Importações IA</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalImportacoesIA ?? '—' }}</p>
                </div> -->
            </div>
            <div class="px-6 pb-6">
                <a href="{{ route('assinatura.minha') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.75 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                    </svg>
                    Gerenciar assinatura
                </a>
            </div>
        </div>

        {{-- ── 5. Funcionários ── --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Equipe</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Gerencie o acesso dos funcionários</p>
                </div>
                <a href="{{ route('usuarios.create') }}"
                    class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Novo funcionário
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">
                                Funcionário</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">
                                E-mail</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">
                                Cargo</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">
                                Cadastrado em</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($funcionarios as $funcionario)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase select-none">
                                            {{ mb_substr($funcionario->name, 0, 1) }}
                                        </span>
                                        <span class="font-medium text-gray-900">{{ $funcionario->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $funcionario->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($funcionario->isAdmin())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            Admin
                                        </span>
                                    @elseif ($funcionario->isGerente())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            Gerente
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            Funcionário
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    {{ $funcionario->created_at ? $funcionario->created_at->format('d/m/Y') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500">Nenhum funcionário cadastrado.</p>
                                        <a href="{{ route('usuarios.create') }}"
                                            class="text-xs text-blue-600 hover:underline">
                                            Cadastrar o primeiro funcionário
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── Script de Preview da Logo e ViaCEP auto-fill ── --}}
    <script>
        document.getElementById('logo-input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    let img = document.getElementById('logo-preview-img');
                    let placeholder = document.getElementById('logo-placeholder');
                    if (img) {
                        img.src = evt.target.result;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cep')?.addEventListener('blur', async function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) return;
            try {
                const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await res.json();
                if (data.erro) return;
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('localidade').value = data.localidade || '';
                document.getElementById('uf').value = data.uf || '';
            } catch (_) {}
        });
    </script>

</x-app-layout>
