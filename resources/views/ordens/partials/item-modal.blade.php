{{-- Modal Compartilhado para Adicionar/Configurar Serviços e Peças --}}
<div x-show="modalOpen" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
     style="display: none;"
     @keydown.escape.window="fecharModal()">

    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
         @click.away="fecharModal()">
        
        {{-- Header do Modal --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg" :class="modalTipo === 'servico' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'">
                    <i class="bi text-lg" :class="modalTipo === 'servico' ? 'bi-wrench' : 'bi-box-seam'"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900" x-text="modalTipo === 'servico' ? 'Adicionar Serviço' : 'Adicionar Peça'"></h3>
                    <p class="text-xs text-gray-500">Selecione do catálogo, crie um novo ou adicione um item personalizado</p>
                </div>
            </div>
            <button type="button" @click="fecharModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        {{-- Corpo do Modal --}}
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">

            {{-- 1. MODO: BUSCAR E SELECIONAR EXISTENTE --}}
            <div x-show="modalAba === 'catalogo'" class="space-y-4">
                {{-- Campo de Busca --}}
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    <input type="text" 
                           x-model="buscaTermo" 
                           :placeholder="modalTipo === 'servico' ? 'Buscar serviço no catálogo...' : 'Buscar peça por nome ou código...'"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                {{-- Lista Filtrada --}}
                <div class="border border-gray-200 rounded-lg divide-y divide-gray-100 max-h-56 overflow-y-auto bg-gray-50/50">
                    <template x-for="item in itensFiltrados" :key="item.id">
                        <div @click="selecionarItemCatalogo(item)"
                             class="p-3 hover:bg-blue-50 cursor-pointer transition-colors flex items-center justify-between"
                             :class="itemSelecionado && itemSelecionado.id === item.id ? 'bg-blue-50 border-l-4 border-blue-600' : ''">
                            <div>
                                <div class="font-medium text-sm text-gray-900" x-text="item.nome"></div>
                                <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                    <span x-show="item.codigo" class="bg-gray-200 text-gray-700 px-1.5 py-0.2 rounded text-[11px]" x-text="'Cód: ' + item.codigo"></span>
                                    <span x-show="modalTipo === 'peca'" class="text-gray-500" x-text="'Estoque: ' + (item.estoque ?? 0) + ' un'"></span>
                                    <span x-show="item.descricao" class="truncate max-w-[200px]" x-text="item.descricao"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-sm text-blue-700" x-text="formatarDinheiro(modalTipo === 'servico' ? item.valor_base : item.valor_unitario)"></span>
                                <div class="text-[11px] text-gray-400">Padrão</div>
                            </div>
                        </div>
                    </template>
                    <div x-show="itensFiltrados.length === 0" class="p-4 text-center text-xs text-gray-500">
                        Nenhum item encontrado com o termo informado.
                    </div>
                </div>

                {{-- Configuração do Item Selecionado (Qtd e Valor Unitário) --}}
                <div x-show="itemSelecionado" class="bg-blue-50/60 border border-blue-200 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-blue-200">
                        <span class="text-xs font-semibold text-blue-900 uppercase">Configurar Item para esta OS</span>
                        <span x-show="modalTipo === 'peca'" class="text-xs font-medium text-blue-800" x-text="'Disponível: ' + (itemSelecionado?.estoque ?? 0) + ' un'"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade</label>
                            <input type="number" 
                                   x-model.number="itemForm.quantidade" 
                                   min="1" 
                                   :max="modalTipo === 'peca' ? itemSelecionado?.estoque : 999"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Valor Unitário (R$)</label>
                            <input type="number" 
                                   step="0.01" 
                                   x-model.number="itemForm.valor_unitario" 
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs text-gray-600">Subtotal: <strong class="text-sm text-gray-900" x-text="formatarDinheiro((itemForm.quantidade || 1) * (itemForm.valor_unitario || 0))"></strong></span>
                        <button type="button" 
                                @click="confirmarAdicionarCatalogo()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md shadow-sm transition-colors">
                            <i class="bi bi-check2"></i>
                            Adicionar à OS
                        </button>
                    </div>
                </div>

                {{-- Alternativas Secundárias --}}
                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                    <button type="button" 
                            @click="modalAba = 'novo'"
                            class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i>
                        <span x-text="modalTipo === 'servico' ? 'Criar novo serviço no catálogo' : 'Criar nova peça no catálogo'"></span>
                    </button>
                    <button type="button" 
                            @click="modalAba = 'personalizado'"
                            class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-1">
                        <i class="bi bi-pencil-square"></i>
                        <span x-text="modalTipo === 'servico' ? 'Serviço personalizado' : 'Peça personalizada'"></span>
                    </button>
                </div>
            </div>

            {{-- 2. MODO: CRIAR NOVO NO CATÁLOGO (AJAX) --}}
            <div x-show="modalAba === 'novo'" class="space-y-4">
                <div class="p-3 bg-blue-50 text-blue-800 text-xs rounded-lg flex items-center justify-between">
                    <span>O item será cadastrado no catálogo da oficina e adicionado a esta OS.</span>
                    <button type="button" @click="modalAba = 'catalogo'" class="underline font-semibold hover:text-blue-900">Voltar à busca</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome do Item *</label>
                        <input type="text" x-model="novoItem.nome" placeholder="Ex: Troca de pastilhas..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div x-show="modalTipo === 'peca'">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Código da Peça (opcional)</label>
                        <input type="text" x-model="novoItem.codigo" placeholder="Ex: CX-1092" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div x-show="modalTipo === 'servico'">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição detalhada</label>
                        <textarea x-model="novoItem.descricao" rows="2" placeholder="Descrição do serviço..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Preço Padrão (R$) *</label>
                            <input type="number" step="0.01" x-model.number="novoItem.valor_unitario" placeholder="0,00" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div x-show="modalTipo === 'peca'">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Estoque Inicial</label>
                            <input type="number" x-model.number="novoItem.estoque" min="0" value="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div x-show="modalTipo === 'servico'">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Qtd. nesta OS</label>
                            <input type="number" x-model.number="novoItem.quantidade" min="1" value="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div x-show="modalTipo === 'peca'">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Qtd. a adicionar nesta OS</label>
                        <input type="number" x-model.number="novoItem.quantidade" min="1" value="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" @click="modalAba = 'catalogo'" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-md">Cancelar</button>
                    <button type="button" 
                            @click="cadastrarNovoECarregar()"
                            :disabled="salvandoNovo"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md disabled:opacity-50 transition-colors">
                        <span x-show="salvandoNovo"><i class="bi bi-arrow-repeat animate-spin"></i> Cadastrando...</span>
                        <span x-show="!salvandoNovo"><i class="bi bi-check2"></i> Salvar no Catálogo &amp; Adicionar à OS</span>
                    </button>
                </div>
            </div>

            {{-- 3. MODO: ITEM PERSONALIZADO (APENAS NESTA OS) --}}
            <div x-show="modalAba === 'personalizado'" class="space-y-4">
                <div class="p-3 bg-amber-50 text-amber-900 text-xs rounded-lg flex items-center justify-between">
                    <span>Este item será utilizado <strong>somente nesta OS</strong> e não ficará salvo no catálogo.</span>
                    <button type="button" @click="modalAba = 'catalogo'" class="underline font-semibold hover:text-amber-950">Voltar à busca</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição do Item *</label>
                        <input type="text" x-model="itemPersonalizado.descricao" placeholder="Ex: Adaptação de mangueira..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade *</label>
                            <input type="number" x-model.number="itemPersonalizado.quantidade" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Valor Unitário (R$) *</label>
                            <input type="number" step="0.01" x-model.number="itemPersonalizado.valor_unitario" placeholder="0,00" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="text-right text-xs text-gray-600 pt-1">
                        Subtotal: <strong class="text-sm text-gray-900" x-text="formatarDinheiro((itemPersonalizado.quantidade || 1) * (itemPersonalizado.valor_unitario || 0))"></strong>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" @click="modalAba = 'catalogo'" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-md">Cancelar</button>
                    <button type="button" 
                            @click="adicionarPersonalizado()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-md shadow-sm transition-colors">
                        <i class="bi bi-check2"></i>
                        Adicionar à OS
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
