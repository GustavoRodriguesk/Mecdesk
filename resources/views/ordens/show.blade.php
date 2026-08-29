<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ordem de Serviço #{{ $ordem->numero_os }}
            </h2>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ str_replace('bg-', 'bg-opacity-20 text-', $ordem->status_color) }} {{ $ordem->status_color }}">
                    {{ $ordem->status_formatado }}
                </span>
                <a href="{{ route('ordens.pdf', $ordem->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-colors"
                    target="_blank" title="PDF de Ordem de Serviço / Orçamento">
                    <i class="bi bi-file-earmark-pdf"></i>
                    PDF OS
                </a>
                <a href="{{ route('ordens.pdf-vistoria', $ordem->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium text-amber-900 bg-amber-100 hover:bg-amber-200 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 transition-colors"
                    target="_blank" title="Imprimir Termo de Vistoria de Entrada do Veículo">
                    <i class="bi bi-camera"></i>
                    PDF Vistoria
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .data-row {
            transition: background-color 0.12s ease;
        }

        .data-row:hover {
            background-color: #F0F4FA;
        }
    </style>

    <div class="w-full" x-data="ordemServicoShow({
        ordemId: {{ $ordem->id }},
        servicosCatalogo: {{ Js::from($servicos) }},
        pecasCatalogo: {{ Js::from($pecas) }}
    })">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Coluna Principal --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Formulário de Informações Gerais --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-info-circle text-gray-500"></i>
                            Informações Gerais
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('ordens.update', $ordem->id) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                        Cliente
                                    </label>
                                    <select name="cliente_id" id="cliente_id"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                                        required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ $ordem->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                        Veículo
                                    </label>
                                    <select name="veiculo_id" id="veiculo_id"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                                        required>
                                        @foreach ($veiculos as $veiculo)
                                            <option value="{{ $veiculo->id }}"
                                                {{ $ordem->veiculo_id == $veiculo->id ? 'selected' : '' }}>
                                                {{ $veiculo->marca }} {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                        Problema Relatado
                                    </label>
                                    <textarea name="descricao_problema" rows="3"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                                        required>{{ old('descricao_problema', $ordem->descricao_problema) }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                        Avarias / Problemas Prévios do Veículo (Vistoria Entrada)
                                    </label>
                                    <textarea name="problemas_previos" rows="2"
                                        placeholder="Ex: Arranhão na porta traseira, para-choque trincado..."
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">{{ old('problemas_previos', $ordem->problemas_previos) }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                        Status
                                    </label>
                                    <select name="status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                                        required>
                                        <option value="aberta" {{ $ordem->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                                        <option value="em_andamento" {{ $ordem->status == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                                        <option value="aguardando_aprovacao" {{ $ordem->status == 'aguardando_aprovacao' ? 'selected' : '' }}>Aguardando aprovação</option>
                                        <option value="aprovada" {{ $ordem->status == 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                                        <option value="reprovada" {{ $ordem->status == 'reprovada' ? 'selected' : '' }}>Reprovada</option>
                                        <option value="concluida" {{ $ordem->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                                        <option value="entregue" {{ $ordem->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                                        <option value="cancelada" {{ $ordem->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end border-t border-gray-100 mt-2">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <i class="bi bi-check2"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Card de Vistoria & Fotos do Veículo --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-data="{ fotoModalUrl: null }">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-camera text-blue-600"></i>
                            Vistoria & Fotos do Veículo ({{ $ordem->fotos->count() }})
                        </h3>
                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                            <form id="form-upload-fotos" action="{{ route('ordens.fotos.store', $ordem->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <label id="btn-adicionar-fotos-label" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md cursor-pointer transition-all shadow-sm">
                                    <i id="btn-adicionar-fotos-icon" class="bi bi-plus-lg"></i>
                                    <span id="btn-adicionar-fotos-text">+ Adicionar Fotos</span>
                                    <input type="file" name="fotos[]" multiple accept="image/*" class="hidden" id="input-fotos-show" onchange="enviarFotosComCompressao(this)">
                                </label>
                            </form>
                        @endif
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($ordem->problemas_previos)
                            <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-lg">
                                <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider block mb-1">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Avarias / Problemas Prévios Registrados:
                                </span>
                                <p class="text-sm text-amber-950 font-medium whitespace-pre-line">{{ $ordem->problemas_previos }}</p>
                            </div>
                        @endif

                        @if ($ordem->fotos->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($ordem->fotos as $foto)
                                    <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                                        <img src="{{ $foto->url }}" alt="Foto do veículo" class="w-full h-full object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105" @click="fotoModalUrl = '{{ $foto->url }}'">
                                        
                                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                            <form action="{{ route('ordens.fotos.destroy', $foto->id) }}" method="POST" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Deseja remover esta foto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-md text-xs" title="Excluir foto">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <i class="bi bi-camera text-3xl mb-1 block text-gray-300"></i>
                                <p class="text-xs">Nenhuma foto registrada para este veículo nesta OS.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Lightbox Modal para Zoom da Foto --}}
                    <div x-show="fotoModalUrl" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-80 flex items-center justify-center p-4" style="display: none;" @keydown.escape.window="fotoModalUrl = null">
                        <div class="relative max-w-4xl w-full bg-black rounded-lg overflow-hidden flex flex-col items-center justify-center" @click.away="fotoModalUrl = null">
                            <button type="button" @click="fotoModalUrl = null" class="absolute top-3 right-3 text-white text-xl bg-gray-800/80 hover:bg-gray-800 rounded-full w-8 h-8 flex items-center justify-center z-10">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <img :src="fotoModalUrl" class="max-h-[85vh] w-auto object-contain">
                        </div>
                    </div>
                </div>

                {{-- Itens da Ordem (Serviços e Peças) --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-list-check text-gray-500"></i>
                            Serviços e Peças Executados
                        </h3>

                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="abrirModal('servico')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition-colors">
                                    <i class="bi bi-wrench"></i>
                                    + Adicionar Serviço
                                </button>
                                <button type="button" 
                                        @click="abrirModal('peca')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-md transition-colors">
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
                                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Tipo</th>
                                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Descrição</th>
                                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Qtd</th>
                                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">V. Unitário</th>
                                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Total</th>
                                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Ações</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($ordem->itens as $item)
                                        <tr class="data-row">
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold {{ $item->tipo_item === 'servico' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                                    <i class="bi {{ $item->tipo_item === 'servico' ? 'bi-wrench' : 'bi-box-seam' }}"></i>
                                                    {{ ucfirst($item->tipo_item) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-900 font-medium">
                                                {{ $item->descricao }}
                                                @if (($item->tipo_item === 'servico' && !$item->servico_id) || ($item->tipo_item === 'peca' && !$item->peca_id))
                                                    <span class="ml-1 text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded border border-gray-200">Personalizado</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600">
                                                {{ $item->quantidade }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-600">
                                                R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                                R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                            </td>
                                            @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <button type="button" 
                                                                @click="abrirEdicaoItem({{ Js::from($item) }})"
                                                                class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors"
                                                                title="Editar Item">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form action="{{ route('ordens.itens.destroy', $item->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Tem certeza que deseja remover este item da OS?');"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors"
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
                                <tfoot class="bg-gray-50 border-t border-gray-100">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900 text-base">
                                            Total da Ordem:
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-blue-700 text-lg whitespace-nowrap">
                                            R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                        </td>
                                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                            <td></td>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-box-seam text-3xl mb-2 block text-gray-300"></i>
                                <p class="text-sm font-medium">Nenhum serviço ou peça adicionado ainda.</p>
                                @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                    <div class="mt-3 flex items-center justify-center gap-3">
                                        <button type="button" @click="abrirModal('servico')" class="text-xs font-semibold text-blue-700 hover:underline">
                                            + Adicionar Serviço
                                        </button>
                                        <span>&bull;</span>
                                        <button type="button" @click="abrirModal('peca')" class="text-xs font-semibold text-amber-700 hover:underline">
                                            + Adicionar Peça
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Coluna Lateral (Timeline & Aprovação) --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Card de Aprovação --}}
                @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-shield-check text-gray-500"></i>
                                Aprovação do Cliente
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if (!$ordem->approval_token)
                                <p class="text-xs text-gray-500">Gere um link seguro para enviar ao cliente para que ele
                                    possa aprovar ou reprovar esta OS sem precisar de login.</p>
                                <form action="{{ route('ordens.solicitar-aprovacao', $ordem->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors flex items-center justify-center gap-2 shadow-sm">
                                        <i class="bi bi-send"></i>
                                        Solicitar aprovação
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Situação</span>
                                    @php
                                        $appStatusColor = match ($ordem->approval_status) {
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-yellow-100 text-yellow-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $appStatusColor }}">
                                        {{ $ordem->approval_status_formatado }}
                                    </span>
                                </div>

                                @if ($ordem->approval_status === 'pending')
                                    <div class="space-y-2">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase">Link de Aprovação</label>
                                        <div class="flex gap-2">
                                            <input type="text" readonly
                                                value="{{ route('aprovacao.show', $ordem->approval_token) }}"
                                                id="link-aprovacao"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded bg-gray-50 text-gray-600 focus:outline-none">
                                            <button type="button"
                                                onclick="navigator.clipboard.writeText(document.getElementById('link-aprovacao').value); alert('Link copiado!');"
                                                class="px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 flex items-center justify-center"
                                                title="Copiar Link">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <a href="{{ $ordem->whatsapp_link }}" target="_blank"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors flex items-center justify-center gap-2 shadow-sm">
                                        <i class="bi bi-whatsapp"></i>
                                        Enviar pelo WhatsApp
                                    </a>

                                    <form action="{{ route('ordens.solicitar-aprovacao', $ordem->id) }}" method="POST"
                                        class="pt-2 border-t border-gray-100">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-xs text-gray-500 hover:text-gray-700 text-center block bg-transparent border-0 cursor-pointer p-0">
                                            Gerar novo link / Resetar
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Histórico de Aprovação (se existir token) --}}
                @if ($ordem->approval_token)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-clock-history text-gray-500"></i>
                                Histórico da aprovação
                            </h3>
                        </div>
                        <div class="p-6 space-y-3 text-sm">
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Status</span>
                                <span class="font-medium text-gray-900">{{ $ordem->approval_status_formatado }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Data do envio</span>
                                <span class="font-medium text-gray-900">{{ $ordem->approval_requested_at ? $ordem->approval_requested_at->format('d/m/Y H:i') : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Data da resposta</span>
                                <span class="font-medium text-gray-900">{{ $ordem->approval_response_at ? $ordem->approval_response_at->format('d/m/Y H:i') : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">IP</span>
                                <span class="font-medium text-gray-900">{{ $ordem->approval_ip ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Navegador</span>
                                <span class="font-medium text-gray-900 text-xs block break-all text-gray-600">{{ $ordem->approval_user_agent ?? '—' }}</span>
                            </div>
                            @if ($ordem->approval_comment)
                                <div class="pt-2 border-t border-gray-100">
                                    <span class="block text-xs font-semibold text-gray-500 uppercase mb-1">Comentário do cliente</span>
                                    <div class="p-2 bg-gray-50 rounded text-xs text-gray-700 border border-gray-100 break-words">
                                        {{ $ordem->approval_comment }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Histórico de Status da OS --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-clock-history text-gray-500"></i>
                        Histórico da Ordem
                    </h3>

                    <div class="space-y-4">
                        @forelse($ordem->historicos as $historico)
                            @php
                                $statusText = match ($historico->status) {
                                    'aberta' => 'Aberta',
                                    'em_andamento' => 'Em andamento',
                                    'aguardando_aprovacao' => 'Aguardando aprovação',
                                    'aprovada' => 'Aprovada pelo cliente',
                                    'reprovada' => 'Reprovada pelo cliente',
                                    'concluida' => 'Concluída',
                                    'entregue' => 'Entregue',
                                    'cancelada' => 'Cancelada',
                                    default => $historico->status,
                                };
                            @endphp
                            <div class="relative pl-6 border-l-2 border-blue-500 pb-2">
                                <div class="text-sm font-semibold text-gray-900">{{ $statusText }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $historico->created_at->format('d/m/Y \à\s H:i') }}
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">
                                Nenhuma movimentação registrada.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        {{-- Modal Compartilhado de Adição de Itens --}}
        @include('ordens.partials.item-modal')

        {{-- Modal de Edição de Item Existente --}}
        <div x-show="modalEdicaoOpen" 
             x-transition
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
             style="display: none;"
             @keydown.escape.window="modalEdicaoOpen = false">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden" @click.away="modalEdicaoOpen = false">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Editar Item da Ordem</h3>
                    <button type="button" @click="modalEdicaoOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form :action="'/ordens/itens/' + itemEditando.id" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição</label>
                        <input type="text" name="descricao" x-model="itemEditando.descricao" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantidade</label>
                            <input type="number" name="quantidade" x-model.number="itemEditando.quantidade" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Valor Unitário (R$)</label>
                            <input type="number" step="0.01" name="valor_unitario" x-model.number="itemEditando.valor_unitario" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="modalEdicaoOpen = false" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-md">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-md">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.getElementById('cliente_id')?.addEventListener('change', function() {
            let clienteId = this.value;
            if (!clienteId) return;

            fetch(`/clientes/${clienteId}/veiculos`)
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById('veiculo_id');
                    select.innerHTML = '<option value="">Selecione um veículo...</option>';
                    data.forEach(veiculo => {
                        select.innerHTML += `<option value="${veiculo.id}">${veiculo.marca} ${veiculo.modelo} - ${veiculo.placa}</option>`;
                    });
                });
        });

        function ordemServicoShow(config) {
            return {
                ordemId: config.ordemId,
                servicosCatalogo: config.servicosCatalogo || [],
                pecasCatalogo: config.pecasCatalogo || [],

                modalOpen: false,
                modalTipo: 'servico',
                modalAba: 'catalogo',
                buscaTermo: '',
                itemSelecionado: null,
                salvandoNovo: false,

                itemForm: { quantidade: 1, valor_unitario: 0 },
                novoItem: { nome: '', codigo: '', descricao: '', valor_unitario: '', estoque: 10, quantidade: 1 },
                itemPersonalizado: { descricao: '', quantidade: 1, valor_unitario: '' },

                modalEdicaoOpen: false,
                itemEditando: { id: null, descricao: '', quantidade: 1, valor_unitario: 0 },

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
                    this.novoItem = { nome: '', codigo: '', descricao: '', valor_unitario: '', estoque: 10, quantidade: 1 };
                    this.itemPersonalizado = { descricao: '', quantidade: 1, valor_unitario: '' };
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

                    let url = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` : `/ordens/${this.ordemId}/itens/peca`;
                    let payload = this.modalTipo === 'servico'
                        ? {
                            servico_id: this.itemSelecionado.id,
                            descricao: this.itemSelecionado.nome,
                            quantidade: this.itemForm.quantidade,
                            valor_unitario: this.itemForm.valor_unitario
                        }
                        : {
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

                    let url = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` : `/ordens/${this.ordemId}/itens/peca`;
                    let payload = {
                        descricao: this.itemPersonalizado.descricao.trim(),
                        quantidade: this.itemPersonalizado.quantidade,
                        valor_unitario: this.itemPersonalizado.valor_unitario
                    };

                    this.enviarItemServidor(url, payload);
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
                            if (res.errors) msg = Object.values(res.errors).flat().join('\n');
                            throw new Error(msg);
                        }
                        return res;
                    })
                    .then(data => {
                        let novoRegistro = data.servico || data.peca;
                        let urlItem = this.modalTipo === 'servico' ? `/ordens/${this.ordemId}/itens` : `/ordens/${this.ordemId}/itens/peca`;
                        let payloadItem = this.modalTipo === 'servico'
                            ? { servico_id: novoRegistro.id, descricao: novoRegistro.nome, quantidade: this.novoItem.quantidade || 1, valor_unitario: vUnit }
                            : { peca_id: novoRegistro.id, descricao: novoRegistro.nome, quantidade: this.novoItem.quantidade || 1, valor_unitario: vUnit };

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
            };
        }

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
