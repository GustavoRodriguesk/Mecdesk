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
                <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    {{ $ordem->numero_os }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                @if ($ordem->funcionario)
                    <span
                        class="hidden md:inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200"
                        title="Funcionário Responsável">
                        <i class="bi bi-person-badge text-slate-500"></i>
                        {{ $ordem->funcionario->name }}
                    </span>
                @endif
                <a href="{{ route('ordens.edit', $ordem->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors"
                    title="Editar Informações da OS">
                    <i class="bi bi-pencil-square"></i>
                    <span class="hidden sm:inline">Editar OS</span>
                </a>
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('abrir-modal-pdf'))"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs sm:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all cursor-pointer"
                    title="Exportar Documentos em PDF (OS e Vistoria)">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Exportar PDF</span>
                    <i class="bi bi-chevron-down text-xs opacity-80"></i>
                </button>
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

    <div class="w-full" x-data="{ fotoModalUrl: null, exportPdfModalOpen: false }" @abrir-modal-pdf.window="exportPdfModalOpen = true">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Coluna Principal --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Módulo de Visualização: Informações Gerais da OS --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-info-circle text-blue-600"></i>
                            Informações da Ordem de Serviço
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Cliente --}}
                            <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Cliente
                                </span>
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('clientes.show', $ordem->cliente->id) }}"
                                        class="text-sm font-bold text-gray-900 hover:text-blue-600 hover:underline">
                                        {{ $ordem->cliente->nome }}
                                    </a>
                                    @if ($ordem->cliente->telefone)
                                        <span class="text-xs text-gray-600 font-mono">
                                            {{ $ordem->cliente->telefone }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Veículo --}}
                            <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Veículo
                                </span>
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('veiculos.show', $ordem->veiculo->id) }}"
                                        class="text-sm font-bold text-gray-900 hover:text-blue-600 hover:underline">
                                        {{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }}
                                    </a>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-white text-gray-800 border border-gray-200 uppercase font-mono">
                                        {{ $ordem->veiculo->placa }}
                                    </span>
                                </div>
                            </div>

                            {{-- Funcionário / Mecânico --}}
                            <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Mecânico / Responsável
                                </span>
                                <div class="text-sm font-medium text-gray-900 flex items-center gap-1.5">
                                    <i class="bi bi-person text-gray-400"></i>
                                    @if ($ordem->funcionario)
                                        <span>{{ $ordem->funcionario->name }}</span>
                                        <span
                                            class="text-xs text-gray-500">({{ ucfirst($ordem->funcionario->role) }})</span>
                                    @else
                                        <span class="text-gray-400 italic">Nenhum funcionário atribuído</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Status e Entrada --}}
                            <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Status & Entrada
                                </span>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ordem->status_color }}">
                                        {{ $ordem->status_formatado }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        <i class="bi bi-calendar3 mr-1 text-gray-400"></i>
                                        {{ $ordem->data_entrada ? $ordem->data_entrada->format('d/m/Y H:i') : $ordem->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Problema Relatado --}}
                        <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                Problema Relatado / Queixa do Cliente
                            </span>
                            <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                                {{ $ordem->descricao_problema }}
                            </p>
                        </div>

                        {{-- Avarias Prévias (se houver) --}}
                        @if ($ordem->problemas_previos)
                            <div class="bg-amber-50/70 rounded-lg p-3.5 border border-amber-200/80">
                                <span
                                    class="block text-xs font-semibold text-amber-800 uppercase tracking-wider mb-1.5">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Avarias / Problemas Prévios
                                    (Vistoria Entrada)
                                </span>
                                <p class="text-sm text-amber-950 font-medium whitespace-pre-line leading-relaxed">
                                    {{ $ordem->problemas_previos }}
                                </p>
                            </div>
                        @endif

                        {{-- Observações Adicionais (se houver) --}}
                        @if ($ordem->observacoes)
                            <div class="bg-gray-50/80 rounded-lg p-3.5 border border-gray-100">
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                    Observações Adicionais
                                </span>
                                <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                                    {{ $ordem->observacoes }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Banner Informativo quando a OS não está Aberta --}}
                @if (!$ordem->podeEditar())
                    <div
                        class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs flex items-center gap-3 shadow-sm">
                        <i class="bi bi-lock-fill text-amber-600 text-xl shrink-0"></i>
                        <div>
                            <span class="font-semibold block text-sm text-amber-950 mb-0.5">Ordem de Serviço com status
                                "{{ $ordem->status_formatado }}"</span>
                            <span class="text-amber-800">Esta Ordem de Serviço não pode sofrer alterações nas peças,
                                serviços ou fotos de avarias. Para alterar informações cadastrais ou mudar o status para
                                <strong>Aberta</strong>, acesse o módulo de <a
                                    href="{{ route('ordens.edit', $ordem->id) }}"
                                    class="underline font-semibold text-amber-950 hover:text-black">edição da
                                    OS</a>.</span>
                        </div>
                    </div>
                @endif

                {{-- Card de Vistoria & Fotos do Veículo --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-camera text-blue-600"></i>
                            Vistoria & Fotos do Veículo ({{ $ordem->fotos->count() }})
                        </h3>
                        <a href="{{ route('ordens.pdf-vistoria', $ordem->id) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-colors"
                            title="Imprimir Termo de Vistoria de Entrada do Veículo">
                            <i class="bi bi-file-earmark-pdf text-amber-600"></i>
                            <span>PDF Vistoria</span>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($ordem->problemas_previos)
                            <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-lg">
                                <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider block mb-1">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Avarias / Problemas Prévios
                                    Registrados:
                                </span>
                                <p class="text-sm text-amber-950 font-medium whitespace-pre-line">
                                    {{ $ordem->problemas_previos }}</p>
                            </div>
                        @endif

                        @if ($ordem->fotos->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($ordem->fotos as $foto)
                                    <div
                                        class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                                        <img src="{{ $foto->url }}" alt="Foto do veículo"
                                            class="w-full h-full object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105"
                                            @click="fotoModalUrl = '{{ $foto->url }}'">
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
                </div>

                {{-- Itens da Ordem (Serviços e Peças) --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-list-check text-gray-500"></i>
                            Serviços e Peças Executados
                        </h3>
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
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($ordem->itens as $item)
                                        <tr class="data-row">
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold {{ $item->tipo_item === 'servico' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                                    <i
                                                        class="bi {{ $item->tipo_item === 'servico' ? 'bi-wrench' : 'bi-box-seam' }}"></i>
                                                    {{ ucfirst($item->tipo_item) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-900 font-medium">
                                                {{ $item->descricao }}
                                                @if (($item->tipo_item === 'servico' && !$item->servico_id) || ($item->tipo_item === 'peca' && !$item->peca_id))
                                                    <span
                                                        class="ml-1 text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded border border-gray-200">Personalizado</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600">
                                                {{ $item->quantidade }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-600">
                                                R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-3 text-right font-bold text-gray-900">
                                                R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-100">
                                    @if ($ordem->temDesconto())
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-2.5 text-right font-medium text-gray-500 text-sm">
                                                Subtotal:
                                            </td>
                                            <td
                                                class="px-6 py-2.5 text-right font-semibold text-gray-700 text-sm whitespace-nowrap">
                                                R$
                                                {{ number_format($ordem->subtotal ?: $ordem->valor_total + $ordem->valor_desconto, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"
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
                                        <td colspan="4"
                                            class="px-6 py-4 text-right font-bold text-gray-900 text-base">
                                            Total da Ordem:
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right font-bold text-blue-700 text-lg whitespace-nowrap">
                                            R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-box-seam text-3xl mb-2 block text-gray-300"></i>
                                <p class="text-sm font-medium">Nenhum serviço ou peça registrado nesta Ordem de
                                    Serviço.</p>
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
                                <p class="text-xs text-gray-500">Gere um link seguro para enviar ao cliente para que
                                    ele
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
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $appStatusColor }}">
                                        {{ $ordem->approval_status_formatado }}
                                    </span>
                                </div>

                                @if ($ordem->approval_status === 'pending')
                                    <div class="space-y-2">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase">Link de
                                            Aprovação</label>
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

                                    <form action="{{ route('ordens.solicitar-aprovacao', $ordem->id) }}"
                                        method="POST" class="pt-2 border-t border-gray-100">
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
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Data do
                                    envio</span>
                                <span
                                    class="font-medium text-gray-900">{{ $ordem->approval_requested_at ? $ordem->approval_requested_at->format('d/m/Y H:i') : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Data da
                                    resposta</span>
                                <span
                                    class="font-medium text-gray-900">{{ $ordem->approval_response_at ? $ordem->approval_response_at->format('d/m/Y H:i') : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">IP</span>
                                <span class="font-medium text-gray-900">{{ $ordem->approval_ip ?? '—' }}</span>
                            </div>
                            <div>
                                <span
                                    class="block text-xs font-semibold text-gray-500 uppercase mb-0.5">Navegador</span>
                                <span
                                    class="font-medium text-gray-900 text-xs block break-all text-gray-600">{{ $ordem->approval_user_agent ?? '—' }}</span>
                            </div>
                            @if ($ordem->approval_comment)
                                <div class="pt-2 border-t border-gray-100">
                                    <span class="block text-xs font-semibold text-gray-500 uppercase mb-1">Comentário
                                        do cliente</span>
                                    <div
                                        class="p-2 bg-gray-50 rounded text-xs text-gray-700 border border-gray-100 break-words">
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

        {{-- Lightbox Modal para Zoom da Foto --}}
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

        {{-- MODAL DE EXPORTAÇÃO DE PDFS (OS e Vistoria) --}}
        <template x-teleport="body">
            <div x-show="exportPdfModalOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4"
                style="display: none;" @keydown.escape.window="exportPdfModalOpen = false">

                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100"
                    @click.away="exportPdfModalOpen = false">

                    {{-- Cabeçalho do Modal --}}
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                <i class="bi bi-file-earmark-pdf-fill text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Exportar Documentos em PDF</h3>
                                <p class="text-xs text-gray-500">Selecione o tipo de documento que deseja gerar</p>
                            </div>
                        </div>
                        <button type="button" @click="exportPdfModalOpen = false"
                            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>

                    {{-- Opções de PDF --}}
                    <div class="p-6 space-y-4">

                        {{-- Opção 1: PDF da Ordem de Serviço --}}
                        <a href="{{ route('ordens.pdf', $ordem->id) }}" target="_blank"
                            @click="exportPdfModalOpen = false"
                            class="group relative flex items-start gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-red-500 hover:bg-red-50/40 transition-all duration-200">
                            <div
                                class="w-11 h-11 rounded-xl bg-red-100 group-hover:bg-red-600 text-red-600 group-hover:text-white flex items-center justify-center shrink-0 transition-colors shadow-sm">
                                <i class="bi bi-file-earmark-text text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h4
                                        class="text-sm font-bold text-gray-900 group-hover:text-red-700 transition-colors">
                                        PDF da Ordem de Serviço (OS)
                                    </h4>
                                    <span
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-600 bg-red-50 group-hover:bg-red-100 px-2.5 py-0.5 rounded-full border border-red-200">
                                        <i class="bi bi-arrow-up-right"></i> Abrir PDF
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Orçamento detalhado com serviços, peças, valores unitários, descontos, total da OS e
                                    termos para assinatura do cliente.
                                </p>
                            </div>
                        </a>

                        {{-- Opção 2: PDF do Laudo de Vistoria --}}
                        <a href="{{ route('ordens.pdf-vistoria', $ordem->id) }}" target="_blank"
                            @click="exportPdfModalOpen = false"
                            class="group relative flex items-start gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-amber-500 hover:bg-amber-50/40 transition-all duration-200">
                            <div
                                class="w-11 h-11 rounded-xl bg-amber-100 group-hover:bg-amber-600 text-amber-700 group-hover:text-white flex items-center justify-center shrink-0 transition-colors shadow-sm">
                                <i class="bi bi-camera text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h4
                                        class="text-sm font-bold text-gray-900 group-hover:text-amber-800 transition-colors">
                                        PDF do Termo de Vistoria
                                    </h4>
                                    <span
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 group-hover:bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-200">
                                        <i class="bi bi-arrow-up-right"></i> Abrir PDF
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Checklist de entrada do veículo, diagnóstico da queixa, avarias prévias registradas
                                    e anexo com todas as fotos.
                                </p>
                            </div>
                        </a>

                    </div>

                </div>
            </div>
        </template>

    </div>

</x-app-layout>
