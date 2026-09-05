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
        oldItens: {{ Js::from(old('itens', [])) }},
        hasEstoqueControl: {{ (auth()->user()->empresa?->hasControleEstoque() ?? true) ? 'true' : 'false' }}
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
        <form action="{{ route('ordens.store') }}" method="POST" enctype="multipart/form-data" @submit="prepararEnvio($event)" class="space-y-6">
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

            {{-- Card: Vistoria & Estado Prévio do Veículo (Opcional) --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-camera text-blue-600"></i>
                        Vistoria / Condição Prévia do Veículo (Opcional)
                    </h3>
                    <span class="text-xs text-gray-500">Fotos & Avarias Existentes</span>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Descrição de Avarias / Problemas Prévios do Veículo
                        </label>
                        <textarea name="problemas_previos" 
                                  rows="2" 
                                  placeholder="Ex: Arranhão na porta direita, para-choque dianteiro trincado, farol esquerdo fosco..."
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">{{ old('problemas_previos') }}</textarea>
                        @error('problemas_previos')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">
                                Fotos de Entrada do Veículo
                            </label>
                            <span x-show="fotos.length > 0" class="text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full" x-text="fotos.length + (fotos.length === 1 ? ' foto adicionada' : ' fotos adicionadas')"></span>
                        </div>

                        {{-- Dropzone / File Picker --}}
                        <div class="flex items-center justify-center w-full"
                             @dragover.prevent="dragOver = true"
                             @dragleave.prevent="dragOver = false"
                             @drop.prevent="dragOver = false; handleDrop($event)">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer transition-all duration-200"
                                   :class="dragOver ? 'border-blue-500 bg-blue-50/50 scale-[1.01]' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 hover:border-gray-400'">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <template x-if="processandoFotos">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-arrow-repeat animate-spin text-2xl text-blue-600 mb-1"></i>
                                            <p class="text-sm font-medium text-blue-700" x-text="processandoTexto || 'Compactando fotos...'"></p>
                                            <p class="text-xs text-gray-400">Por favor, aguarde...</p>
                                        </div>
                                    </template>
                                    <template x-if="!processandoFotos">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 mb-1" :class="{'text-blue-600': dragOver}"></i>
                                            <p class="mb-1 text-sm text-gray-500 font-medium">
                                                <span class="text-blue-600 font-semibold">Clique para selecionar</span> ou arraste fotos aqui
                                            </p>
                                            <p class="text-xs text-gray-400">PNG, JPG, WEBP (Adicione fotos individualmente ou em lote)</p>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" 
                                       multiple 
                                       accept="image/*" 
                                       class="hidden" 
                                       id="fotos-input-picker" 
                                       @change="handleFileInput($event)"
                                       :disabled="processandoFotos">
                            </label>
                        </div>

                        {{-- Input oculto que guarda todos os arquivos acumulados para o envio do form --}}
                        <input type="file" name="fotos[]" multiple accept="image/*" class="hidden" id="fotos-input-final">

                        @error('fotos')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('fotos.*')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Previews com Exclusão Individual --}}
                        <div x-show="fotos.length > 0" class="mt-4" style="display: none;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Fotos Adicionadas (<span x-text="fotos.length"></span>)
                                </span>
                                <button type="button" 
                                        @click="limparTodasFotos()" 
                                        class="text-xs text-red-600 hover:text-red-800 font-medium transition-colors">
                                    <i class="bi bi-trash mr-0.5"></i> Remover todas
                                </button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                <template x-for="(foto, index) in fotos" :key="foto.id">
                                    <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100 transition-all hover:shadow-md">
                                        <img :src="foto.previewUrl" class="w-full h-full object-cover">
                                        
                                        {{-- Botão de Excluir Foto na Criação --}}
                                        <button type="button" 
                                                @click="removerFoto(foto.id)" 
                                                class="absolute top-1.5 right-1.5 p-1 bg-red-600 hover:bg-red-700 active:scale-95 text-white rounded-full shadow-md text-xs transition-all opacity-90 group-hover:opacity-100 hover:scale-110"
                                                title="Remover esta foto">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        {{-- Indicador de Compressão --}}
                                        <div class="absolute bottom-0 inset-x-0 bg-slate-900/85 backdrop-blur-[2px] text-white text-[10px] py-0.5 px-1 text-center font-mono font-medium truncate">
                                            <span x-text="foto.origKb + 'KB'"></span> &rarr; <span class="text-emerald-400 font-bold" x-text="foto.compKb + 'KB'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
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
                                :disabled="processandoFotos"
                                :class="processandoFotos ? 'opacity-50 cursor-not-allowed' : ''"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-md transition-all">
                            <i class="bi bi-check2-circle text-base" x-show="!processandoFotos"></i>
                            <i class="bi bi-arrow-repeat animate-spin text-base" x-show="processandoFotos" style="display: none;"></i>
                            <span x-text="processandoFotos ? 'Otimizando Fotos...' : 'Salvar e Abrir OS'"></span>
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
                hasEstoqueControl: config.hasEstoqueControl ?? true,
                
                clienteId: config.clienteIdInicial || '',
                veiculoId: config.veiculoIdInicial || '',
                veiculosFiltrados: [],
                carregandoVeiculos: false,

                itens: [],

                // Fotos do Veículo (Upload Progressivo & Exclusão)
                fotos: [],
                processandoFotos: false,
                processandoTexto: '',
                dragOver: false,

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

                    if (this.modalTipo === 'peca' && this.hasEstoqueControl) {
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

                async handleFileInput(e) {
                    let files = e.target.files;
                    if (!files || files.length === 0) return;
                    await this.processarArquivos(Array.from(files));
                    e.target.value = ''; // Permite selecionar mais fotos ou os mesmos arquivos novamente
                },

                async handleDrop(e) {
                    this.dragOver = false;
                    let files = e.dataTransfer.files;
                    if (!files || files.length === 0) return;
                    await this.processarArquivos(Array.from(files));
                },

                async processarArquivos(novosArquivos) {
                    let imageFiles = novosArquivos.filter(f => f.type.startsWith('image/'));
                    if (imageFiles.length === 0) return;

                    this.processandoFotos = true;
                    let total = imageFiles.length;

                    for (let i = 0; i < total; i++) {
                        let file = imageFiles[i];
                        this.processandoTexto = `Otimizando foto ${i + 1} de ${total}...`;

                        try {
                            let origKb = (file.size / 1024).toFixed(0);
                            let compressed = await compressImage(file);
                            let compKb = (compressed.size / 1024).toFixed(0);
                            let previewUrl = URL.createObjectURL(compressed);

                            this.fotos.push({
                                id: Date.now() + '_' + Math.random().toString(36).substr(2, 6) + '_' + i,
                                file: compressed,
                                previewUrl: previewUrl,
                                origKb: origKb,
                                compKb: compKb
                            });
                        } catch (err) {
                            console.error('Erro ao processar imagem:', err);
                        }
                    }

                    this.sincronizarInputFinal();
                    this.processandoFotos = false;
                    this.processandoTexto = '';
                },

                removerFoto(fotoId) {
                    let index = this.fotos.findIndex(f => f.id === fotoId);
                    if (index !== -1) {
                        if (this.fotos[index].previewUrl) {
                            URL.revokeObjectURL(this.fotos[index].previewUrl);
                        }
                        this.fotos.splice(index, 1);
                        this.sincronizarInputFinal();
                    }
                },

                limparTodasFotos() {
                    this.fotos.forEach(f => {
                        if (f.previewUrl) URL.revokeObjectURL(f.previewUrl);
                    });
                    this.fotos = [];
                    this.sincronizarInputFinal();
                },

                sincronizarInputFinal() {
                    let finalInput = document.getElementById('fotos-input-final');
                    if (!finalInput) return;
                    let dt = new DataTransfer();
                    this.fotos.forEach(item => {
                        dt.items.add(item.file);
                    });
                    finalInput.files = dt.files;
                },

                prepararEnvio(e) {
                    if (!this.clienteId || !this.veiculoId) {
                        alert('Por favor, selecione o cliente e o veículo.');
                        e.preventDefault();
                        return;
                    }
                    if (this.processandoFotos) {
                        alert('Aguarde a otimização das fotos ser concluída antes de salvar.');
                        e.preventDefault();
                        return;
                    }
                    this.sincronizarInputFinal();
                },

                formatarDinheiro(val) {
                    let num = parseFloat(val) || 0;
                    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            };
        }

        async function compressImage(file, maxWidth = 1280, maxHeight = 1280, quality = 0.8) {
            if (!file.type.startsWith('image/')) return file;
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth || height > maxHeight) {
                            if (width > height) {
                                height = Math.round((height * maxWidth) / width);
                                width = maxWidth;
                            } else {
                                width = Math.round((width * maxHeight) / height);
                                height = maxHeight;
                            }
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            if (!blob) {
                                resolve(file);
                                return;
                            }
                            const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = () => resolve(file);
                    img.src = e.target.result;
                };
                reader.onerror = () => resolve(file);
                reader.readAsDataURL(file);
            });
        }
    </script>
    @endpush

</x-app-layout>
