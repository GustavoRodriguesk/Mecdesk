<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('ordens.index') }}"
                    class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Ordens de Serviço</span>
                </a>
                <i class="bi bi-chevron-right text-xs text-gray-400"></i>
                <a href="{{ route('ordens.show', $ordem->id) }}"
                    class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
                    <span>{{ $ordem->numero_os }}</span>
                </a>
                <i class="bi bi-chevron-right text-xs text-gray-400"></i>
                <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                    <i class="bi bi-pencil-square text-blue-600"></i>
                    Editar OS
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('ordens.show', $ordem->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                    title="Módulo de Visualização da OS">
                    <i class="bi bi-eye"></i>
                    <span class="hidden sm:inline">Visualizar OS</span>
                </a>
                <a href="{{ route('ordens.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                    title="Voltar à lista de ordens">
                    <i class="bi bi-arrow-left"></i>
                    <span class="hidden sm:inline">Voltar</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full max-w-6xl mx-auto py-2 space-y-8" x-data="ordemServicoEdit({
        ordemId: {{ $ordem->id }},
        servicosCatalogo: {{ Js::from($servicos) }},
        pecasCatalogo: {{ Js::from($pecas) }},
        hasEstoqueControl: {{ auth()->user()->empresa?->hasControleEstoque() ?? true ? 'true' : 'false' }}
    })">

        {{-- Cabeçalho da Seção --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                    <span>Editar Ordem de Serviço #{{ $ordem->numero_os }}</span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ordem->status_color }}">
                        {{ $ordem->status_formatado }}
                    </span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Módulo de edição completa: altere dados cadastrais, gerencie fotos, adicione/edite peças, serviços e
                    descontos.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ordens.show', $ordem->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm transition-colors">
                    <i class="bi bi-eye"></i>
                    Ir para Visualização
                </a>
            </div>
        </div>

        {{-- Bloqueio Informativo se a OS não estiver Aberta --}}
        @if (!$ordem->podeEditar())
            <div
                class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs flex items-center gap-3 shadow-sm">
                <i class="bi bi-lock-fill text-amber-600 text-xl shrink-0"></i>
                <div>
                    <span class="font-semibold block text-sm text-amber-950 mb-0.5">
                        Status atual: "{{ $ordem->status_formatado }}"
                    </span>
                    <span class="text-amber-800">
                        Para alterar peças, serviços ou fotos, mude o status abaixo para <strong>Aberta</strong> e salve
                        as informações cadastrais.
                    </span>
                </div>
            </div>
        @endif

        {{-- SEÇÃO 1: FORMULÁRIO DE DADOS CADASTRAIS DA OS --}}
        <form action="{{ route('ordens.update', $ordem->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Card 1: Dados do Atendimento --}}
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

                        {{-- Cliente --}}
                        <div>
                            <label for="cliente_id" class="block mb-1.5 text-sm font-medium text-gray-700">
                                Cliente *
                            </label>
                            <select name="cliente_id" id="cliente_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                required>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ old('cliente_id', $ordem->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Veículo --}}
                        <div>
                            <label for="veiculo_id" class="block mb-1.5 text-sm font-medium text-gray-700">
                                Veículo *
                            </label>
                            <select name="veiculo_id" id="veiculo_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                required>
                                @foreach ($veiculos as $veiculo)
                                    <option value="{{ $veiculo->id }}"
                                        {{ old('veiculo_id', $ordem->veiculo_id) == $veiculo->id ? 'selected' : '' }}>
                                        {{ $veiculo->marca }} {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="veiculo_loading" class="text-xs text-blue-600 mt-1 hidden">
                                <i class="bi bi-arrow-repeat animate-spin mr-1"></i> Carregando veículos do cliente...
                            </p>
                            @error('veiculo_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Funcionário / Mecânico --}}
                        <div>
                            <label for="funcionario_id" class="block mb-1.5 text-sm font-medium text-gray-700">
                                Funcionário / Mecânico Responsável
                            </label>
                            <select name="funcionario_id" id="funcionario_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">
                                <option value="">Nenhum funcionário atribuído</option>
                                @foreach ($funcionarios as $func)
                                    <option value="{{ $func->id }}"
                                        {{ old('funcionario_id', $ordem->funcionario_id) == $func->id ? 'selected' : '' }}>
                                        {{ $func->name }} ({{ ucfirst($func->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('funcionario_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status da OS --}}
                        <div>
                            <label for="status" class="block mb-1.5 text-sm font-medium text-gray-700">
                                Status da Ordem *
                            </label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                required>
                                <option value="aberta"
                                    {{ old('status', $ordem->status) == 'aberta' ? 'selected' : '' }}>Aberta</option>
                                <option value="em_andamento"
                                    {{ old('status', $ordem->status) == 'em_andamento' ? 'selected' : '' }}>Em
                                    andamento</option>
                                <option value="aguardando_aprovacao"
                                    {{ old('status', $ordem->status) == 'aguardando_aprovacao' ? 'selected' : '' }}>
                                    Aguardando aprovação</option>
                                <option value="aprovada"
                                    {{ old('status', $ordem->status) == 'aprovada' ? 'selected' : '' }}>Aprovada
                                </option>
                                <option value="reprovada"
                                    {{ old('status', $ordem->status) == 'reprovada' ? 'selected' : '' }}>Reprovada
                                </option>
                                <option value="concluida"
                                    {{ old('status', $ordem->status) == 'concluida' ? 'selected' : '' }}>Concluída
                                </option>
                                <option value="entregue"
                                    {{ old('status', $ordem->status) == 'entregue' ? 'selected' : '' }}>Entregue
                                </option>
                                <option value="cancelada"
                                    {{ old('status', $ordem->status) == 'cancelada' ? 'selected' : '' }}>Cancelada
                                </option>
                            </select>
                            @error('status')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Card 2: Diagnóstico & Vistoria --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-file-earmark-medical text-blue-600"></i>
                        Diagnóstico & Queixa do Cliente
                    </h3>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Problema Relatado --}}
                    <div>
                        <label for="descricao_problema" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Problema Relatado / Queixa do Cliente *
                        </label>
                        <textarea name="descricao_problema" id="descricao_problema" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                            placeholder="Descreva detalhadamente o defeito ou motivo da entrada..." required>{{ old('descricao_problema', $ordem->descricao_problema) }}</textarea>
                        @error('descricao_problema')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Avarias / Problemas Prévios --}}
                    <div>
                        <label for="problemas_previos" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Avarias / Problemas Prévios do Veículo (Vistoria de Entrada)
                        </label>
                        <textarea name="problemas_previos" id="problemas_previos" rows="2"
                            placeholder="Ex: Arranhão na porta dianteira direita, amassado no para-choque traseiro, etc."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">{{ old('problemas_previos', $ordem->problemas_previos) }}</textarea>
                        @error('problemas_previos')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Observações Gerais --}}
                    <div>
                        <label for="observacoes" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Observações Gerais / Anotações Internas
                        </label>
                        <textarea name="observacoes" id="observacoes" rows="2"
                            placeholder="Anotações internas da oficina, instruções para a equipe, etc."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">{{ old('observacoes', $ordem->observacoes) }}</textarea>
                        @error('observacoes')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors shadow-sm">
                        <i class="bi bi-check2 text-base"></i>
                        Salvar Informações da OS
                    </button>
                </div>
            </div>
        </form>

        {{-- SEÇÃO 2: VISTORIA & FOTOS DO VEÍCULO (ANEXAR / EXCLUIR FOTOS) --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-camera text-blue-600"></i>
                        Fotos de Vistoria do Veículo ({{ $ordem->fotos->count() }})
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Adicione ou remova fotos de avarias e do estado de entrada
                        do veículo.</p>
                </div>

                @if ($ordem->podeEditar())
                    <div>
                        <form action="{{ route('ordens.fotos.store', $ordem->id) }}" method="POST"
                            enctype="multipart/form-data" class="inline-block">
                            @csrf
                            <label id="btn-adicionar-fotos-label"
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg cursor-pointer transition-colors">
                                <i id="btn-adicionar-fotos-icon" class="bi bi-plus-lg"></i>
                                <span id="btn-adicionar-fotos-text">Adicionar Fotos</span>
                                <input type="file" name="fotos[]" multiple accept="image/*" class="hidden"
                                    onchange="enviarFotosComCompressao(this)">
                            </label>
                        </form>
                    </div>
                @endif
            </div>

            <div class="p-6">
                @if ($ordem->fotos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($ordem->fotos as $foto)
                            <div
                                class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shadow-sm">
                                <img src="{{ $foto->url }}" alt="Foto da OS"
                                    class="w-full h-full object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105"
                                    @click="fotoModalUrl = '{{ $foto->url }}'">

                                @if ($ordem->podeEditar())
                                    <div class="absolute top-1.5 right-1.5 z-10">
                                        <form action="{{ route('ordens.fotos.destroy', $foto->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta foto da OS?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="absolute top-1.5 right-1.5 p-1 bg-red-600 hover:bg-red-700 active:scale-95 text-white rounded-full shadow-md text-xs transition-all opacity-90 group-hover:opacity-100 hover:scale-110"
                                                title="Excluir Foto">
                                                <i class="bi bi-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="bi bi-camera text-3xl mb-1 block text-gray-300"></i>
                        <p class="text-sm font-medium">Nenhuma foto cadastrada nesta Ordem de Serviço.</p>
                        @if ($ordem->podeEditar())
                            <p class="text-xs text-gray-400 mt-1">Utilize o botão acima para adicionar fotos com
                                compressão automática.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- SEÇÃO 3: SERVIÇOS E PEÇAS EXECUTADOS (ADICIONAR / EDITAR / EXCLUIR ITENS E DESCONTO) --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div
                class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-list-check text-blue-600"></i>
                        Serviços e Peças Executados ({{ $ordem->itens->count() }})
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Gerencie os itens da OS, edite valores, quantidades e
                        aplique descontos.</p>
                </div>

                @if ($ordem->podeEditar())
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" @click="descontoModalOpen = true"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors">
                            <i class="bi bi-tag"></i>
                            {{ $ordem->temDesconto() ? 'Editar Desconto' : '+ Desconto' }}
                        </button>
                        <button type="button" @click="abrirModal('servico')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                            <i class="bi bi-wrench"></i>
                            + Adicionar Serviço
                        </button>
                        <button type="button" @click="abrirModal('peca')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-colors">
                            <i class="bi bi-box-seam"></i>
                            + Adicionar Peça
                        </button>
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                @if ($ordem->itens->count())
                    <table class="w-full text-sm">
                        <thead class="bg-white border-b border-gray-100">
                            <tr>
                                <th
                                    class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">
                                    Tipo</th>
                                <th
                                    class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">
                                    Descrição</th>
                                <th
                                    class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">
                                    Qtd</th>
                                <th
                                    class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">
                                    V. Unitário</th>
                                <th
                                    class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">
                                    Total</th>
                                @if ($ordem->podeEditar())
                                    <th
                                        class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">
                                        Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($ordem->itens as $item)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-semibold {{ $item->tipo_item === 'servico' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                            <i
                                                class="bi {{ $item->tipo_item === 'servico' ? 'bi-wrench' : 'bi-box-seam' }}"></i>
                                            {{ ucfirst($item->tipo_item) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-900 font-medium">
                                        {{ $item->descricao }}
                                        @if (($item->tipo_item === 'servico' && !$item->servico_id) || ($item->tipo_item === 'peca' && !$item->peca_id))
                                            <span
                                                class="ml-1 text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded border border-gray-200">Personalizado</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center text-gray-700 font-medium">
                                        {{ $item->quantidade }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right text-gray-700">
                                        R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-bold text-gray-900">
                                        R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                    </td>
                                    @if ($ordem->podeEditar())
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    @click="abrirEdicaoItem({{ Js::from($item) }})"
                                                    class="text-blue-600 hover:text-blue-800 p-1.5 rounded hover:bg-blue-50 transition-colors"
                                                    title="Editar Valor / Quantidade">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('ordens.itens.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja remover este item da OS?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 p-1.5 rounded hover:bg-red-50 transition-colors"
                                                        title="Remover Item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/80 border-t border-gray-200">
                            @if ($ordem->temDesconto())
                                <tr>
                                    <td colspan="{{ $ordem->podeEditar() ? '4' : '4' }}"
                                        class="px-6 py-2.5 text-right font-medium text-gray-500 text-sm">
                                        Subtotal:
                                    </td>
                                    <td
                                        class="px-6 py-2.5 text-right font-semibold text-gray-700 text-sm whitespace-nowrap">
                                        R$
                                        {{ number_format($ordem->subtotal ?: $ordem->valor_total + $ordem->valor_desconto, 2, ',', '.') }}
                                    </td>
                                    @if ($ordem->podeEditar())
                                        <td></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td colspan="{{ $ordem->podeEditar() ? '4' : '4' }}"
                                        class="px-6 py-2.5 text-right font-medium text-emerald-600 text-sm">
                                        Desconto
                                        ({{ $ordem->desconto_tipo === 'porcentagem' ? number_format($ordem->desconto_valor, 0) . '%' : 'fixo' }}):
                                    </td>
                                    <td
                                        class="px-6 py-2.5 text-right font-bold text-emerald-600 text-sm whitespace-nowrap">
                                        - R$ {{ number_format($ordem->valor_desconto, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="{{ $ordem->temDesconto() ? 'border-t border-gray-200' : '' }}">
                                <td colspan="{{ $ordem->podeEditar() ? '4' : '4' }}"
                                    class="px-6 py-4 text-right font-bold text-gray-900 text-base">
                                    Total da Ordem:
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-blue-700 text-lg whitespace-nowrap">
                                    R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                </td>
                                @if ($ordem->podeEditar())
                                    <td></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="px-6 py-12 text-center text-gray-500">
                        <i class="bi bi-box-seam text-3xl mb-2 block text-gray-300"></i>
                        <p class="text-sm font-medium">Nenhum serviço ou peça adicionado nesta Ordem de Serviço.</p>
                        @if ($ordem->podeEditar())
                            <p class="text-xs text-gray-400 mt-1">Utilize os botões acima para incluir serviços e peças
                                do catálogo ou personalizados.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL COMPARTILHADO: ADICIONAR SERVIÇOS E PEÇAS --}}
        @include('ordens.partials.item-modal')

        {{-- MODAL DE EDIÇÃO DE ITEM EXISTENTE --}}
        <template x-teleport="body">
            <div x-show="modalEdicaoOpen" x-transition
                class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4"
                style="display: none;" @keydown.escape.window="modalEdicaoOpen = false">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden"
                    @click.away="modalEdicaoOpen = false">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-pencil-square text-blue-600"></i>
                            Editar Item da Ordem
                        </h3>
                        <button type="button" @click="modalEdicaoOpen = false"
                            class="text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <form :action="'/ordens/itens/' + itemEditando.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Descrição</label>
                            <input type="text" name="descricao" x-model="itemEditando.descricao"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade</label>
                                <input type="number" name="quantidade" x-model.number="itemEditando.quantidade"
                                    min="1"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Valor Unitário (R$)</label>
                                <input type="number" step="0.01" name="valor_unitario"
                                    x-model.number="itemEditando.valor_unitario"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button type="button" @click="modalEdicaoOpen = false"
                                class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-md">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-xs font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-md shadow-sm">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL DE DESCONTO DA OS --}}
        <template x-teleport="body">
            <div x-show="descontoModalOpen" x-transition
                class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4"
                style="display: none;" @keydown.escape.window="descontoModalOpen = false">
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4"
                    @click.away="descontoModalOpen = false">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span
                                class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                                <i class="bi bi-tag-fill"></i>
                            </span>
                            Desconto da Ordem de Serviço
                        </h3>
                        <button type="button" @click="descontoModalOpen = false"
                            class="text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>

                    <form action="{{ route('ordens.desconto.update', $ordem->id) }}" method="POST"
                        class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de Desconto
                            </label>
                            <div class="grid grid-cols-2 gap-2 bg-gray-100 p-1 rounded-lg">
                                <button type="button" @click="descontoTipo = 'dinheiro'"
                                    :class="descontoTipo === 'dinheiro' ? 'bg-white shadow text-gray-900 font-bold' :
                                        'text-gray-600 hover:text-gray-900'"
                                    class="py-1.5 text-xs rounded-md transition-all flex items-center justify-center gap-1">
                                    <i class="bi bi-cash"></i>
                                    Valor Fixo (R$)
                                </button>
                                <button type="button" @click="descontoTipo = 'porcentagem'"
                                    :class="descontoTipo === 'porcentagem' ? 'bg-white shadow text-gray-900 font-bold' :
                                        'text-gray-600 hover:text-gray-900'"
                                    class="py-1.5 text-xs rounded-md transition-all flex items-center justify-center gap-1">
                                    <i class="bi bi-percent"></i>
                                    Porcentagem (%)
                                </button>
                            </div>
                            <input type="hidden" name="desconto_tipo" :value="descontoTipo">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1"
                                x-text="descontoTipo === 'porcentagem' ? 'Porcentagem de Desconto (%)' : 'Valor do Desconto (R$)'"></label>
                            <input type="number" name="desconto_valor" x-model.number="descontoValor"
                                :step="descontoTipo === 'porcentagem' ? '1' : '0.01'" min="0"
                                :max="descontoTipo === 'porcentagem' ? '100' : subtotalOrdem" placeholder="0,00"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-emerald-500">
                        </div>

                        {{-- Resumo da Simulação do Desconto --}}
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 text-xs space-y-1.5">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal da OS:</span>
                                <span>R$ <span
                                        x-text="subtotalOrdem.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></span>
                            </div>
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span>Desconto aplicado:</span>
                                <span>- R$ <span
                                        x-text="valorDescontoPreview.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-900 font-bold pt-1 border-t border-gray-200">
                                <span>Total Final:</span>
                                <span class="text-blue-700">R$ <span
                                        x-text="totalFinalPreview.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span></span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button type="button" @click="descontoModalOpen = false"
                                class="px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
                                Salvar Desconto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- LIGHTBOX MODAL PARA ZOOM DA FOTO --}}
        <template x-teleport="body">
            <div x-show="fotoModalUrl" x-transition
                class="fixed inset-0 z-[100] overflow-y-auto bg-black bg-opacity-80 backdrop-blur-sm flex items-center justify-center p-4"
                style="display: none;" @keydown.escape.window="fotoModalUrl = null">
                <div class="relative max-w-4xl w-full bg-black rounded-lg overflow-hidden flex flex-col items-center justify-center"
                    @click.away="fotoModalUrl = null">
                    <button type="button" @click="fotoModalUrl = null"
                        class="absolute top-3 right-3 text-white text-xl bg-gray-800/80 hover:bg-gray-800 rounded-full w-8 h-8 flex items-center justify-center z-10"
                        title="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img :src="fotoModalUrl" class="max-h-[85vh] w-auto object-contain">
                </div>
            </div>
        </template>

    </div>

    @push('scripts')
        <script>
            // Carregamento dinâmico de veículos ao alterar cliente
            document.addEventListener('DOMContentLoaded', function() {
                const clienteSelect = document.getElementById('cliente_id');
                const veiculoSelect = document.getElementById('veiculo_id');
                const veiculoLoading = document.getElementById('veiculo_loading');
                const currentVeiculoId = "{{ old('veiculo_id', $ordem->veiculo_id) }}";

                if (clienteSelect) {
                    clienteSelect.addEventListener('change', function() {
                        const clienteId = this.value;
                        if (!clienteId) {
                            veiculoSelect.innerHTML =
                                '<option value="">Selecione um cliente primeiro...</option>';
                            return;
                        }

                        if (veiculoLoading) veiculoLoading.classList.remove('hidden');
                        veiculoSelect.disabled = true;

                        fetch(`/clientes/${clienteId}/veiculos`)
                            .then(response => response.json())
                            .then(data => {
                                veiculoSelect.innerHTML = '';
                                if (!data || data.length === 0) {
                                    veiculoSelect.innerHTML =
                                        '<option value="">Nenhum veículo cadastrado para este cliente</option>';
                                } else {
                                    veiculoSelect.innerHTML =
                                        '<option value="">Selecione um veículo...</option>';
                                    data.forEach(veiculo => {
                                        const opt = document.createElement('option');
                                        opt.value = veiculo.id;
                                        opt.textContent =
                                            `${veiculo.marca} ${veiculo.modelo} - ${veiculo.placa}`;
                                        if (veiculo.id == currentVeiculoId) {
                                            opt.selected = true;
                                        }
                                        veiculoSelect.appendChild(opt);
                                    });
                                }
                            })
                            .catch(err => {
                                console.error('Erro ao buscar veículos:', err);
                            })
                            .finally(() => {
                                veiculoSelect.disabled = false;
                                if (veiculoLoading) veiculoLoading.classList.add('hidden');
                            });
                    });
                }
            });

            // Gerenciador Alpine da página de Edição da OS
            function ordemServicoEdit(config) {
                return {
                    ordemId: config.ordemId,
                    servicosCatalogo: config.servicosCatalogo || [],
                    pecasCatalogo: config.pecasCatalogo || [],
                    hasEstoqueControl: config.hasEstoqueControl ?? true,
                    fotoModalUrl: null,

                    // Desconto
                    descontoModalOpen: false,
                    descontoTipo: '{{ $ordem->desconto_tipo ?? 'dinheiro' }}',
                    descontoValor: {{ (float) ($ordem->desconto_valor ?? 0) }},
                    subtotalOrdem: {{ (float) ($ordem->subtotal ?: $ordem->valor_total + $ordem->valor_desconto) }},

                    get valorDescontoPreview() {
                        let sub = this.subtotalOrdem;
                        let val = Number(this.descontoValor) || 0;
                        if (val <= 0 || sub <= 0) return 0;
                        if (this.descontoTipo === 'porcentagem') {
                            let p = Math.min(100, Math.max(0, val));
                            return Math.round(sub * (p / 100) * 100) / 100;
                        }
                        return Math.min(sub, Math.max(0, val));
                    },

                    get totalFinalPreview() {
                        return Math.max(0, Math.round((this.subtotalOrdem - this.valorDescontoPreview) * 100) / 100);
                    },

                    modalOpen: false,
                    modalTipo: 'servico',
                    modalAba: 'catalogo',
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
                        marca: '',
                        descricao: '',
                        preco_custo: '',
                        preco_venda: '',
                        valor_unitario: '',
                        estoque: 0,
                        quantidade: 1
                    },
                    itemPersonalizado: {
                        descricao: '',
                        quantidade: 1,
                        valor_unitario: ''
                    },

                    modalEdicaoOpen: false,
                    itemEditando: {
                        id: null,
                        descricao: '',
                        quantidade: 1,
                        valor_unitario: 0
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
                        this.itemForm = {
                            quantidade: 1,
                            valor_unitario: 0
                        };
                        this.novoItem = {
                            nome: '',
                            codigo: '',
                            marca: '',
                            descricao: '',
                            preco_custo: '',
                            preco_venda: '',
                            valor_unitario: '',
                            estoque: 0,
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
                        this.itemForm.valor_unitario = Number(this.modalTipo === 'servico' ? item.valor_base : (item
                            .preco_venda || item.valor_unitario));
                    },

                    confirmarAdicionarCatalogo() {
                        if (!this.itemSelecionado) return;

                        let url = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` :
                            `/ordens/${this.ordemId}/itens/peca`;
                        let payload = this.modalTipo === 'servico' ? {
                            servico_id: this.itemSelecionado.id,
                            descricao: this.itemSelecionado.nome,
                            quantidade: this.itemForm.quantidade,
                            valor_unitario: this.itemForm.valor_unitario
                        } : {
                            peca_id: this.itemSelecionado.id,
                            descricao: this.itemSelecionado.nome,
                            quantidade: this.itemForm.quantidade,
                            valor_unitario: this.itemForm.valor_unitario
                        };

                        this.enviarItemServidor(url, payload);
                    },

                    adicionarPersonalizado() {
                        if (!this.itemPersonalizado.descricao.trim()) {
                            alert('Informe a descrição do item.');
                            return;
                        }

                        let url = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` :
                            `/ordens/${this.ordemId}/itens/peca`;
                        let payload = {
                            descricao: this.itemPersonalizado.descricao.trim(),
                            quantidade: this.itemPersonalizado.quantidade,
                            valor_unitario: this.itemPersonalizado.valor_unitario
                        };

                        this.enviarItemServidor(url, payload);
                    },

                    cadastrarNovoECarregar() {
                        if (!this.novoItem.nome || !this.novoItem.nome.trim()) {
                            alert('Informe o nome do item.');
                            return;
                        }

                        let qtd = Math.max(1, parseInt(this.novoItem.quantidade) || 1);
                        let url = this.modalTipo === 'servico' ? '/servicos' : '/pecas';
                        let payload = {};
                        let precoUnitarioOS = 0;

                        if (this.modalTipo === 'servico') {
                            let vUnit = parseFloat(this.novoItem.valor_unitario);
                            if (isNaN(vUnit) || vUnit < 0) {
                                alert('Informe um valor unitário válido.');
                                return;
                            }
                            precoUnitarioOS = vUnit;
                            payload = {
                                nome: this.novoItem.nome.trim(),
                                descricao: this.novoItem.descricao ? this.novoItem.descricao.trim() : this.novoItem.nome
                                    .trim(),
                                valor_base: vUnit
                            };
                        } else {
                            let precoCusto = parseFloat(this.novoItem.preco_custo);
                            if (isNaN(precoCusto) || precoCusto < 0) {
                                alert('Informe o preço de custo (compra) válido.');
                                return;
                            }

                            let precoVenda = parseFloat(this.novoItem.preco_venda);
                            if (isNaN(precoVenda) || precoVenda < 0) {
                                alert('Informe o preço de venda válido.');
                                return;
                            }

                            precoUnitarioOS = precoVenda;
                            payload = {
                                nome: this.novoItem.nome.trim(),
                                marca: this.novoItem.marca ? this.novoItem.marca.trim() : null,
                                codigo: this.novoItem.codigo ? this.novoItem.codigo.trim() : null,
                                estoque: parseInt(this.novoItem.estoque) || 0,
                                preco_custo: precoCusto,
                                preco_venda: precoVenda,
                                valor_unitario: precoVenda
                            };
                        }

                        this.salvandoNovo = true;

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify(payload)
                            })
                            .then(async response => {
                                let res = await response.json();
                                if (!response.ok) {
                                    let msg = res.message || 'Erro ao cadastrar item no catálogo.';
                                    if (res.errors) msg = Object.values(res.errors).flat().join('\n');
                                    throw new Error(msg);
                                }
                                return res;
                            })
                            .then(data => {
                                let novoRegistro = data.servico || data.peca;
                                let urlItem = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` :
                                    `/ordens/${this.ordemId}/itens/peca`;
                                let payloadItem = this.modalTipo === 'servico' ? {
                                    servico_id: novoRegistro.id,
                                    descricao: novoRegistro.nome,
                                    quantidade: qtd,
                                    valor_unitario: precoUnitarioOS
                                } : {
                                    peca_id: novoRegistro.id,
                                    descricao: novoRegistro.nome,
                                    quantidade: qtd,
                                    valor_unitario: precoUnitarioOS
                                };

                                this.enviarItemServidor(urlItem, payloadItem);
                            })
                            .catch(err => {
                                alert(err.message);
                            })
                            .finally(() => {
                                this.salvandoNovo = false;
                            });
                    },

                    enviarItemServidor(url, payload) {
                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify(payload)
                            })
                            .then(async response => {
                                let res = await response.json();
                                if (!response.ok) {
                                    let msg = res.message || 'Erro ao adicionar item.';
                                    if (res.errors) msg = Object.values(res.errors).flat().join('\n');
                                    throw new Error(msg);
                                }
                                window.location.reload();
                            })
                            .catch(err => {
                                alert(err.message);
                            });
                    },

                    abrirEdicaoItem(item) {
                        this.itemEditando = {
                            id: item.id,
                            descricao: item.descricao,
                            quantidade: item.quantidade,
                            valor_unitario: item.valor_unitario
                        };
                        this.modalEdicaoOpen = true;
                    },

                    formatarDinheiro(val) {
                        let num = parseFloat(val) || 0;
                        return num.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        });
                    }
                };
            }

            // Upload de Fotos com Compressão no Lado do Cliente
            async function enviarFotosComCompressao(input) {
                if (!input.files || input.files.length === 0) return;

                let form = input.form;
                let label = document.getElementById('btn-adicionar-fotos-label') || input.closest('label');
                let icon = document.getElementById('btn-adicionar-fotos-icon');
                let text = document.getElementById('btn-adicionar-fotos-text');

                if (label) {
                    label.style.pointerEvents = 'none';
                    label.classList.add('opacity-75', 'bg-blue-100');
                }
                if (icon) {
                    icon.className = 'bi bi-arrow-repeat animate-spin';
                }

                try {
                    let dataTransfer = new DataTransfer();
                    let filesArr = Array.from(input.files);
                    let total = filesArr.length;

                    for (let i = 0; i < total; i++) {
                        if (text) {
                            text.textContent = `Compactando ${i + 1}/${total}...`;
                        }
                        let compressed = await compressImage(filesArr[i]);
                        dataTransfer.items.add(compressed);
                    }

                    if (text) {
                        text.textContent = 'Enviando...';
                    }

                    input.files = dataTransfer.files;
                    form.submit();
                } catch (err) {
                    console.error('Erro ao processar fotos para envio:', err);
                    alert('Ocorreu um erro ao processar as fotos selecionadas. Tente novamente.');
                    if (label) {
                        label.style.pointerEvents = 'auto';
                        label.classList.remove('opacity-75', 'bg-blue-100');
                    }
                    if (icon) {
                        icon.className = 'bi bi-plus-lg';
                    }
                    if (text) {
                        text.textContent = '+ Adicionar Fotos';
                    }
                }
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
                                const compressedFile = new File([blob], file.name.replace(
                                    /\.[^/.]+$/, "") + ".jpg", {
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
