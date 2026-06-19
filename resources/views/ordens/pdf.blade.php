<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.35;
        }

        * {
            box-sizing: border-box;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1F2937;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .empresa {
            text-align: right;
        }

        .empresa h1 {
            margin: 0 0 2px 0;
            color: #1F2937;
            font-size: 16px;
        }

        .empresa p {
            margin: 0;
            font-size: 9px;
            color: #555;
            line-height: 1.3;
        }

        .titulo-os {
            background: #1F2937;
            color: white;
            padding: 5px 8px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .titulo-os .status {
            float: right;
            background: #4B5563;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
        }

        .info-linha {
            font-size: 9px;
            color: #555;
            margin-bottom: 10px;
        }

        .box {
            border: 1px solid #dcdcdc;
            margin-bottom: 8px;
        }

        .titulo-box {
            background: #374151;
            color: white;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
        }

        .conteudo {
            padding: 6px 8px;
        }

        .conteudo p {
            margin: 1px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #374151;
            color: white;
            padding: 4px 6px;
            font-size: 9px;
            text-align: left;
        }

        table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            font-size: 9.5px;
        }

        .duas-colunas {
            width: 100%;
        }

        .duas-colunas td {
            border: none;
            padding: 0;
            vertical-align: top;
            width: 50%;
        }

        .total {
            margin-top: 6px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            padding: 0 8px 6px 8px;
        }

        .assinaturas {
            width: 100%;
            margin-top: 30px;
        }

        .assinatura {
            width: 45%;
            text-align: center;
            display: inline-block;
            font-size: 9px;
        }

        .linha {
            border-top: 1px solid #000;
            margin-top: 25px;
            padding-top: 4px;
        }

        .rodape {
            margin-top: 15px;
            text-align: center;
            color: #888;
            font-size: 8px;
        }
    </style>
</head>

<body>

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
                @if($empresa->cnpj) &nbsp;|&nbsp; CNPJ: {{ $empresa->cnpj }} @endif
            </p>
            <p>
                @if($empresa->telefone) Tel: {{ $empresa->telefone }} @endif
                @if($empresa->whatsapp) &nbsp;|&nbsp; WhatsApp: {{ $empresa->whatsapp }} @endif
                @if($empresa->email) &nbsp;|&nbsp; {{ $empresa->email }} @endif
            </p>
            @if($empresa->logradouro)
                <p>
                    {{ $empresa->logradouro }}, {{ $empresa->numero }} {{ $empresa->bairro }} -
                    {{ $empresa->cidade }}/{{ $empresa->estado }}
                    @if($empresa->cep) &nbsp;|&nbsp; CEP {{ $empresa->cep }} @endif
                </p>
            @endif
        </td>
    </tr>
</table>

<div class="titulo-os">
    ORDEM DE SERVIÇO Nº {{ $ordem->numero_os }}
    <span class="status">{{ $ordem->status_formatado }}</span>
</div>
<div class="info-linha">
    Entrada: {{ optional($ordem->data_entrada)->format('d/m/Y') }}
</div>

<div class="box">
    <div class="titulo-box">CLIENTE E VEÍCULO</div>
    <div class="conteudo">
        <table class="duas-colunas">
            <tr>
                <td>
                    <p><strong>Cliente:</strong> {{ $ordem->cliente->nome }}</p>
                    @if($ordem->cliente->telefone)
                        <p><strong>Telefone:</strong> {{ $ordem->cliente->telefone }}</p>
                    @endif
                    @if($ordem->cliente->email)
                        <p><strong>E-mail:</strong> {{ $ordem->cliente->email }}</p>
                    @endif
                </td>
                <td>
                    <p><strong>Veículo:</strong> {{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }}
                        ({{ $ordem->veiculo->ano }}) - {{ $ordem->veiculo->cor }}</p>
                    <p><strong>Placa:</strong> {{ $ordem->veiculo->placa }}</p>
                    @if($ordem->veiculo->quilometragem)
                        <p><strong>KM:</strong> {{ number_format($ordem->veiculo->quilometragem,0,',','.') }} km</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

@if($ordem->descricao_problema)
<div class="box">
    <div class="titulo-box">PROBLEMA RELATADO</div>
    <div class="conteudo">
        {{ $ordem->descricao_problema }}
    </div>
</div>
@endif

<div class="box">
    <div class="titulo-box">SERVIÇOS E PEÇAS</div>
    <table>
        <thead>
        <tr>
            <th width="15%">Tipo</th>
            <th>Descrição</th>
            <th width="10%">Qtd.</th>
            <th width="18%">Valor</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ordem->itens as $item)
            <tr>
                <td>{{ ucfirst($item->tipo_item) }}</td>
                <td>{{ $item->descricao }}</td>
                <td>{{ $item->quantidade }}</td>
                <td>R$ {{ number_format($item->valor_total,2,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="total">
        TOTAL: R$ {{ number_format($ordem->valor_total,2,',','.') }}
    </div>
</div>

<table class="assinaturas">
    <tr>
        <td style="width:45%; text-align:center; font-size:9px;">
            <div class="linha">Assinatura do Cliente</div>
        </td>
        <td style="width:10%;"></td>
        <td style="width:45%; text-align:center; font-size:9px;">
            <div class="linha">{{ $empresa->nome_fantasia }}</div>
        </td>
    </tr>
</table>

<div class="rodape">
    Documento emitido automaticamente pelo <strong>MecDesk</strong> em {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>