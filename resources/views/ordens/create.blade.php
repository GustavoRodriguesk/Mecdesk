<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nova Ordem de Serviço
        </h2>
    </x-slot>

    <div class="w-full max-w-7xl mx-auto" x-data="ordemServicoCreate({
        clientes: {{ Js::from($clientes) }},
        veiculos: {{ Js::from($veiculos) }},
        servicosCatalogo: {{ Js::from($servicos) }},
        pecasCatalogo: {{ Js::from($pecas) }},
        clienteIdInicial: '{{ old('cliente_id', request('cliente', '')) }}',
        veiculoIdInicial: '{{ old('veiculo_id', request('veiculo', '')) }}',
        oldItens: {{ Js::from(old('itens', [])) }}
    })">

        {{-- Cabeçalho da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="bi bi-file-earmark-plus text-blue-700"></i>
                    Nova Ordem de Serviço
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Monte a Ordem de Serviço completa adicionando serviços, peças e definindo valores.
                </p>
            </div>

            <a href="{{ route('ordens.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors self-start sm:self-auto">
                <i class="bi bi-arrow-left"></i>
                Voltar à Lista
            </a>
        </div>

        {{-- Formulário Principal --}}
        <form action="{{ route('ordens.store') }}" method="POST" @submit="prepararEnvio($event)" class="space-y-6">
            @csrf

            {{-- Card: Informações do Cliente & Veículo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-person-badge text-blue-600"></i>
                        Dados do Atendimento
                    </h3>
                    <span class="text-xs text-gray-500">* Campos obrigatórios</span>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Selecionar Cliente --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Cliente *
                            </label>
                            <select name="cliente_id" 
                                    x-model="clienteId" 
                                    @change="onClienteChange()"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                    required>
                                <option value="">Selecione um cliente...</option>
                                <template x-for="cliente in clientes" :key="cliente.id">
                                    <option :value="cliente.id" x-text="cliente.nome" :selected="cliente.id == clienteId"></option>
                                </template>
                            </select>
                            @error('cliente_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Selecionar Veículo --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Veículo *
                            </label>
                            <select name="veiculo_id" 
                                    x-model="veiculoId" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                    :disabled="carregandoVeiculos"
                                    required>
                                <option value="" x-text="carregandoVeiculos ? 'Carregando veículos...' : 'Selecione um veículo...'"></option>
                                <template x-for="veiculo in veiculosFiltrados" :key="veiculo.id">
                                    <option :value="veiculo.id" 
                                            x-text="veiculo.marca + ' ' + veiculo.modelo + ' - Placa: ' + veiculo.placa"
                                            :selected="veiculo.id == veiculoId"></option>
                                </template>
                            </select>
                            @error('veiculo_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Problema Relatado --}}
                        <div class="md:col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Problema Relatado / Diagnóstico Inicial *
                            </label>
                            <textarea name="descricao_problema" 
                                      rows="3" 
                                      placeholder="Descreva detalhadamente o problema relatado pelo cliente ou a solicitação..."
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                      required>{{ old('descricao_problema') }}</textarea>
                            @error('descricao_problema')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Observações Opcionais --}}
                        <div class="md:col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Observações Internas (Opcional)
                            </label>
                            <input type="text" 
                                   name="observacoes" 
                                   value="{{ old('observacoes') }}"
                                   placeholder="Observações complementares sobre prazos, detalhes ou garantias..."
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid de Itens: Serviços & Peças --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- SEÇÃO DE SERVIÇOS --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-blue-100 text-blue-700 rounded-md">
                                    <i class="bi bi-wrench"></i>
                                </span>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Serviços da OS</h3>
                                    <p class="text-xs text-gray-500" x-text="servicosAdicionados.length + ' serviço(s) adicionado(s)'"></p>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="abrirModal('servico')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm transition-colors">
                                <i class="bi bi-plus-lg"></i>
                                Adicionar Serviço
                            </button>
                        </div>

                        {{-- Lista de Serviços Adicionados --}}
                        <div class="divide-y divide-gray-100 min-h-[140px]">
                            <template x-for="(item, index) in servicosAdicionados" :key="item._tempId || index">
                                <div class="p-4 hover:bg-gray-50/70 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-sm text-gray-900" x-text="item.descricao"></span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider"
                                                  :class="item.servico_id ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                                  x-text="item.servico_id ? 'Catálogo' : 'Personalizado'"></span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-3">
                                            <span>Qtd: <strong class="text-gray-700" x-text="item.quantidade"></strong></span>
                                            <span>Unitário: <strong class="text-gray-700" x-text="formatarDinheiro(item.valor_unitario)"></strong></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-gray-900" x-text="formatarDinheiro(item.quantidade * item.valor_unitario)"></div>
                                            <div class="text-[11px] text-gray-400">Total do Item</div>
                                        </div>
                                        <button type="button" 
                                                @click="removerItem(item._tempId)"
                                                class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Remover serviço">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="servicosAdicionados.length === 0" class="p-8 text-center text-gray-400">
                                <i class="bi bi-wrench text-2xl mb-1 block text-gray-300"></i>
                                <p class="text-xs">Nenhum serviço incluído nesta OS.</p>
                                <button type="button" @click="abrirModal('servico')" class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    + Clique aqui para adicionar serviço
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Subtotal Serviços --}}
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span class="text-gray-600 font-medium uppercase tracking-wider">Subtotal Serviços:</span>
                        <span class="text-sm font-bold text-blue-800" x-text="formatarDinheiro(subtotalServicos)"></span>
                    </div>
                </div>

                {{-- SEÇÃO DE PEÇAS --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-amber-100 text-amber-700 rounded-md">
                                    <i class="bi bi-box-seam"></i>
                                </span>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Peças e Produtos</h3>
                                    <p class="text-xs text-gray-500" x-text="pecasAdicionadas.length + ' peça(s) adicionada(s)'"></p>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="abrirModal('peca')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm transition-colors">
                                <i class="bi bi-plus-lg"></i>
                                Adicionar Peça
                            </button>
                        </div>

                        {{-- Lista de Peças Adicionadas --}}
                        <div class="divide-y divide-gray-100 min-h-[140px]">
                            <template x-for="(item, index) in pecasAdicionadas" :key="item._tempId || index">
                                <div class="p-4 hover:bg-gray-50/70 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-sm text-gray-900" x-text="item.descricao"></span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider"
                                                  :class="item.peca_id ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-100 text-gray-700 border border-gray-200'"
                                                  x-text="item.peca_id ? 'Catálogo' : 'Personalizado'"></span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-3">
                                            <span>Qtd: <strong class="text-gray-700" x-text="item.quantidade"></strong></span>
                                            <span>Unitário: <strong class="text-gray-700" x-text="formatarDinheiro(item.valor_unitario)"></strong></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-gray-900" x-text="formatarDinheiro(item.quantidade * item.valor_unitario)"></div>
                                            <div class="text-[11px] text-gray-400">Total do Item</div>
                                        </div>
                                        <button type="button" 
                                                @click="removerItem(item._tempId)"
                                                class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Remover peça">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="pecasAdicionadas.length === 0" class="p-8 text-center text-gray-400">
                                <i class="bi bi-box-seam text-2xl mb-1 block text-gray-300"></i>
                                <p class="text-xs">Nenhuma peça incluída nesta OS.</p>
                                <button type="button" @click="abrirModal('peca')" class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    + Clique aqui para adicionar peça
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Subtotal Peças --}}
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span class="text-gray-600 font-medium uppercase tracking-wider">Subtotal Peças:</span>
                        <span class="text-sm font-bold text-amber-800" x-text="formatarDinheiro(subtotalPecas)"></span>
                    </div>
                </div>

            </div>

            {{-- Card de Resumo Financeiro & Ações Finais --}}
            <div class="bg-gradient-to-r from-gray-900 to-slate-900 text-white rounded-xl shadow-lg p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1 text-center md:text-left">
                    <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Resumo Geral da Ordem de Serviço</div>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-300">
                        <span>Serviços: <strong class="text-white" x-text="formatarDinheiro(subtotalServicos)"></strong></span>
                        <span>&bull;</span>
                        <span>Peças: <strong class="text-white" x-text="formatarDinheiro(subtotalPecas)"></strong></span>
                        <span>&bull;</span>
                        <span>Total de Itens: <strong class="text-white" x-text="itens.length"></strong></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    <div class="text-center sm:text-right bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm border border-white/10 w-full sm:w-auto">
                        <div class="text-[11px] text-gray-300 uppercase tracking-wider">Valor Total da OS</div>
                        <div class="text-2xl font-black text-green-400 tracking-tight" x-text="formatarDinheiro(totalGeral)"></div>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="{{ route('ordens.index') }}"
                           class="flex-1 sm:flex-none px-4 py-2.5 text-xs font-semibold text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/20 rounded-lg transition-colors text-center">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-md transition-all">
                            <i class="bi bi-check2-circle text-base"></i>
                            Salvar e Abrir OS
                        </button>
                    </div>
                </div>
            </div>

            {{-- Inputs Ocultos para Submissão da Lista de Itens --}}
            <template x-for="(item, index) in itens" :key="item._tempId || index">
                <div>
                    <input type="hidden" :name="'itens[' + index + '][tipo_item]'" :value="item.tipo_item">
                    <input type="hidden" :name="'itens[' + index + '][servico_id]'" :value="item.servico_id || ''">
                    <input type="hidden" :name="'itens[' + index + '][peca_id]'" :value="item.peca_id || ''">
                    <input type="hidden" :name="'itens[' + index + '][descricao]'" :value="item.descricao">
                    <input type="hidden" :name="'itens[' + index + '][quantidade]'" :value="item.quantidade">
                    <input type="hidden" :name="'itens[' + index + '][valor_unitario]'" :value="item.valor_unitario">
                </div>
            </template>
        </form>

        {{-- Modal de Adição/Configuração Compartilhado --}}
        @include('ordens.partials.item-modal')

    </div>

    @push('scripts')
    <script>
        function ordemServicoCreate(config) {
            return {
                clientes: config.clientes || [],
                veiculos: config.veiculos || [],
                servicosCatalogo: config.servicosCatalogo || [],
                pecasCatalogo: config.pecasCatalogo || [],
                
                clienteId: config.clienteIdInicial || '',
                veiculoId: config.veiculoIdInicial || '',
                veiculosFiltrados: [],
                carregandoVeiculos: false,

                itens: [],

                // Estado do Modal
                modalOpen: false,
                modalTipo: 'servico', // 'servico' | 'peca'
                modalAba: 'catalogo', // 'catalogo' | 'novo' | 'personalizado'
                buscaTermo: '',
                itemSelecionado: null,
                salvandoNovo: false,

                itemForm: {
                    quantidade: 1,
                    valor_unitario: 0
                },

                novoItem: {
                    nome: '',
                    codigo: '',
                    descricao: '',
                    valor_unitario: 0,
                    estoque: 10,
                    quantidade: 1
                },

                itemPersonalizado: {
                    descricao: '',
                    quantidade: 1,
                    valor_unitario: 0
                },

                init() {
                    // Inicializar veículos do cliente selecionado
                    if (this.clienteId) {
                        this.filtrarVeiculos();
                    }

                    // Se vierem itens antigos do flash/session
                    if (config.oldItens && Array.isArray(config.oldItens)) {
                        this.itens = config.oldItens.map((item, idx) => ({
                            ...item,
                            _tempId: Date.now() + '_' + idx
                        }));
                    }
                },

                onClienteChange() {
                    this.veiculoId = '';
                    this.filtrarVeiculos();
                },

                filtrarVeiculos() {
                    if (!this.clienteId) {
                        this.veiculosFiltrados = [];
                        return;
                    }
                    this.carregandoVeiculos = true;
                    fetch(`/clientes/${this.clienteId}/veiculos`)
                        .then(r => r.json())
                        .then(data => {
                            this.veiculosFiltrados = data;
                            if (this.veiculosFiltrados.length === 1 && !this.veiculoId) {
                                this.veiculoId = this.veiculosFiltrados[0].id;
                            }
                        })
                        .catch(() => {
                            this.veiculosFiltrados = this.veiculos.filter(v => v.cliente_id == this.clienteId);
                        })
                        .finally(() => {
                            this.carregandoVeiculos = false;
                        });
                },

                get servicosAdicionados() {
                    return this.itens.filter(i => i.tipo_item === 'servico');
                },

                get pecasAdicionadas() {
                    return this.itens.filter(i => i.tipo_item === 'peca');
                },

                get subtotalServicos() {
                    return this.servicosAdicionados.reduce((acc, i) => acc + ((Number(i.quantidade) || 0) * (Number(i.valor_unitario) || 0)), 0);
                },

                get subtotalPecas() {
                    return this.pecasAdicionadas.reduce((acc, i) => acc + ((Number(i.quantidade) || 0) * (Number(i.valor_unitario) || 0)), 0);
                },

                get totalGeral() {
                    return this.subtotalServicos + this.subtotalPecas;
                },

                get itensFiltrados() {
                    let termo = (this.buscaTermo || '').toLowerCase().trim();
                    let lista = this.modalTipo === 'servico' ? this.servicosCatalogo : this.pecasCatalogo;
                    
                    if (!termo) return lista.slice(0, 15);

                    return lista.filter(item => {
                        let nomeMatch = (item.nome || '').toLowerCase().includes(termo);
                        let descMatch = (item.descricao || '').toLowerCase().includes(termo);
                        let codMatch = (item.codigo || '').toLowerCase().includes(termo);
                        return nomeMatch || descMatch || codMatch;
                    }).slice(0, 20);
                },

                abrirModal(tipo) {
                    this.modalTipo = tipo;
                    this.modalAba = 'catalogo';
                    this.buscaTermo = '';
                    this.itemSelecionado = null;
                    this.itemForm = { quantidade: 1, valor_unitario: 0 };
                    this.novoItem = {
                        nome: '',
                        codigo: '',
                        descricao: '',
                        valor_unitario: '',
                        estoque: 10,
                        quantidade: 1
                    };
                    this.itemPersonalizado = {
                        descricao: '',
                        quantidade: 1,
                        valor_unitario: ''
                    };
                    this.modalOpen = true;
                },

                fecharModal() {
                    this.modalOpen = false;
                },

                selecionarItemCatalogo(item) {
                    this.itemSelecionado = item;
                    this.itemForm.quantidade = 1;
                    this.itemForm.valor_unitario = Number(this.modalTipo === 'servico' ? item.valor_base : item.valor_unitario);
                },

                confirmarAdicionarCatalogo() {
                    if (!this.itemSelecionado) return;

                    let qtd = Math.max(1, parseInt(this.itemForm.quantidade) || 1);
                    let vUnit = parseFloat(this.itemForm.valor_unitario) || 0;

                    if (this.modalTipo === 'peca') {
                        if (this.itemSelecionado.estoque !== null && qtd > this.itemSelecionado.estoque) {
                            alert(`Quantidade indisponível em estoque. Máximo disponível: ${this.itemSelecionado.estoque}`);
                            return;
                        }
                    }

                    this.itens.push({
                        _tempId: Date.now() + '_' + Math.random().toString(36).substr(2, 4),
                        tipo_item: this.modalTipo,
                        servico_id: this.modalTipo === 'servico' ? this.itemSelecionado.id : null,
                        peca_id: this.modalTipo === 'peca' ? this.itemSelecionado.id : null,
                        descricao: this.itemSelecionado.nome,
                        quantidade: qtd,
                        valor_unitario: vUnit,
                        valor_total: qtd * vUnit
                    });

                    this.fecharModal();
                },

                adicionarPersonalizado() {
                    if (!this.itemPersonalizado.descricao.trim()) {
                        alert('Informe a descrição do item.');
                        return;
                    }

                    let qtd = Math.max(1, parseInt(this.itemPersonalizado.quantidade) || 1);
                    let vUnit = parseFloat(this.itemPersonalizado.valor_unitario) || 0;

                    this.itens.push({
                        _tempId: Date.now() + '_' + Math.random().toString(36).substr(2, 4),
                        tipo_item: this.modalTipo,
                        servico_id: null,
                        peca_id: null,
                        descricao: this.itemPersonalizado.descricao.trim(),
                        quantidade: qtd,
                        valor_unitario: vUnit,
                        valor_total: qtd * vUnit
                    });

                    this.fecharModal();
                },

                cadastrarNovoECarregar() {
                    if (!this.novoItem.nome.trim()) {
                        alert('Informe o nome do item.');
                        return;
                    }

                    let vUnit = parseFloat(this.novoItem.valor_unitario);
                    if (isNaN(vUnit) || vUnit < 0) {
                        alert('Informe um valor unitário válido.');
                        return;
                    }

                    let qtd = Math.max(1, parseInt(this.novoItem.quantidade) || 1);
                    let url = this.modalTipo === 'servico' ? '/servicos' : '/pecas';
                    let payload = this.modalTipo === 'servico' 
                        ? {
                            nome: this.novoItem.nome.trim(),
                            descricao: this.novoItem.descricao.trim() || this.novoItem.nome.trim(),
                            valor_base: vUnit
                        }
                        : {
                            nome: this.novoItem.nome.trim(),
                            codigo: this.novoItem.codigo.trim() || null,
                            estoque: parseInt(this.novoItem.estoque) || 0,
                            valor_unitario: vUnit
                        };

                    this.salvandoNovo = true;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(async response => {
                        let res = await response.json();
                        if (!response.ok) {
                            let msg = res.message || 'Erro ao cadastrar item no catálogo.';
                            if (res.errors) {
                                msg = Object.values(res.errors).flat().join('\n');
                            }
                            throw new Error(msg);
                        }
                        return res;
                    })
                    .then(data => {
                        let novoRegistro = data.servico || data.peca;
                        if (this.modalTipo === 'servico') {
                            this.servicosCatalogo.push(novoRegistro);
                        } else {
                            this.pecasCatalogo.push(novoRegistro);
                        }

                        // Adicionar automaticamente à OS
                        this.itens.push({
                            _tempId: Date.now() + '_' + Math.random().toString(36).substr(2, 4),
                            tipo_item: this.modalTipo,
                            servico_id: this.modalTipo === 'servico' ? novoRegistro.id : null,
                            peca_id: this.modalTipo === 'peca' ? novoRegistro.id : null,
                            descricao: novoRegistro.nome,
                            quantidade: qtd,
                            valor_unitario: vUnit,
                            valor_total: qtd * vUnit
                        });

                        this.fecharModal();
                    })
                    .catch(err => {
                        alert(err.message);
                    })
                    .finally(() => {
                        this.salvandoNovo = false;
                    });
                },

                removerItem(tempId) {
                    this.itens = this.itens.filter(i => i._tempId !== tempId);
                },

                prepararEnvio(e) {
                    if (!this.clienteId || !this.veiculoId) {
                        alert('Por favor, selecione o cliente e o veículo.');
                        e.preventDefault();
                        return;
                    }
                },

                formatarDinheiro(val) {
                    let num = parseFloat(val) || 0;
                    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            };
        }
    </script>
    @endpush

</x-app-layout>
