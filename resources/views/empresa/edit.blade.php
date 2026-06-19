<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Minha Empresa
        </h2>
    </x-slot>

    <style>
        .data-row { transition: background-color 0.12s ease; }
        .data-row:hover { background-color: #F8FAFC; }
        .btn-action { transition: opacity 0.12s ease; }
        .btn-action:hover { opacity: 0.85; }
        .field-input:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
        @media (prefers-reduced-motion: reduce) {
            .data-row, .btn-action { transition: none; }
        }
    </style>

    <div class="py-8 px-6 max-w-4xl mx-auto space-y-6">

        {{-- ── Cabeçalho da página ── --}}
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $empresa->nome_fantasia }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold tracking-wide uppercase bg-blue-600 text-white">
                            {{ ucfirst($empresa->plano ?? 'starter') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Plano {{ ucfirst($empresa->plano ?? 'starter') }} &middot; {{ $empresa->ativo ? 'Ativo' : 'Inativo' }}
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
                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('nome_fantasia') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Razão Social</label>
                        <input type="text" name="razao_social" value="{{ old('razao_social', $empresa->razao_social ?? '') }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('razao_social') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $empresa->cnpj) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('cnpj') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $empresa->email) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $empresa->telefone) }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('telefone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $empresa->whatsapp ?? '') }}"
                            class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('whatsapp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- ── 2. Endereço (dentro do mesmo form) ── --}}
                <div class="px-6 py-5 border-t border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Endereço</h3>
                    <p class="text-sm text-gray-500 mb-5">Localização física da oficina</p>
                    <div class="grid md:grid-cols-3 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CEP</label>
                            <input type="text" name="cep" id="cep" value="{{ old('cep', $empresa->cep ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors"
                                placeholder="00000-000">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Rua / Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro', $empresa->logradouro ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Número</label>
                            <input type="text" name="numero" value="{{ old('numero', $empresa->numero ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bairro</label>
                            <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $empresa->bairro ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Cidade</label>
                            <input type="text" name="cidade" id="localidade" value="{{ old('cidade', $empresa->cidade ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                            <input type="text" name="estado" id="uf" value="{{ old('estado', $empresa->estado ?? '') }}"
                                class="field-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors"
                                maxlength="2" placeholder="SP">
                        </div>

                    </div>
                </div>

                {{-- ── 3. Identidade Visual (dentro do mesmo form) ── --}}
                <div class="px-6 py-5 border-t border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Identidade Visual</h3>
                    <p class="text-sm text-gray-500 mb-5">Logo exibida nos documentos gerados pelo sistema</p>
                    <div class="flex items-center gap-6">
                        @if(!empty($empresa->logo))
                            <img src="{{ asset('storage/' . $empresa->logo) }}"
                                class="w-24 h-24 rounded-xl object-cover border border-gray-200 shrink-0">
                        @else
                            <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs">Sem logo</span>
                            </div>
                        @endif
                        <div>
                            <input type="file" name="logo" accept="image/png,image/jpeg"
                                class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 transition-colors">
                            <p class="text-xs text-gray-500 mt-2">PNG ou JPG, até 2 MB. Recomendado: 512 × 512 px.</p>
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

        {{-- ── 4. Plano & Utilização ── --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Plano atual</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($empresa->plano ?? 'starter') }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                    Ativo
                </span>
            </div>
            <div class="px-6 py-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Funcionários</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $funcionarios->count() }}<span class="text-base font-normal text-gray-400">/3</span></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Clientes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalClientes ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Ordens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrdens ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Importações IA</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalImportacoesIA ?? '—' }}</p>
                </div>
            </div>
            <div class="px-6 pb-6">
                <a href="#"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Fazer upgrade
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Novo funcionário
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Funcionário</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">E-mail</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Cargo</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Cadastrado em</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($funcionarios as $funcionario)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase select-none">
                                            {{ mb_substr($funcionario->name, 0, 1) }}
                                        </span>
                                        <span class="font-medium text-gray-900">{{ $funcionario->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $funcionario->email }}</td>
                                <td class="px-6 py-4">
                                    @if($funcionario->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500">Nenhum funcionário cadastrado.</p>
                                        <a href="{{ route('usuarios.create') }}" class="text-xs text-blue-600 hover:underline">
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

    {{-- ── ViaCEP auto-fill ── --}}
    <script>
        document.getElementById('cep')?.addEventListener('blur', async function () {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) return;
            try {
                const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await res.json();
                if (data.erro) return;
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('bairro').value     = data.bairro     || '';
                document.getElementById('localidade').value = data.localidade  || '';
                document.getElementById('uf').value         = data.uf         || '';
            } catch (_) {}
        });
    </script>

</x-app-layout>