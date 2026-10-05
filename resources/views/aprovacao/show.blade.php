<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aprovação de Ordem de Serviço - {{ $ordem->empresa->nome_fantasia }}</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Scripts/Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F8FAFC;
        }

        .glow-green {
            box-shadow: 0 4px 25px rgba(34, 197, 94, 0.18);
        }

        .glow-red {
            box-shadow: 0 4px 25px rgba(239, 68, 68, 0.18);
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
    </style>
</head>

<body
    class="min-h-screen text-slate-800 antialiased py-3.5 px-3 sm:py-8 sm:px-6 lg:px-8 {{ !$expirado && !$ordem->isApprovalResponded() ? 'pb-28 sm:pb-8' : '' }}">

    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">

        {{-- Cabeçalho da Empresa e OS --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6">
            {{-- Topo: Logotipo à esquerda e Badge da OS à direita --}}
            <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    @if ($ordem->empresa->logo_url)
                        <img src="{{ $ordem->empresa->logo_url }}" alt="{{ $ordem->empresa->nome_fantasia }}"
                            class="h-10 sm:h-14 w-auto max-w-[140px] sm:max-w-[200px] object-contain object-left rounded-lg shrink-0"
                            onerror="this.style.display='none'">
                    @else
                        <div
                            class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center text-lg sm:text-xl font-bold shrink-0 shadow-sm">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                    @endif
                </div>

                <div class="text-right shrink-0">
                    <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block">Ordem
                        de Serviço</span>
                    <span
                        class="text-lg sm:text-2xl font-black text-blue-600 block leading-tight">#{{ $ordem->numero_os }}</span>
                </div>
            </div>

            {{-- Dados da Empresa e Contato --}}
            <div class="pt-3">
                <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                    {{ $ordem->empresa->nome_fantasia }}
                </h1>
                @if ($ordem->empresa->razao_social && $ordem->empresa->razao_social !== $ordem->empresa->nome_fantasia)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $ordem->empresa->razao_social }}</p>
                @endif

                {{-- Contatos Rápidos Clicáveis --}}
                @if ($ordem->empresa->telefone || $ordem->empresa->email)
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @if ($ordem->empresa->telefone)
                            @php
                                $phoneClean = preg_replace('/\D/', '', $ordem->empresa->telefone);
                            @endphp
                            <a href="tel:{{ $phoneClean }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-95 px-3 py-1.5 rounded-full transition">
                                <i class="bi bi-telephone-fill text-slate-500 text-xs"></i>
                                <span>{{ $ordem->empresa->telefone }}</span>
                            </a>
                        @endif
                        @if ($ordem->empresa->email)
                            <a href="mailto:{{ $ordem->empresa->email }}"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 active:scale-95 px-3 py-1.5 rounded-full transition">
                                <i class="bi bi-envelope-fill text-slate-500 text-xs"></i>
                                <span>{{ $ordem->empresa->email }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Notificações de Sucesso ou Erro --}}
        @if (session('success'))
            <div
                class="p-3.5 sm:p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                <i class="bi bi-check-circle-fill text-green-600 text-lg shrink-0"></i>
                <div class="leading-snug">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div
                class="p-3.5 sm:p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg shrink-0"></i>
                <div class="leading-snug">{{ session('error') }}</div>
            </div>
        @endif

        {{-- Status da Aprovação / Painel Principal de Ação --}}
        @if ($expirado && !$ordem->isApprovalResponded())
            <div
                class="bg-white rounded-2xl border border-amber-200 shadow-sm p-4 sm:p-6 border-t-4 border-t-amber-500">
                <div class="flex items-start gap-3.5 sm:gap-4">
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 text-xl">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="space-y-1">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Link de aprovação
                            expirado</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            O prazo de validade deste orçamento (15 dias) expirou. Por favor, entre em contato
                            diretamente com a oficina para obter um orçamento atualizado.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($ordem->isApprovalResponded())
            @if ($ordem->approval_status === 'approved')
                <div
                    class="bg-white rounded-2xl border border-green-200 shadow-sm p-4 sm:p-6 glow-green border-t-4 border-t-green-500">
                    <div class="flex items-start gap-3.5 sm:gap-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shrink-0 text-xl">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Esta Ordem de Serviço já foi respondida</h2>
                            <p class="text-xs sm:text-sm text-slate-600">
                                Resposta: <strong class="text-green-600 font-bold">APROVADA</strong>
                            </p>
                            <p class="text-[11px] sm:text-xs text-slate-400">
                                Respondida em {{ $ordem->approval_response_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="bg-white rounded-2xl border border-red-200 shadow-sm p-4 sm:p-6 glow-red border-t-4 border-t-red-500">
                    <div class="flex items-start gap-3.5 sm:gap-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 text-xl">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="space-y-1 flex-1">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Esta Ordem de
                                Serviço já foi respondida</h2>
                            <p class="text-xs sm:text-sm text-slate-600">
                                Resposta: <strong class="text-red-600 font-bold">REPROVADA</strong>
                            </p>
                            @if ($ordem->approval_comment)
                                <div
                                    class="mt-2 p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs text-slate-600 italic">
                                    "{{ $ordem->approval_comment }}"
                                </div>
                            @endif
                            <p class="text-[11px] sm:text-xs text-slate-400 mt-2">
                                Respondida em {{ $ordem->approval_response_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            {{-- Painel de Ações de Aprovação --}}
            <div id="painelAprovacao" class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6">
                <div class="mb-4 sm:mb-5">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Sua aprovação é necessária</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Revise o orçamento detalhado abaixo e confirme
                        sua aprovação ou reprovação.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <form action="{{ route('aprovacao.approve', $ordem->approval_token) }}" method="POST"
                        onsubmit="return confirm('Confirma a aprovação desta Ordem de Serviço?');">
                        @csrf
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl transition-all flex items-center justify-center gap-2.5 shadow-sm hover:shadow text-sm sm:text-base">
                            <i class="bi bi-check-circle-fill text-lg sm:text-xl"></i>
                            <span>Aprovar Ordem de Serviço</span>
                        </button>
                    </form>
                    <button type="button" onclick="openRejectModal()"
                        class="w-full bg-slate-100 hover:bg-red-50 text-slate-700 hover:text-red-700 active:scale-[0.99] font-bold py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl border border-slate-200 hover:border-red-200 transition-all flex items-center justify-center gap-2.5 text-sm sm:text-base">
                        <i class="bi bi-x-circle text-lg sm:text-xl"></i>
                        <span>Reprovar Ordem de Serviço</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Detalhes do Cliente e Veículo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-6">
            {{-- Cliente --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-3.5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-person-fill text-slate-500"></i> Dados do Cliente
                </h3>
                <div class="space-y-2.5 text-xs sm:text-sm">
                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium">Nome</span>
                        <span class="font-bold text-slate-900 text-sm sm:text-base">{{ $ordem->cliente->nome }}</span>
                    </div>
                    @if ($ordem->cliente->cpf_cnpj)
                        @php
                            $doc = preg_replace('/\D/', '', $ordem->cliente->cpf_cnpj);
                            if (strlen($doc) === 11) {
                                $docFormatado = substr($doc, 0, 3) . '.***.***-' . substr($doc, -2);
                            } elseif (strlen($doc) === 14) {
                                $docFormatado = substr($doc, 0, 2) . '.***.***/****-' . substr($doc, -2);
                            } else {
                                $docFormatado = '***';
                            }
                        @endphp
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">CPF/CNPJ</span>
                            <span class="font-medium text-slate-700">{{ $docFormatado }}</span>
                        </div>
                    @endif
                    @if ($ordem->cliente->email)
                        @php
                            $emailPartes = explode('@', $ordem->cliente->email);
                            $emailMascarado = substr($emailPartes[0], 0, 2) . '***@' . ($emailPartes[1] ?? '');
                        @endphp
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">E-mail</span>
                            <span class="font-medium text-slate-700 break-all">{{ $emailMascarado }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Veículo --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-3.5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-car-front-fill text-slate-500"></i> Dados do Veículo
                </h3>
                <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-[11px] text-slate-400 block font-medium">Veículo</span>
                        <span
                            class="font-bold text-slate-900 text-sm sm:text-base leading-tight">{{ $ordem->veiculo->marca }}
                            {{ $ordem->veiculo->modelo }}</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-[11px] text-slate-400 block font-medium">Placa</span>
                        <span
                            class="inline-flex items-center gap-1.5 font-black text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200 uppercase tracking-wider text-xs sm:text-sm">
                            {{ $ordem->veiculo->placa }}
                        </span>
                    </div>
                    @if ($ordem->veiculo->ano)
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Ano</span>
                            <span class="font-medium text-slate-700">{{ $ordem->veiculo->ano }}</span>
                        </div>
                    @endif
                    @if ($ordem->veiculo->cor)
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Cor</span>
                            <span class="font-medium text-slate-700">{{ $ordem->veiculo->cor }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Vistoria & Fotos de Entrada do Veículo --}}
        @if ($ordem->problemas_previos || $ordem->fotos->count())
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-3.5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-camera-fill text-blue-600"></i> Vistoria & Condição Prévia
                </h3>

                @if ($ordem->problemas_previos)
                    <div
                        class="p-3 sm:p-3.5 bg-amber-50 border border-amber-200/90 rounded-xl text-xs sm:text-sm text-amber-950">
                        <span class="font-bold block mb-1 text-[11px] uppercase tracking-wider text-amber-800">
                            Avarias / Observações na Entrada:
                        </span>
                        <p class="whitespace-pre-line font-medium leading-relaxed">{{ $ordem->problemas_previos }}</p>
                    </div>
                @endif

                @if ($ordem->fotos->count())
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-slate-500 font-medium">Fotos Registradas
                                ({{ $ordem->fotos->count() }})</span>
                            <span class="text-[10px] text-slate-400 sm:hidden">Toque para ampliar</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                            @foreach ($ordem->fotos as $foto)
                                <a href="{{ $foto->url }}" target="_blank"
                                    class="block aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 active:opacity-80 hover:opacity-95 transition shadow-sm relative group">
                                    <img src="{{ $foto->url }}" alt="Foto do Veículo"
                                        class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition flex items-center justify-center">
                                        <span
                                            class="bg-white/90 backdrop-blur-sm text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 sm:block hidden transition">
                                            Ver foto
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Itens da Ordem de Serviço (Desktop Table + Mobile Cards) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div
                class="px-4 py-3.5 sm:px-6 sm:py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h3
                    class="text-xs sm:text-sm font-bold text-slate-800 flex items-center gap-2 uppercase tracking-wider">
                    <i class="bi bi-list-check text-slate-500 text-base"></i>
                    Serviços e Peças Detalhados
                </h3>
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">Valores em Reais (R$)</span>
            </div>

            @php
                $servicos = $ordem->itens->where('tipo_item', 'servico');
                $pecas = $ordem->itens->where('tipo_item', 'peca');
            @endphp

            {{-- Bloco de Serviços --}}
            @if ($servicos->count())
                <div class="p-4 sm:p-6 border-b border-slate-100">
                    <h4
                        class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-wrench"></i> Serviços
                    </h4>

                    {{-- Visão Mobile: Lista de Cards (Sem barra de rolagem horizontal) --}}
                    <div class="sm:hidden space-y-2.5">
                        @foreach ($servicos as $item)
                            <div class="bg-slate-50 border border-slate-100/80 rounded-xl p-3 space-y-1.5">
                                <div class="text-xs font-bold text-slate-900 leading-snug">
                                    {{ $item->descricao }}
                                </div>
                                <div
                                    class="flex items-center justify-between pt-1 border-t border-slate-200/60 text-xs">
                                    <span class="text-slate-500 font-medium">
                                        {{ $item->quantidade }} un &times; R$
                                        {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                    </span>
                                    <span class="font-extrabold text-slate-950">
                                        R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Visão Desktop: Tabela Tabular Tradicional --}}
                    <div class="hidden sm:block overflow-x-auto">
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
                                @foreach ($servicos as $item)
                                    <tr>
                                        <td class="py-3 text-slate-900 font-medium">{{ $item->descricao }}</td>
                                        <td class="py-3 text-center text-slate-500">{{ $item->quantidade }}</td>
                                        <td class="py-3 text-right text-slate-500">R$
                                            {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                        <td class="py-3 text-right font-semibold text-slate-950">R$
                                            {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Bloco de Peças --}}
            @if ($pecas->count())
                <div class="p-4 sm:p-6">
                    <h4
                        class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-box-seam"></i> Peças
                    </h4>

                    {{-- Visão Mobile: Lista de Cards --}}
                    <div class="sm:hidden space-y-2.5">
                        @foreach ($pecas as $item)
                            <div class="bg-slate-50 border border-slate-100/80 rounded-xl p-3 space-y-1.5">
                                <div class="text-xs font-bold text-slate-900 leading-snug">
                                    {{ $item->descricao }}
                                </div>
                                <div
                                    class="flex items-center justify-between pt-1 border-t border-slate-200/60 text-xs">
                                    <span class="text-slate-500 font-medium">
                                        {{ $item->quantidade }} un &times; R$
                                        {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                    </span>
                                    <span class="font-extrabold text-slate-950">
                                        R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Visão Desktop: Tabela Tabular Tradicional --}}
                    <div class="hidden sm:block overflow-x-auto">
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
                                @foreach ($pecas as $item)
                                    <tr>
                                        <td class="py-3 text-slate-900 font-medium">{{ $item->descricao }}</td>
                                        <td class="py-3 text-center text-slate-500">{{ $item->quantidade }}</td>
                                        <td class="py-3 text-right text-slate-500">R$
                                            {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                        <td class="py-3 text-right font-semibold text-slate-950">R$
                                            {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if (!$ordem->itens->count())
                <div class="p-8 text-center text-slate-400">
                    <i class="bi bi-inbox text-3xl mb-1 block"></i>
                    <p class="text-sm">Nenhum serviço ou peça adicionada à Ordem de Serviço.</p>
                </div>
            @endif

            {{-- Rodapé / Total Geral --}}
            <div
                class="bg-slate-50 border-t border-slate-100 p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-slate-500 text-xs text-center sm:text-left order-2 sm:order-1 leading-relaxed">
                    Valor sujeito a alterações caso novos serviços sejam solicitados.
                </div>
                <div
                    class="text-center sm:text-right shrink-0 order-1 sm:order-2 w-full sm:w-auto bg-white sm:bg-transparent p-3 sm:p-0 rounded-xl border sm:border-0 border-slate-200/80 space-y-1">
                    @if ($ordem->temDesconto())
                        <div class="text-xs text-slate-500 flex sm:justify-end gap-3 justify-between">
                            <span>Subtotal:</span>
                            <span class="font-semibold text-slate-700">R$ {{ number_format($ordem->subtotal ?: ($ordem->valor_total + $ordem->valor_desconto), 2, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-emerald-600 font-medium flex sm:justify-end gap-3 justify-between">
                            <span>Desconto aplicado:</span>
                            <span class="font-bold">- R$ {{ number_format($ordem->valor_desconto, 2, ',', '.') }} ({{ $ordem->desconto_tipo === 'porcentagem' ? number_format($ordem->desconto_valor, 0) . '%' : 'fixo' }})</span>
                        </div>
                    @endif
                    <span class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block">Valor
                        Total Geral</span>
                    <span class="text-2xl sm:text-3xl font-black text-blue-700 tracking-tight">R$
                        {{ number_format($ordem->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Observações --}}
        @if ($ordem->observacoes)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-chat-left-text-fill text-slate-500"></i> Observações da Oficina
                </h3>
                <div
                    class="p-3.5 sm:p-4 bg-slate-50 border border-slate-100 rounded-xl text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $ordem->observacoes }}
                </div>
            </div>
        @endif

        {{-- Footer simples da página --}}
        <div class="text-center text-xs text-slate-400 pt-2 pb-6">
            Sistema de Gestão MecDesk &middot; &copy; {{ date('Y') }} {{ $ordem->empresa->nome_fantasia }}.
        </div>

    </div>

    {{-- BARRA FIXA INFERIOR NO MOBILE (STICKY ACTION BAR) --}}
    @if (!$expirado && !$ordem->isApprovalResponded())
        <div
            class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] z-40 safe-bottom">
            <div class="px-3.5 py-2.5 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total</span>
                    <span class="text-lg font-black text-blue-700 tracking-tight truncate block leading-tight">
                        R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                    </span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="openRejectModal()"
                        class="px-3 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs bg-slate-50 hover:bg-red-50 hover:text-red-700 transition active:scale-95 flex items-center gap-1.5">
                        <i class="bi bi-x-circle text-sm text-red-600"></i>
                        <span>Recusar</span>
                    </button>
                    <form action="{{ route('aprovacao.approve', $ordem->approval_token) }}" method="POST"
                        onsubmit="return confirm('Confirma a aprovação desta Ordem de Serviço?');">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm transition active:scale-95 flex items-center gap-1.5">
                            <i class="bi bi-check-circle-fill text-sm"></i>
                            <span>Aprovar OS</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Reprovação Otimizado para Mobile (Bottom Sheet no Smartphone) --}}
    @if (!$ordem->isApprovalResponded())
        <div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4 text-center">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                    onclick="closeRejectModal()"></div>

                <div
                    class="relative bg-white rounded-t-2xl sm:rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full sm:max-w-lg border border-slate-200 z-10">
                    <form action="{{ route('aprovacao.reject', $ordem->approval_token) }}" method="POST">
                        @csrf
                        <div class="bg-white p-5 sm:p-6 space-y-4">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div
                                    class="shrink-0 flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-red-100 text-red-600 text-xl">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-tight"
                                        id="modal-title">
                                        Reprovar Ordem de Serviço
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                                        Informe o motivo da reprovação abaixo para que a oficina possa entender e
                                        ajustar o orçamento.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label for="approval_comment"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Motivo
                                    da reprovação</label>
                                <textarea id="approval_comment" name="approval_comment" rows="4" required
                                    placeholder="Ex: Valor de alguma peça alto / Desejo alterar itens..."
                                    class="w-full px-3.5 py-3 text-base sm:text-sm border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition placeholder-slate-400 text-slate-800 bg-white"
                                    maxlength="1000"></textarea>
                            </div>
                        </div>
                        <div
                            class="bg-slate-50 px-5 py-3.5 sm:px-6 sm:py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-100 safe-bottom">
                            <button type="button" onclick="closeRejectModal()"
                                class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-slate-300 px-4 py-3 sm:py-2.5 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 transition active:scale-95">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 sm:py-2.5 bg-red-600 text-sm font-bold text-white hover:bg-red-700 transition active:scale-95">
                                Confirmar Reprovação
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
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    const textarea = document.getElementById('approval_comment');
                    if (textarea) textarea.focus();
                }, 100);
            }
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
</body>

</html>
