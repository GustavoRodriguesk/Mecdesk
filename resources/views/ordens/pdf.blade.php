<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            color: #1f2937;
            line-height: 1.5;
            padding: 28px 32px 24px 32px;
            background: #ffffff;
        }

        /* ── HEADER ── */
        .header {
            width: 100%;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header td { vertical-align: middle; }
        .logo-img { width: 60px; height: auto; }
        .empresa { text-align: right; }
        .empresa h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }
        .empresa p {
            font-size: 8pt;
            color: #6b7280;
            line-height: 1.6;
        }

        /* ── BANNER OS ── */
        .os-banner {
            width: 100%;
            background: #111827;
            border-radius: 6px;
            padding: 9px 14px;
            margin-bottom: 4px;
        }
        .os-banner td { vertical-align: middle; }
        .os-numero {
            color: #ffffff;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.4px;
        }
        .os-status {
            background: #ffffff;
            color: #111827;
            font-size: 8pt;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 4px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .os-data {
            font-size: 8pt;
            color: #9ca3af;
            padding-left: 2px;
            padding-top: 5px;
            padding-bottom: 10px;
            display: block;
        }

        /* ── CARD WRAPPER ── */
        .card-wrapper {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            margin-bottom: 10px;
        }
        .card-header {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            padding: 6px 12px;
            font-size: 8.5pt;
            font-weight: bold;
            color: #374151;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-radius: 7px 7px 0 0;
        }
        .card-body { padding: 10px 12px; }

        /* ── DOIS PAINÉIS LADO A LADO ── */
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { vertical-align: top; width: 50%; padding: 0 10px 0 0; text-transform: uppercase;}
        .two-col td + td { padding: 0 0 0 10px; border-left: 1px solid #e5e7eb; }

        .field-label {
            font-size: 7.5pt;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 1px;
        }
        .field-value {
            font-size: 9.5pt;
            color: #111827;
            font-weight: bold;
            margin-bottom: 7px;
        }
        .field-value-light {
            font-size: 9.5pt;
            color: #374151;
            font-weight: normal;
            margin-bottom: 7px;
        }

        /* ── PROBLEMA ── */
        .problema-text {
            font-size: 9.5pt;
            color: #1f2937;
            background: #f9fafb;
            border-left: 3px solid #374151;
            padding: 7px 10px;
            font-style: italic;
        }

        /* ── TABELA ITENS ── */
        .itens-table { width: 100%; border-collapse: collapse; }
        .itens-table thead tr { background: #111827; }
        .itens-table thead th {
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            padding: 7px 12px;
            text-align: left;
        }
        .itens-table thead th.right { text-align: right; }
        .itens-table thead th.center { text-align: center; }
        .itens-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .itens-table tbody tr.last { border-bottom: none; }
        .itens-table tbody td {
            padding: 7px 12px;
            font-size: 9.5pt;
            color: #374151;
        }
        .itens-table tbody td.right {
            text-align: right;
            font-weight: bold;
            color: #111827;
        }
        .itens-table tbody td.center {
            text-align: center;
            text-transform: uppercase;
        }

        .badge-tipo {
            background: #f3f4f6;
            color: #1f2937;
            border: 1px solid #d1d5db;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .badge-peca {
            background: #1f2937;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        /* ── TOTAL ── */
        .total-bar {
            width: 100%;
            background: #111827;
            border-radius: 0 0 6px 6px;
            padding: 10px;
        }
        .total-label {
            color: #9ca3af;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            width: 1080px; /* Força esta coluna a ocupar todo o espaço sobrando */
        }

        .total-valor {
            color: #ffffff;
            font-size: 15pt;
            font-weight: bold;
            white-space: nowrap; /* Impede que o valor quebre de linha */
        }

        /* ── ASSINATURA ── */
        .assinatura-section {
            margin-top: 45px;
            text-align: center;
            width: 100%;
        }
        .assinatura-linha-wrap {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
        }
        .assinatura-label {
            font-size: 8pt;
            color: #4b5563;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* ── RODAPÉ ── */
        .rodape {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            color: #9ca3af;
            font-size: 7.5pt;
        }
        .rodape-strong { color: #4b5563; font-weight: bold; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="header" cellpadding="0" cellspacing="0">
    <tr>
        <td width="70">
            @if($empresa->logo && file_exists(storage_path('app/public/'.$empresa->logo)))
                <img src="{{ storage_path('app/public/'.$empresa->logo) }}" class="logo-img">
            @else
                <img src="{{ public_path('img/logo.png') }}" class="logo-img">
            @endif
        </td>
        <td class="empresa">
            <h1>{{ $empresa->nome_fantasia }}</h1>
            <p>
                @if($empresa->razao_social){{ $empresa->razao_social }}@endif
                @if($empresa->cnpj) &nbsp;|&nbsp; CNPJ: {{ $empresa->cnpj }}@endif
            </p>
            <p>
                @if($empresa->telefone)Tel: {{ $empresa->telefone }}@endif
                @if($empresa->whatsapp) &nbsp;|&nbsp; WhatsApp: {{ $empresa->whatsapp }}@endif
                @if($empresa->email) &nbsp;|&nbsp; {{ $empresa->email }}@endif
            </p>
            @if($empresa->logradouro)
                <p>{{ $empresa->logradouro }}, {{ $empresa->numero }}
                @if($empresa->bairro) {{ $empresa->bairro }}@endif
                &#8212; {{ $empresa->cidade }}/{{ $empresa->estado }}
                @if($empresa->cep) &nbsp;|&nbsp; CEP {{ $empresa->cep }}@endif</p>
            @endif
        </td>
    </tr>
</table>

{{-- BANNER OS --}}
<table class="os-banner" cellpadding="0" cellspacing="0">
    <tr>
        <td><span class="os-numero">ORDEM DE SERVI&Ccedil;O &nbsp;#&nbsp;{{ $ordem->numero_os }}</span></td>
        <td style="text-align:right"><span class="os-status">{{ $ordem->status_formatado }}</span></td>
    </tr>
</table>
<span class="os-data">Entrada: {{ optional($ordem->data_entrada)->format('d/m/Y') }}</span>

{{-- CLIENTE E VEÍCULO --}}
<table class="card-wrapper" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
    <tr>
        <td>
            <div class="card-header">Cliente &amp; Veículo</div>
            <div class="card-body">
                <table class="two-col">
                    <tr>
                        <td>
                            <div class="field-label">Nome do Cliente</div>
                            <div class="field-value">{{ $ordem->cliente->nome }}</div>
                            @if($ordem->cliente->telefone)
                                <div class="field-label">Telefone</div>
                                <div class="field-value-light">{{ $ordem->cliente->telefone }}</div>
                            @endif
                            @if($ordem->cliente->email)
                                <div class="field-label">E-mail</div>
                                <div class="field-value-light">{{ $ordem->cliente->email }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="field-label">Ve&iacute;culo</div>
                            <div class="field-value">{{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }} ({{ $ordem->veiculo->ano }}) &#8212; {{ $ordem->veiculo->cor }}</div>
                            <div class="field-label">Placa</div>
                            <div class="field-value">{{ $ordem->veiculo->placa }}</div>
                            @if($ordem->veiculo->quilometragem)
                                <div class="field-label">Quilometragem</div>
                                <div class="field-value-light">{{ number_format($ordem->veiculo->quilometragem, 0, ',', '.') }} km</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

{{-- PROBLEMA RELATADO --}}
@if($ordem->descricao_problema)
<table class="card-wrapper" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
    <tr>
        <td>
            <div class="card-header">Problema Relatado</div>
            <div class="card-body">
                <div class="problema-text">{{ $ordem->descricao_problema }}</div>
            </div>
        </td>
    </tr>
</table>
@endif

{{-- SERVIÇOS E PEÇAS --}}
<table class="card-wrapper" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
    <tr>
        <td>
            <div class="card-header">Serviços &amp; Peças</div>
            <table class="itens-table">
                <thead>
                    <tr>
                        <th style="width:14%">Tipo</th>
                        <th>Descrição</th>
                        <th class="center" style="width:10%">Qtd.</th>
                        <th class="right" style="width:18%">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @php $itens = $ordem->itens; $total_itens = count($itens); @endphp
                    @foreach($itens as $idx => $item)
                    <tr class="{{ $idx + 1 === $total_itens ? 'last' : '' }}">
                        <td>
                            <span class="{{ $item->tipo_item === 'servico' ? 'badge-tipo' : 'badge-peca' }}">
                                {{ ucfirst($item->tipo_item) }}
                            </span>
                        </td>
                        <td>{{ $item->descricao }}</td>
                        <td class="center">{{ $item->quantidade }}</td>
                        <td class="right">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="total-bar">
                <tr>
                    <td><span class="total-label">Total a Pagar</span></td>
                    <td style="text-align: right;"><span class="total-valor">R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ASSINATURA --}}
<div class="assinatura-section">
    <div class="assinatura-linha-wrap">
        <table cellpadding="0" cellspacing="0" style="width:260px; margin:0 auto; border-bottom:1px solid #111827;">
            <tr><td style="height:30px;">&nbsp;</td></tr>
        </table>
    </div>
    <div class="assinatura-label">Assinatura do Cliente</div>
</div>

{{-- RODAPÉ --}}
<div class="rodape">
    Documento gerado automaticamente pelo <span class="rodape-strong">MecDesk</span> &nbsp;&middot;&nbsp; {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>