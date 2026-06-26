<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5px;
            color: #1a1a2e;
            line-height: 1.5;
            padding: 28px 32px 24px 32px;
        }

        /* ── HEADER ── */
        .header {
            width: 100%;
            border-bottom: 3px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header td { vertical-align: middle; }
        .header .logo { width: 60px; height: auto; }
        .header .empresa { text-align: right; }
        .header .empresa h1 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }
        .header .empresa p {
            font-size: 8px;
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
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .os-status {
            background: #10b981;
            color: #ffffff;
            font-size: 8px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .os-data {
            font-size: 8px;
            color: #9ca3af;
            padding-left: 2px;
            margin-bottom: 14px;
        }

        /* ── CARDS ── */
        .card {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            margin-bottom: 10px;
            border-collapse: collapse;
            overflow: hidden;
        }
        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 6px 12px;
            font-size: 8.5px;
            font-weight: 700;
            color: #374151;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .card-body { padding: 10px 12px; }

        /* ── DOIS PAINÉIS LADO A LADO ── */
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { vertical-align: top; width: 50%; padding: 0 10px 0 0; }
        .two-col td + td { padding: 0 0 0 10px; border-left: 1px solid #e5e7eb; }

        .field-label {
            font-size: 7.5px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 1px;
        }
        .field-value {
            font-size: 9.5px;
            color: #111827;
            font-weight: 500;
            margin-bottom: 7px;
        }
        .field-value.light { font-weight: 400; color: #374151; }

        /* ── PROBLEMA ── */
        .problema-text {
            font-size: 9.5px;
            color: #374151;
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 7px 10px;
            border-radius: 0 4px 4px 0;
            font-style: italic;
        }

        /* ── TABELA ITENS ── */
        .itens-table { width: 100%; border-collapse: collapse; }
        .itens-table thead tr { background: #111827; }
        .itens-table thead th {
            color: #ffffff;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            padding: 7px 12px;
            text-align: left;
        }
        .itens-table thead th.right { text-align: right; }
        .itens-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .itens-table tbody tr:last-child { border-bottom: none; }
        .itens-table tbody td {
            padding: 7px 12px;
            font-size: 9.5px;
            color: #374151;
        }
        .itens-table tbody td.valor {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        .badge-tipo {
            background: #eff6ff;
            color: #2563eb;
            font-size: 7.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .badge-peca {
            background: #f0fdf4;
            color: #16a34a;
            font-size: 7.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        /* ── TOTAL ── */
        .total-row {
            background: #111827;
            border-radius: 0 0 6px 6px;
            padding: 9px 12px;
        }
        .total-row td { vertical-align: middle; }
        .total-label {
            color: #9ca3af;
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .total-valor {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            text-align: right;
        }

        /* ── ASSINATURAS ── */
        .assinaturas { width: 100%; margin-top: 32px; border-collapse: collapse; }
        .assinaturas td { width: 50%; vertical-align: top; padding: 0 16px 0 0; }
        .assinaturas td + td { padding: 0 0 0 16px; }
        .assinatura-area {
            height: 54px;
            border: 1.5px dashed #d1d5db;
            border-radius: 6px;
            background: #fafafa;
            margin-bottom: 5px;
        }
        .assinatura-label {
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        /* ── RODAPÉ ── */
        .rodape {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            color: #d1d5db;
            font-size: 7.5px;
        }
        .rodape strong { color: #9ca3af; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="header">
    <tr>
        <td width="70">
            @if($empresa->logo && file_exists(storage_path('app/public/'.$empresa->logo)))
                <img src="{{ storage_path('app/public/'.$empresa->logo) }}" class="logo">
            @else
                <img src="{{ public_path('img/logo.png') }}" class="logo">
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
                <p>{{ $empresa->logradouro }}, {{ $empresa->numero }} {{ $empresa->bairro }} —
                {{ $empresa->cidade }}/{{ $empresa->estado }}
                @if($empresa->cep) &nbsp;|&nbsp; CEP {{ $empresa->cep }}@endif</p>
            @endif
        </td>
    </tr>
</table>

{{-- BANNER OS --}}
<table class="os-banner" cellpadding="0" cellspacing="0">
    <tr>
        <td><span class="os-numero">ORDEM DE SERVIÇO &nbsp;#&nbsp;{{ $ordem->numero_os }}</span></td>
        <td style="text-align:right"><span class="os-status">{{ $ordem->status_formatado }}</span></td>
    </tr>
</table>
<div class="os-data">Entrada: {{ optional($ordem->data_entrada)->format('d/m/Y') }}</div>

{{-- CLIENTE E VEÍCULO --}}
<div class="card-header" style="border-radius:7px 7px 0 0; border:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">
    Cliente &amp; Veículo
</div>
<div style="border:1px solid #e5e7eb; border-top:none; border-radius:0 0 7px 7px; margin-bottom:10px;">
    <div class="card-body">
        <table class="two-col">
            <tr>
                <td>
                    <div class="field-label">Nome do Cliente</div>
                    <div class="field-value">{{ $ordem->cliente->nome }}</div>
                    @if($ordem->cliente->telefone)
                        <div class="field-label">Telefone</div>
                        <div class="field-value light">{{ $ordem->cliente->telefone }}</div>
                    @endif
                    @if($ordem->cliente->email)
                        <div class="field-label">E-mail</div>
                        <div class="field-value light">{{ $ordem->cliente->email }}</div>
                    @endif
                </td>
                <td>
                    <div class="field-label">Veículo</div>
                    <div class="field-value">{{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }} ({{ $ordem->veiculo->ano }}) — {{ $ordem->veiculo->cor }}</div>
                    <div class="field-label">Placa</div>
                    <div class="field-value">{{ $ordem->veiculo->placa }}</div>
                    @if($ordem->veiculo->quilometragem)
                        <div class="field-label">Quilometragem</div>
                        <div class="field-value light">{{ number_format($ordem->veiculo->quilometragem, 0, ',', '.') }} km</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- PROBLEMA RELATADO --}}
@if($ordem->descricao_problema)
<div class="card-header" style="border-radius:7px 7px 0 0; border:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">
    Problema Relatado
</div>
<div style="border:1px solid #e5e7eb; border-top:none; border-radius:0 0 7px 7px; margin-bottom:10px;">
    <div class="card-body">
        <div class="problema-text">{{ $ordem->descricao_problema }}</div>
    </div>
</div>
@endif

{{-- SERVIÇOS E PEÇAS --}}
<div class="card-header" style="border-radius:7px 7px 0 0; border:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb;">
    Serviços &amp; Peças
</div>
<div style="border:1px solid #e5e7eb; border-top:none; border-radius:0 0 7px 7px; margin-bottom:10px; overflow:hidden;">
    <table class="itens-table">
        <thead>
            <tr>
                <th style="width:14%">Tipo</th>
                <th>Descrição</th>
                <th style="width:10%; text-align:center">Qtd.</th>
                <th class="right" style="width:18%">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordem->itens as $item)
            <tr>
                <td>
                    <span class="{{ $item->tipo_item === 'servico' ? 'badge-tipo' : 'badge-peca' }}">
                        {{ ucfirst($item->tipo_item) }}
                    </span>
                </td>
                <td>{{ $item->descricao }}</td>
                <td style="text-align:center">{{ $item->quantidade }}</td>
                <td class="valor">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table class="total-row" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="total-label">Total a Pagar</span></td>
            <td><span class="total-valor">R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}</span></td>
        </tr>
    </table>
</div>

{{-- ASSINATURAS --}}
<table class="assinaturas">
    <tr>
        <td>
            <div class="assinatura-area"></div>
            <div class="assinatura-label">Assinatura do Cliente</div>
        </td>
        <td>
            <div class="assinatura-area"></div>
            <div class="assinatura-label">Responsável — {{ $empresa->nome_fantasia }}</div>
        </td>
    </tr>
</table>

{{-- RODAPÉ --}}
<div class="rodape">
    Documento gerado automaticamente pelo <strong>MecDesk</strong> &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>