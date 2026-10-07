<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="bi bi-file-earmark-text text-blue-600 text-lg"></i>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                Ordens de Serviço
            </h2>
        </div>
    </x-slot>

    <style>
        .data-row {
            transition: background-color 0.12s ease;
        }

        .data-row:hover {
            background-color: #F0F4FA;
        }

        .btn-action {
            transition: opacity 0.12s ease;
        }

        .btn-action:hover {
            opacity: 0.85;
        }

        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        @media (prefers-reduced-motion: reduce) {

            .data-row,
            .btn-action {
                transition: none;
            }
        }
    </style>

    <div class="w-full" x-data="{
        exportPdfModalOpen: false,
        selectedOrdem: { id: null, numero: '', cliente: '', pdfUrl: '', pdfVistoriaUrl: '' },
        abrirModalPdf(id, numero, cliente, pdfUrl, pdfVistoriaUrl) {
            this.selectedOrdem = { id, numero, cliente, pdfUrl, pdfVistoriaUrl };
            this.exportPdfModalOpen = true;
        },
        async enviarAprovacaoWhatsApp(ordemId, hasToken, whatsappLink, numeroOs) {
            if (!confirm(`Deseja enviar a OS #${numeroOs} para aprovação? Isso mudará o status da OS para 'Aguardando cliente'.`)) {
                return;
            }

            if (hasToken && whatsappLink) {
                window.open(whatsappLink, '_blank');
                return;
            }

            try {
                const response = await fetch(`/ordens/${ordemId}/solicitar-aprovacao`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    }
                });

                const data = await response.json();
                if (data.success && data.whatsapp_link) {
                    window.open(data.whatsapp_link, '_blank');
                    window.location.reload();
                } else {
                    alert(data.message || 'Não foi possível gerar a solicitação de aprovação.');
                }
            } catch (err) {
                console.error(err);
                alert('Ocorreu um erro ao processar o envio para o WhatsApp.');
            }
        }
    }">

        {{-- Card principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Formulário de Filtros Avançados --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-4">
                <form method="GET" action="{{ route('ordens.index') }}" class="flex flex-col gap-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                        {{-- Busca Global --}}
                        <div class="xl:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Busca
                                rápida</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="OS, cliente, placa..."
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Status --}}
                        <div class="xl:col-span-1">
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <select name="status"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="">Todos</option>
                                <option value="aberta" @selected(request('status') == 'aberta')>Aberta</option>
                                <option value="aguardando_aprovacao" @selected(request('status') == 'aguardando_aprovacao')>Aguardando Aprovação
                                </option>
                                <option value="aprovada" @selected(request('status') == 'aprovada')>Aprovada</option>
                                <option value="reprovada" @selected(request('status') == 'reprovada')>Reprovada</option>
                                <option value="concluida" @selected(request('status') == 'concluida')>Concluída</option>
                                <option value="entregue" @selected(request('status') == 'entregue')>Entregue</option>
                                <option value="cancelada" @selected(request('status') == 'cancelada')>Cancelada</option>
                            </select>
                        </div>

                        {{-- Cliente --}}
                        <div class="xl:col-span-1">
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente</label>
                            <select name="cliente_id"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="">Todos</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(request('cliente_id') == $cliente->id)>
                                        {{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Funcionário --}}
                        <div class="xl:col-span-1">
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Mecânico
                                / Resp.</label>
                            <select name="funcionario_id"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="">Todos</option>
                                @foreach ($funcionarios as $func)
                                    <option value="{{ $func->id }}" @selected(request('funcionario_id') == $func->id)>
                                        {{ $func->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Data Inicial --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data
                                Início</label>
                            <input type="date" name="inicio" value="{{ request('inicio') }}"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Data Final --}}
                        <div class="xl:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Data
                                Fim</label>
                            <input type="date" name="fim" value="{{ request('fim') }}"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                        </div>

                        {{-- Ordenação (pode ficar abaixo se faltar espaço, ou no lugar de uma das datas dependendo do layout, mas colocarei na linha) --}}
                        <div class="xl:col-span-2">
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ordenar
                                por</label>
                            <select name="sort"
                                class="search-input w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 transition-colors duration-150">
                                <option value="recentes" @selected(request('sort') == 'recentes' || !request('sort'))>Mais recentes</option>
                                <option value="antigas" @selected(request('sort') == 'antigas')>Mais antigas</option>
                                <option value="valor_maior" @selected(request('sort') == 'valor_maior')>Maior valor</option>
                                <option value="valor_menor" @selected(request('sort') == 'valor_menor')>Menor valor</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            @if (request('search') ||
                                    request('status') ||
                                    request('cliente_id') ||
                                    request('funcionario_id') ||
                                    request('inicio') ||
                                    request('fim'))
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                    </svg>
                                    Filtros ativos
                                </span>
                            @endif
                            @if (isset($ordens) && method_exists($ordens, 'total'))
                                <span class="ml-2 text-gray-500">{{ $ordens->total() }}
                                    {{ $ordens->total() === 1 ? 'ordem' : 'ordens' }}</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('ordens.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                Limpar
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-700">
                                Filtrar
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed min-w-[900px]">
                    <colgroup>
                        <col style="width: 12%">
                        <col style="width: 24%">
                        <col style="width: 20%">
                        <col style="width: 14%">
                        <col style="width: 14%">
                        <col style="width: 16%">
                    </colgroup>
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Nº OS</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Cliente</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Veículo</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Status OS</th>
                            <th
                                class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Valor Total</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        @forelse($ordens as $ordem)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 items-start">
                                        <a href="{{ route('ordens.show', $ordem->id) }}"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-colors"
                                            title="Visualizar #{{ $ordem->numero_os }}">
                                            #{{ $ordem->numero_os }}
                                        </a>
                                        @if ($ordem->funcionario)
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] text-gray-600 font-medium truncate max-w-[130px]"
                                                title="Responsável: {{ $ordem->funcionario->name }}">
                                                <i class="bi bi-person text-gray-400"></i>
                                                {{ Str::limit($ordem->funcionario->name, 14) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 truncate">
                                    <a href="{{ route('ordens.show', $ordem->id) }}"
                                        class="hover:text-blue-600 hover:underline">
                                        {{ $ordem->cliente->nome }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-500 truncate">
                                    {{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ordem->status_color }}">
                                        {{ $ordem->status_formatado }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-sm">
                                            R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                        </span>
                                        @if ($ordem->temDesconto())
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 mt-0.5 self-start"
                                                title="Desconto: {{ $ordem->desconto_descricao }}">
                                                <i class="bi bi-tag-fill text-[10px]"></i>
                                                -R$ {{ number_format($ordem->valor_desconto, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Enviar OS para Aprovação via WhatsApp (Unificado) --}}
                                        @if ($ordem->status !== 'concluida' && $ordem->status !== 'cancelada')
                                            <button type="button"
                                                @click="enviarAprovacaoWhatsApp('{{ $ordem->id }}', {{ $ordem->approval_token ? 'true' : 'false' }}, '{{ $ordem->approval_token ? $ordem->whatsapp_link : '' }}', '{{ $ordem->numero_os }}')"
                                                class="btn-action inline-flex items-center justify-center w-8 h-8 text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors cursor-pointer"
                                                title="Enviar Aprovação via WhatsApp">
                                                <i class="bi bi-whatsapp text-sm"></i>
                                            </button>
                                        @endif

                                        {{-- Exportar PDF (Modal com OS e Vistoria) --}}
                                        <button type="button"
                                            @click="abrirModalPdf('{{ $ordem->id }}', '{{ $ordem->numero_os }}', '{{ addslashes($ordem->cliente->nome) }}', '{{ route('ordens.pdf', $ordem->id) }}', '{{ route('ordens.pdf-vistoria', $ordem->id) }}')"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors cursor-pointer"
                                            title="Exportar PDF (OS e Vistoria)">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                        </button>

                                        {{-- Visualizar OS --}}
                                        <a href="{{ route('ordens.show', $ordem->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                                            title="Visualizar OS">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>

                                        {{-- Editar OS --}}
                                        <a href="{{ route('ordens.edit', $ordem->id) }}"
                                            class="btn-action inline-flex items-center justify-center w-8 h-8 text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                            title="Editar OS">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>

                                        @if (auth()->user()->canDelete())
                                            <form action="{{ route('ordens.destroy', $ordem->id) }}" method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir a OS #{{ $ordem->numero_os }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-action inline-flex items-center justify-center w-8 h-8 text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                                                    title="Excluir OS">
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @if (request('search') || request('status') || request('cliente_id') || request('inicio') || request('fim'))
                                            <p class="text-sm font-medium text-gray-500">Nenhuma ordem encontrada com
                                                os filtros aplicados</p>
                                            <a href="{{ route('ordens.index') }}"
                                                class="text-xs text-blue-600 hover:underline">Limpar filtros e ver
                                                todas</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">Nenhuma ordem de serviço
                                                cadastrada ainda</p>
                                            <a href="{{ route('ordens.create') }}"
                                                class="text-xs text-blue-600 hover:underline">Criar a primeira OS</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if (isset($ordens) && method_exists($ordens, 'hasPages') && $ordens->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $ordens->links() }}
                </div>
            @endif

        </div>

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
                                <h3 class="text-base font-bold text-gray-900">
                                    Exportar Documentos em PDF <span class="text-blue-600"
                                        x-text="selectedOrdem.numero ? ('#' + selectedOrdem.numero) : ''"></span>
                                </h3>
                                <p class="text-xs text-gray-500"
                                    x-text="selectedOrdem.cliente ? ('Cliente: ' + selectedOrdem.cliente) : 'Selecione o tipo de documento que deseja gerar'">
                                </p>
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
                        <a :href="selectedOrdem.pdfUrl" target="_blank" @click="exportPdfModalOpen = false"
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
                                    assinatura do cliente.
                                </p>
                            </div>
                        </a>

                        {{-- Opção 2: PDF do Laudo de Vistoria --}}
                        <a :href="selectedOrdem.pdfVistoriaUrl" target="_blank" @click="exportPdfModalOpen = false"
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
