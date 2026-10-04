<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Peças
        </h2>
    </x-slot>

    <div class="w-full max-w-4xl mx-auto py-2" x-data="{
        precoCusto: '{{ old('preco_custo', '') }}',
        precoVenda: '{{ old('preco_venda', old('valor_unitario', '')) }}',
        get custoNum() {
            let v = String(this.precoCusto || '').replace(',', '.');
            return parseFloat(v) || 0;
        },
        get vendaNum() {
            let v = String(this.precoVenda || '').replace(',', '.');
            return parseFloat(v) || 0;
        },
        get lucro() {
            return Math.max(0, this.vendaNum - this.custoNum);
        },
        get temCusto() {
            return this.custoNum > 0;
        },
        get margem() {
            if (this.custoNum <= 0 || this.lucro <= 0) return 0;
            return ((this.lucro / this.custoNum) * 100).toFixed(1);
        }
    }">

        {{-- Cabeçalho da Página --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Nova Peça
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Preencha as informações da peça para adicionar ao catálogo
                </p>
            </div>

            <a href="{{ route('pecas.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors shadow-xs">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        {{-- Card do Formulário --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">

            <form action="{{ route('pecas.store') }}" method="POST">
                @csrf

                {{-- SEÇÃO 1: Informações Básicas --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Informações Básicas</h2>
                            <p class="text-xs text-gray-500">Dados cadastrais de identificação da peça</p>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Nome da Peça / Descrição <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                            placeholder="Ex: Pastilha de freio dianteira Civic 2004"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150"
                            required autofocus>
                        @error('nome')
                            <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Marca
                            </label>
                            <input type="text" name="marca" value="{{ old('marca') }}"
                                placeholder="Ex: Bosch, Fras-le, Nakata"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150">
                            @error('marca')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Código Interno
                            </label>
                            <input type="text" name="codigo" value="{{ old('codigo') }}"
                                placeholder="Ex: PST-2004"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150">
                            @error('codigo')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Código de Barras (EAN)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-sm">
                                    <i class="bi bi-upc-scan"></i>
                                </span>
                                <input type="text" name="codigo_barras" value="{{ old('codigo_barras') }}"
                                    placeholder="Ex: 7891234567890"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150">
                            </div>
                            @error('codigo_barras')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEÇÃO 2: Preços --}}
                <div class="pt-8 mt-8 border-t border-gray-200 space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Preços</h2>
                            <p class="text-xs text-gray-500">Custos de aquisição e valores de venda para ordens de serviço</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Preço de Custo (R$) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500 text-sm font-semibold">
                                    R$
                                </span>
                                <input type="number" step="0.01" min="0" name="preco_custo" x-model="precoCusto"
                                    placeholder="0,00"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 font-medium"
                                    required>
                            </div>
                            <span class="text-xs text-gray-500 mt-1.5 block">Valor pago pelo estabelecimento na compra</span>
                            @error('preco_custo')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Preço de Venda (R$) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500 text-sm font-semibold">
                                    R$
                                </span>
                                <input type="number" step="0.01" min="0" name="preco_venda" x-model="precoVenda"
                                    placeholder="0,00"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 font-semibold text-blue-900"
                                    required>
                            </div>
                            <span class="text-xs text-gray-500 mt-1.5 block">Valor cobrado do cliente final na OS</span>
                            @error('preco_venda')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Resumo Dinâmico de Margem e Lucro --}}
                    <template x-if="vendaNum > 0">
                        <div class="p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-lg flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 text-emerald-900 font-medium">
                                <i class="bi bi-graph-up-arrow text-emerald-600 text-sm"></i>
                                <span>Lucro estimado: <strong class="font-bold">R$ <span x-text="lucro.toFixed(2).replace('.', ',')"></span></strong> por unidade</span>
                            </div>
                            <template x-if="temCusto && margem > 0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Margem: +<span x-text="margem"></span>%
                                </span>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- SEÇÃO 3: Estoque --}}
                <div class="pt-8 mt-8 border-t border-gray-200 space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Estoque</h2>
                            <p class="text-xs text-gray-500">Controle de unidades disponíveis</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Quantidade em Estoque
                            </label>
                            <input type="number" name="estoque" value="{{ old('estoque', 0) }}" min="0"
                                placeholder="0"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150">
                            <span class="text-xs text-gray-500 mt-1.5 block">Quantidade física disponível para uso</span>
                            @error('estoque')
                                <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEÇÃO 4: Rodapé e Botões de Ação --}}
                <div class="pt-8 mt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-500">
                        Somente campos com asterisco (<span class="text-red-500 font-bold">*</span>) são obrigatórios.
                    </p>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('pecas.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                            <i class="bi bi-check2 text-base"></i>
                            Salvar Peça
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>
