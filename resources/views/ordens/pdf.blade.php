<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ordem de Serviço - {{ $ordem->numero_os }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5pt;
            color: #1f2937;
            line-height: 1.35;
            padding: 22px 34px 18px 34px;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: DejaVu Sans, sans-serif;
        }

        td, th, span, div, p {
            font-family: DejaVu Sans, sans-serif;
        }

        /* ── HEADER ── */
        .header {
            width: 100%;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo-img {
            width: 130px;
            height: auto;
        }

        .empresa {
            text-align: right;
        }

        .empresa h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }

        .empresa p {
            font-size: 8pt;
            color: #6b7280;
            line-height: 1.45;
        }

        /* ── BANNER OS ── */
        .os-banner {
            width: 100%;
            background: #111827;
            padding: 5px 12px;
            margin-bottom: 2px;
            border-radius: 4px;
        }

        .os-banner td {
            vertical-align: middle;
        }

        .os-titulo {
            color: #ffffff;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .os-numero {
            color: #ffffff;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-align: right;
        }

        .os-meta {
            font-size: 7.2pt;
            color: #6b7280;
            padding-left: 2px;
            padding-top: 3px;
            padding-bottom: 5px;
            display: block;
        }

        /* ── CARD WRAPPER ── */
        .card-wrapper {
            width: 100%;
            border: 1px solid #e5e7eb;
            margin-bottom: 5px;
            border-radius: 4px;
            overflow: hidden;
        }

        .card-header {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            padding: 3.5px 8px;
            font-size: 7.2pt;
            font-weight: bold;
            color: #374151;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
        }

        .card-body {
            padding: 5px 8px;
        }

        /* ── DOIS PAINÉIS LADO A LADO ── */
        .two-col {
            width: 100%;
            border-collapse: collapse;
        }

        .two-col td {
            vertical-align: top;
            width: 50%;
            padding: 0 8px 0 0;
        }

        .two-col td+td {
            padding: 0 0 0 8px;
            border-left: 1px solid #e5e7eb;
        }

        .field-group {
            margin-bottom: 2.5px;
        }

        .field-label {
            font-size: 6.8pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .field-value {
            font-size: 8.2pt;
            color: #111827;
            font-weight: bold;
        }

        .field-value-light {
            font-size: 8.2pt;
            color: #374151;
            font-weight: normal;
        }

        /* ── OBSERVAÇÕES / PROBLEMA ── */
        .problema-text {
            font-size: 7.8pt;
            color: #1f2937;
            background: #f9fafb;
            border-left: 3px solid #374151;
            padding: 4px 8px;
            line-height: 1.3;
            border-radius: 0 4px 4px 0;
        }

        /* ── TABELA ITENS ── */
        .itens-table {
            width: 100%;
            border-collapse: collapse;
        }

        .itens-table thead tr {
            background: #f3f4f6;
        }

        .itens-table thead th {
            color: #374151;
            font-size: 6.8pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 3.5px 7px;
            text-align: left;
        }

        .itens-table thead th.right {
            text-align: right;
        }

        .itens-table thead th.center {
            text-align: center;
        }

        .itens-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .itens-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .itens-table tbody tr.last {
            border-bottom: none;
        }

        .itens-table tbody td {
            padding: 3.5px 7px;
            font-size: 7.2pt;
            color: #374151;
            vertical-align: middle;
        }

        .itens-table tbody td.right {
            text-align: right;
            font-weight: bold;
            color: #111827;
        }

        .itens-table tbody td.center {
            text-align: center;
        }

        /* ── TIPO DE ITEM (NEUTRO E DISCRETO) ── */
        .badge-tipo,
        .badge-peca {
            display: inline-block;
            font-size: 6.2pt;
            font-weight: bold;
            padding: 1px 5px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            border-radius: 3px;
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        /* ── ÁREA DE TOTAIS (LEVE E LIMPA) ── */
        .total-bar {
            width: 100%;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 5px 8px;
            border-bottom-left-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        .total-bar td {
            padding: 1.5px 0;
            vertical-align: middle;
        }

        .subtotal-label {
            color: #6b7280;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .subtotal-valor {
            color: #374151;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .desconto-label {
            color: #059669;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .desconto-valor {
            color: #059669;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .total-divider {
            border-top: 1px dashed #d1d5db;
        }

        .total-label {
            color: #111827;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            padding-top: 2px;
        }

        .total-valor {
            color: #111827;
            font-size: 10.5pt;
            font-weight: bold;
            white-space: nowrap;
            padding-top: 2px;
        }

        /* ── ASSINATURA DO CLIENTE ── */
        .assinatura-section {
            width: 100%;
            margin-top: 42px;
            margin-bottom: 4px;
            text-align: center;
        }

        .assinatura-wrap {
            width: 260px;
            margin: 0 auto;
            text-align: center;
        }

        .assinatura-linha {
            width: 100%;
            margin: 0 auto 3px auto;
            border-bottom: 1px solid #111827;
            height: 16px;
        }

        .assinatura-label {
            font-size: 7.2pt;
            color: #111827;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .assinatura-sub {
            font-size: 6.2pt;
            color: #6b7280;
            margin-top: 1px;
        }

        /* ── RODAPÉ ── */
        .rodape {
            margin-top: 6px;
            padding-top: 3px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            color: #9ca3af;
            font-size: 6.5pt;
        }

        .rodape-strong {
            color: #4b5563;
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table class="header" cellpadding="0" cellspacing="0">
        <tr>
            <td width="135">
                @if ($empresa->logo_path)
                    <img src="{{ $empresa->logo_path }}" class="logo-img">
                @elseif (file_exists(public_path('img/logo.png')))
                    <img src="{{ public_path('img/logo.png') }}" class="logo-img">
                @endif
            </td>
            <td class="empresa">
                <h1>{{ $empresa->nome_fantasia }}</h1>
                <p>
                    @if ($empresa->razao_social)
                        {{ $empresa->razao_social }}
                    @endif
                    @if ($empresa->cnpj)
                        &nbsp;|&nbsp; CNPJ: {{ $empresa->cnpj }}
                    @endif
                </p>
                <p>
                    @if ($empresa->telefone)
                        Tel: {{ $empresa->telefone }}
                    @endif
                    @if ($empresa->whatsapp)
                        &nbsp;|&nbsp; WhatsApp: {{ $empresa->whatsapp }}
                    @endif
                    @if ($empresa->email)
                        &nbsp;|&nbsp; {{ $empresa->email }}
                    @endif
                </p>
                @if ($empresa->logradouro)
                    <p>{{ $empresa->logradouro }}, {{ $empresa->numero }}
                        @if ($empresa->bairro)
                            {{ $empresa->bairro }}
                        @endif
                        &#8212; {{ $empresa->cidade }}/{{ $empresa->estado }}
                        @if ($empresa->cep)
                            &nbsp;|&nbsp; CEP {{ $empresa->cep }}
                        @endif
                    </p>
                @endif
            </td>
        </tr>
    </table>

    {{-- BANNER OS --}}
    <table class="os-banner" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="os-titulo">ORDEM DE SERVIÇO / ORÇAMENTO</span></td>
            <td style="text-align: right;"><span class="os-numero">{{ $ordem->numero_os }}</span></td>
        </tr>
    </table>
    <div class="os-meta">
        <strong>Data de Entrada:</strong>
        {{ optional($ordem->data_entrada ?? $ordem->created_at)->format('d/m/Y H:i') }}
        @if ($ordem->funcionario)
            &nbsp;&nbsp;&bull;&nbsp;&nbsp;<strong>Mecânico / Responsável:</strong> {{ $ordem->funcionario->name }}
        @endif
    </div>

    {{-- CLIENTE E VEÍCULO --}}
    <table class="card-wrapper">
        <tr>
            <td>
                <div class="card-header">Cliente &amp; Veículo</div>
                <div class="card-body">
                    <table class="two-col">
                        <tr>
                            <td>
                                <div class="field-group">
                                    <div class="field-label">Cliente</div>
                                    <div class="field-value">{{ $ordem->cliente->nome }}</div>
                                </div>
                                <div class="field-group">
                                    <div class="field-label">Telefone</div>
                                    <div class="field-value-light">
                                        {{ $ordem->cliente->telefone_formatado ?? ($ordem->cliente->telefone ?: '-') }}
                                    </div>
                                </div>
                                <div class="field-group" style="margin-bottom: 0;">
                                    <div class="field-label">CPF / CNPJ</div>
                                    <div class="field-value-light">
                                        {{ $ordem->cliente->cpf_cnpj_formatado ?? ($ordem->cliente->cpf_cnpj ?: '-') }}
                                    </div>
                                </div>
                                @if ($ordem->funcionario)
                                    <div class="field-group" style="margin-top: 2.5px; margin-bottom: 0;">
                                        <div class="field-label">Mecânico / Responsável</div>
                                        <div class="field-value-light">{{ $ordem->funcionario->name }}</div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="field-group">
                                    <div class="field-label">Veículo</div>
                                    <div class="field-value">{{ $ordem->veiculo->marca }}
                                        {{ $ordem->veiculo->modelo }} ({{ $ordem->veiculo->ano ?: '-' }})</div>
                                </div>
                                <div class="field-group">
                                    <div class="field-label">Placa &nbsp;|&nbsp; Cor</div>
                                    <div class="field-value-light">
                                        {{ $ordem->veiculo->placa_formatada ?? $ordem->veiculo->placa }} &nbsp;|&nbsp;
                                        {{ $ordem->veiculo->cor ?: '-' }}</div>
                                </div>
                                <div class="field-group" style="margin-bottom: 0;">
                                    <div class="field-label">KM Entrada</div>
                                    <div class="field-value-light">
                                        {{ $ordem->veiculo->quilometragem ? number_format($ordem->veiculo->quilometragem, 0, ',', '.') . ' km' : '-' }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- PROBLEMA RELATADO --}}
    @if ($ordem->descricao_problema)
        <table class="card-wrapper">
            <tr>
                <td>
                    <div class="card-header">Problema Relatado / Queixa do Cliente</div>
                    <div class="card-body">
                        <div class="problema-text">{{ $ordem->descricao_problema }}</div>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    {{-- OBSERVAÇÕES / AVARIAS (se houver) --}}
    @if ($ordem->problemas_previos || $ordem->observacoes)
        <table class="card-wrapper">
            <tr>
                <td>
                    <div class="card-header">Avarias Prévias &amp; Observações</div>
                    <div class="card-body">
                        @if ($ordem->problemas_previos)
                            <div class="field-group"
                                style="{{ $ordem->observacoes ? 'margin-bottom: 3px;' : 'margin-bottom: 0;' }}">
                                <div class="field-label">Avarias Prévias / Vistoria de Entrada</div>
                                <div class="problema-text">{{ $ordem->problemas_previos }}</div>
                            </div>
                        @endif
                        @if ($ordem->observacoes)
                            <div class="field-group" style="margin-bottom: 0;">
                                <div class="field-label">Observações Gerais</div>
                                <div class="problema-text">{{ $ordem->observacoes }}</div>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    @endif

    {{-- SERVIÇOS E PEÇAS --}}
    <table class="card-wrapper">
        <tr>
            <td>
                <div class="card-header">Serviços Executados &amp; Peças Aplicadas</div>
                <table class="itens-table">
                    <thead>
                        <tr>
                            <th style="width: 13%;">Tipo</th>
                            <th>Descrição</th>
                            <th class="center" style="width: 10%;">Qtd.</th>
                            <th class="right" style="width: 18%;">Valor Unit.</th>
                            <th class="right" style="width: 18%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itens = $ordem->itens;
                            $total_itens = count($itens);
                        @endphp
                        @forelse ($itens as $idx => $item)
                            <tr class="{{ $idx + 1 === $total_itens ? 'last' : '' }}">
                                <td>
                                    <span class="{{ $item->tipo_item === 'servico' ? 'badge-tipo' : 'badge-peca' }}">
                                        {{ $item->tipo_item === 'servico' ? 'Serviço' : 'Peça' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $item->descricao }}</strong>
                                </td>
                                <td class="center">{{ $item->quantidade }}</td>
                                <td class="right" style="color: #6b7280; font-weight: normal;">
                                    R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}
                                </td>
                                <td class="right">
                                    R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="last">
                                <td colspan="5"
                                    style="text-align: center; color: #9ca3af; padding: 8px; font-style: italic;">
                                    Nenhum serviço ou peça registrado nesta Ordem de Serviço.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- BARRA DE TOTAIS (VISUAL LEVE E ELEGANTE) --}}
                <table class="total-bar">
                    @if ($ordem->temDesconto())
                        <tr>
                            <td><span class="subtotal-label">Subtotal</span></td>
                            <td style="text-align: right;"><span class="subtotal-valor">R$
                                    {{ number_format($ordem->subtotal ?: $ordem->valor_total + $ordem->valor_desconto, 2, ',', '.') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="desconto-label">Desconto
                                    ({{ $ordem->desconto_tipo === 'porcentagem' ? number_format($ordem->desconto_valor, 0) . '%' : 'fixo' }})</span>
                            </td>
                            <td style="text-align: right;"><span class="desconto-valor">- R$
                                    {{ number_format($ordem->valor_desconto, 2, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="total-divider" style="padding: 2px 0;"></td>
                        </tr>
                    @endif
                    <tr>
                        <td><span class="total-label">Total da Ordem de Serviço</span></td>
                        <td style="text-align: right;"><span class="total-valor">R$
                                {{ number_format($ordem->valor_total, 2, ',', '.') }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ASSINATURA (SOMENTE DO CLIENTE) --}}
    <div class="assinatura-section">
        <div class="assinatura-wrap">
            <div class="assinatura-linha"></div>
            <div class="assinatura-label">Assinatura do Cliente</div>
            <div class="assinatura-sub">{{ $ordem->cliente->nome }}</div>
        </div>
    </div>

    {{-- RODAPÉ --}}
    <div class="rodape">
        Documento gerado pelo sistema <span class="rodape-strong">MecDesk</span> &nbsp;&bull;&nbsp;
        {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
