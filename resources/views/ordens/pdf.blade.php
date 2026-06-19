<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        * {
            box-sizing: border-box;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #1F2937;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            width: 90px;
            height: auto;
        }

        .empresa {
            text-align: right;
        }

        .empresa h1 {
            margin: 0;
            color: #1F2937;
            font-size: 22px;
        }

        .empresa p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }

        .titulo-box {
            background: #1F2937;
            color: white;
            padding: 8px 10px;
            font-size: 13px;
            font-weight: bold;
        }

        .box {
            border: 1px solid #dcdcdc;
            margin-bottom: 18px;
        }

        .conteudo {
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #374151;
            color: white;
            padding: 8px;
            font-size: 11px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .info td {
            border: none;
            padding: 4px;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .assinaturas {
            margin-top: 70px;
        }

        .assinatura {
            width: 45%;
            text-align: center;
            display: inline-block;
        }

        .linha {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }

        .rodape {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            background: #e5e7eb;
            border-radius: 4px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<table class="header">

    <tr>

        <td width="120">

            @if($empresa->logo && file_exists(storage_path('app/public/'.$empresa->logo)))

                <img
                    src="{{ storage_path('app/public/'.$empresa->logo) }}"
                    class="logo">

            @else

                <img
                    src="{{ public_path('img/logo.png') }}"
                    class="logo">

            @endif

        </td>

        <td class="empresa">

            <h1>{{ $empresa->nome_fantasia }}</h1>

            @if($empresa->razao_social)
                <p>{{ $empresa->razao_social }}</p>
            @endif

            @if($empresa->cnpj)
                <p>CNPJ: {{ $empresa->cnpj }}</p>
            @endif

            @if($empresa->email)
                <p>{{ $empresa->email }}</p>
            @endif

            @if($empresa->telefone)
                <p>Telefone: {{ $empresa->telefone }}</p>
            @endif

            @if($empresa->whatsapp)
                <p>WhatsApp: {{ $empresa->whatsapp }}</p>
            @endif

            @if($empresa->logradouro)

                <p>

                    {{ $empresa->logradouro }},
                    {{ $empresa->numero }}

                </p>

                <p>

                    {{ $empresa->bairro }}

                    -

                    {{ $empresa->cidade }}/{{ $empresa->estado }}

                </p>

                @if($empresa->cep)

                    <p>CEP {{ $empresa->cep }}</p>

                @endif

            @endif

        </td>

    </tr>

</table>

<div class="box">

    <div class="titulo-box">

        ORDEM DE SERVIÇO Nº {{ $ordem->numero_os }}

    </div>

    <div class="conteudo">

        <table class="info">

            <tr>

                <td width="35%">

                    <strong>Status:</strong>

                    <span class="status">

                        {{ $ordem->status_formatado }}

                    </span>

                </td>

                <td>

                    <strong>Entrada:</strong>

                    {{ optional($ordem->data_entrada)->format('d/m/Y') }}

                </td>

            </tr>

        </table>

    </div>

</div>

<div class="box">

    <div class="titulo-box">

        CLIENTE

    </div>

    <div class="conteudo">

        <p>

            <strong>Nome:</strong>

            {{ $ordem->cliente->nome }}

        </p>

        @if($ordem->cliente->telefone)

            <p>

                <strong>Telefone:</strong>

                {{ $ordem->cliente->telefone }}

            </p>

        @endif

        @if($ordem->cliente->email)

            <p>

                <strong>E-mail:</strong>

                {{ $ordem->cliente->email }}

            </p>

        @endif

    </div>

</div>

<div class="box">

    <div class="titulo-box">

        VEÍCULO

    </div>

    <div class="conteudo">

        <p>

            <strong>Marca:</strong>

            {{ $ordem->veiculo->marca }}

        </p>

        <p>

            <strong>Modelo:</strong>

            {{ $ordem->veiculo->modelo }}

        </p>

        <p>

            <strong>Ano:</strong>

            {{ $ordem->veiculo->ano }}

        </p>

        <p>

            <strong>Cor:</strong>

            {{ $ordem->veiculo->cor }}

        </p>

        <p>

            <strong>Placa:</strong>

            {{ $ordem->veiculo->placa }}

        </p>

        @if($ordem->veiculo->quilometragem)

            <p>

                <strong>Quilometragem:</strong>

                {{ number_format($ordem->veiculo->quilometragem,0,',','.') }} km

            </p>

        @endif

    </div>

</div>

<div class="box">

    <div class="titulo-box">

        PROBLEMA RELATADO

    </div>

    <div class="conteudo">

        {{ $ordem->descricao_problema }}

    </div>

</div>

<div class="box">

    <div class="titulo-box">

        SERVIÇOS E PEÇAS

    </div>

    <table>

        <thead>

        <tr>

            <th width="15%">Tipo</th>

            <th>Descrição</th>

            <th width="12%">Qtd.</th>

            <th width="20%">Valor</th>

        </tr>

        </thead>

        <tbody>

        @foreach($ordem->itens as $item)

            <tr>

                <td>

                    {{ ucfirst($item->tipo_item) }}

                </td>

                <td>

                    {{ $item->descricao }}

                </td>

                <td>

                    {{ $item->quantidade }}

                </td>

                <td>

                    R$ {{ number_format($item->valor_total,2,',','.') }}

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="conteudo">

        <div class="total">

            TOTAL

            R$

            {{ number_format($ordem->valor_total,2,',','.') }}

        </div>

    </div>

</div>

<div class="assinaturas">

    <div class="assinatura">

        <div class="linha">

            Assinatura do Cliente

        </div>

    </div>

    <div class="assinatura" style="float:right;">

        <div class="linha">

            {{ $empresa->nome_fantasia }}

        </div>

    </div>

</div>

<div class="rodape">

    Documento emitido automaticamente pelo <strong>MecDesk</strong> em
    {{ now()->format('d/m/Y H:i') }}

</div>

</body>

</html>