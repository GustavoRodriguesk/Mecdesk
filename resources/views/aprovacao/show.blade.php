<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aprovação de Ordem de Serviço - {{ $ordem->empresa->nome_fantasia }}</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Scripts/Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #F1F5F9; }
        .glow-green { box-shadow: 0 4px 20px rgba(34, 197, 94, 0.15); }
        .glow-red { box-shadow: 0 4px 20px rgba(239, 68, 68, 0.15); }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Cabeçalho da Empresa --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-center sm:text-left">
                @if($ordem->empresa->logo)
                    <img src="{{ asset('storage/' . $ordem->empresa->logo) }}" alt="Logo {{ $ordem->empresa->nome_fantasia }}" class="h-16 w-auto max-w-[180px] object-contain rounded-lg">
                @else
                    <div class="w-16 h-16 rounded-xl bg-slate-900 text-white flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-bold text-slate-900 leading-tight">{{ $ordem->empresa->nome_fantasia }}</h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $ordem->empresa->razao_social }}</p>
                    @if($ordem->empresa->telefone || $ordem->empresa->email)
                        <p class="text-xs text-slate-400 mt-1 flex flex-wrap justify-center sm:justify-start gap-2">
                            @if($ordem->empresa->telefone)
                                <span><i class="bi bi-telephone"></i> {{ $ordem->empresa->telefone }}</span>
                            @endif
                            @if($ordem->empresa->email)
                                <span><i class="bi bi-envelope"></i> {{ $ordem->empresa->email }}</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
            <div class="text-center sm:text-right shrink-0">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Ordem de Serviço</span>
                <span class="text-2xl font-black text-blue-600 block mt-0.5">#{{ $ordem->numero_os }}</span>
            </div>
        </div>

        {{-- Notificações de Sucesso ou Erro --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- Status da Aprovação / Ações --}}
        @if($ordem->isApprovalResponded())
            @if($ordem->approval_status === 'approved')
                <div class="bg-white rounded-xl border border-green-200 shadow-sm p-6 glow-green border-t-4 border-t-green-500">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 text-xl">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-lg font-bold text-slate-900">Esta Ordem de Serviço já foi respondida</h2>
                            <p class="text-sm text-slate-600">
                                Resposta: <strong class="text-green-600">APROVADA</strong>
                            </p>
                            <p class="text-xs text-slate-400">
                                Respondida em {{ $ordem->approval_response_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6 glow-red border-t-4 border-t-red-500">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 text-xl">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="space-y-1 flex-1">
                            <h2 class="text-lg font-bold text-slate-900">Esta Ordem de Serviço já foi respondida</h2>
                            <p class="text-sm text-slate-600">
                                Resposta: <strong class="text-red-600">REPROVADA</strong>
                            </p>
                            @if($ordem->approval_comment)
                                <div class="mt-2 p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs text-slate-600 italic">
                                    "{{ $ordem->approval_comment }}"
                                </div>
                            @endif
                            <p class="text-xs text-slate-400 mt-2">
                                Respondida em {{ $ordem->approval_response_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-2">Sua aprovação é necessária</h2>
                <p class="text-sm text-slate-500 mb-6">Revise as informações da Ordem de Serviço abaixo e selecione uma das opções para nos enviar sua resposta.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <form action="{{ route('aprovacao.approve', $ordem->approval_token) }}" method="POST" onsubmit="return confirm('Confirma a aprovação desta Ordem de Serviço?');">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-150 flex items-center justify-center gap-2 shadow hover:shadow-lg text-lg">
                            <i class="bi bi-check2-circle text-xl"></i>
                            Aprovar Ordem de Serviço
                        </button>
                    </form>
                    <button type="button" onclick="openRejectModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-150 flex items-center justify-center gap-2 shadow hover:shadow-lg text-lg">
                        <i class="bi bi-x-circle text-xl"></i>
                        Reprovar Ordem de Serviço
                    </button>
                </div>
            </div>
        @endif

        {{-- Detalhes do Cliente e Veículo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Cliente --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-person"></i> Dados do Cliente
                </h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-xs text-slate-400 block">Nome</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $ordem->cliente->nome }}</span>
                    </div>
                    @if($ordem->cliente->cpf_cnpj)
                        <div>
                            <span class="text-xs text-slate-400 block">CPF/CNPJ</span>
                            <span class="text-sm font-medium text-slate-700">{{ $ordem->cliente->cpf_cnpj }}</span>
                        </div>
                    @endif
                    @if($ordem->cliente->email)
                        <div>
                            <span class="text-xs text-slate-400 block">E-mail</span>
                            <span class="text-sm font-medium text-slate-700">{{ $ordem->cliente->email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Veículo --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-car-front"></i> Dados do Veículo
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-slate-400 block">Veículo</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block">Placa</span>
                        <span class="text-sm font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 inline-block uppercase mt-0.5">{{ $ordem->veiculo->placa }}</span>
                    </div>
                    @if($ordem->veiculo->ano)
                        <div>
                            <span class="text-xs text-slate-400 block">Ano</span>
                            <span class="text-sm font-medium text-slate-700">{{ $ordem->veiculo->ano }}</span>
                        </div>
                    @endif
                    @if($ordem->veiculo->cor)
                        <div>
                            <span class="text-xs text-slate-400 block">Cor</span>
                            <span class="text-sm font-medium text-slate-700">{{ $ordem->veiculo->cor }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Itens da Ordem de Serviço --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-list-check text-slate-500"></i>
                    Serviços e Peças Detalhados
                </h3>
            </div>

            {{-- Serviços --}}
            @php
                $servicos = $ordem->itens->where('tipo_item', 'servico');
                $pecas = $ordem->itens->where('tipo_item', 'peca');
            @endphp

            @if($servicos->count())
                <div class="p-6 border-b border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-wrench"></i> Serviços Executados
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-slate-400 text-xs border-b border-slate-100 text-left">
                                    <th class="pb-2 font-medium">Descrição</th>
                                    <th class="pb-2 font-medium text-center">Qtd</th>
                                    <th class="pb-2 font-medium text-right">V. Unitário</th>
                                    <th class="pb-2 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($servicos as $item)
                                    <tr>
                                        <td class="py-3 text-slate-900 font-medium">{{ $item->descricao }}</td>
                                        <td class="py-3 text-center text-slate-500">{{ $item->quantidade }}</td>
                                        <td class="py-3 text-right text-slate-500">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                        <td class="py-3 text-right font-semibold text-slate-950">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Peças --}}
            @if($pecas->count())
                <div class="p-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-box-seam"></i> Peças Aplicadas
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-slate-400 text-xs border-b border-slate-100 text-left">
                                    <th class="pb-2 font-medium">Descrição</th>
                                    <th class="pb-2 font-medium text-center">Qtd</th>
                                    <th class="pb-2 font-medium text-right">V. Unitário</th>
                                    <th class="pb-2 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pecas as $item)
                                    <tr>
                                        <td class="py-3 text-slate-900 font-medium">{{ $item->descricao }}</td>
                                        <td class="py-3 text-center text-slate-500">{{ $item->quantidade }}</td>
                                        <td class="py-3 text-right text-slate-500">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                        <td class="py-3 text-right font-semibold text-slate-950">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(!$ordem->itens->count())
                <div class="p-8 text-center text-slate-400">
                    <i class="bi bi-inbox text-3xl mb-1 block"></i>
                    <p class="text-sm">Nenhum serviço ou peça adicionada à Ordem de Serviço.</p>
                </div>
            @endif

            {{-- Rodapé / Total --}}
            <div class="bg-slate-50 border-t border-slate-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-slate-500 text-xs text-center sm:text-left">
                    Valor sujeito a alterações caso novos serviços sejam solicitados.
                </div>
                <div class="text-right shrink-0">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Valor Total Geral</span>
                    <span class="text-2xl font-black text-blue-700">R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Observações --}}
        @if($ordem->observacoes)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-chat-left-text"></i> Observações da Oficina
                </h3>
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $ordem->observacoes }}
                </div>
            </div>
        @endif

        {{-- Footer simples da página --}}
        <div class="text-center text-xs text-slate-400 pt-4 pb-8">
            Sistema de Gestão MecDesk &middot; &copy; {{ date('Y') }} {{ $ordem->empresa->nome_fantasia }}. Todos os direitos reservados.
        </div>

    </div>

    @if(!$ordem->isApprovalResponded())
    {{-- Modal de Reprovação --}}
    <div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-60 transition-opacity" onclick="closeRejectModal()"></div>

            <!-- Trick to center the modal contents -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form action="{{ route('aprovacao.reject', $ordem->approval_token) }}" method="POST">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="sm:flex sm:items-start gap-4">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-600 sm:mx-0 text-xl">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    Reprovar Ordem de Serviço
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Por favor, informe o motivo da reprovação abaixo. Isso nos ajudará a entender como proceder com o seu veículo.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="approval_comment" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Motivo da reprovação</label>
                            <textarea id="approval_comment" name="approval_comment" rows="4" required placeholder="Ex: Valor das peças está muito alto / Desejo remover o serviço X..." class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition placeholder-slate-400 text-slate-800 bg-white" maxlength="1000"></textarea>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reprovar
                        </button>
                        <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2.5 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
        function openRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>
