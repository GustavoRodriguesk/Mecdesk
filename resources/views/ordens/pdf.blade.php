<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 90px;
        }

        .empresa {
            text-align: center;
        }

        .empresa h1 {
            margin: 0;
            font-size: 22px;
            color: #1f2937;
        }

        .empresa p {
            margin: 2px;
            font-size: 11px;
        }

        .box {
            border: 1px solid #d1d5db;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .titulo {
            background: #1f2937;
            color: white;
            padding: 6px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #1f2937;
            color: white;
            padding: 8px;
            text-align: left;
        }

        table td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
        }

        .assinatura {
            margin-top: 80px;
            text-align: center;
        }

        .linha-assinatura {
            width: 300px;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 5px;
        }

        .rodape {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <table class="header" style="border: none;">
        <tr style="border:none;">
            <td style="border:none; width:120px;">
                <img src="{{ public_path('img/logo.png') }}" class="logo">
            </td>

            <td style="border:none;" class="empresa">
                <h1>Pai e Filho Auto Elétrica e Mecânica Ltda</h1>

                <p>CNPJ: 59.253.187/0001-88</p>
                <p>Telefone: (11) 99525-3420</p>
                <p>Rua Mercedes Maria de Jesus, 383, Guararema - SP, 08.900-000</p>
            </td>
        </tr>
    </table>

    <div class="box">
        <div class="titulo">
            ORDEM DE SERVIÇO Nº {{ $ordem->numero_os }}
        </div>

        <table style="border:none;">
            <tr>
                <td style="border:none;">
                    <strong>Status:</strong> {{ $ordem->status }}
                </td>

                <td style="border:none;">
                    <strong>Data:</strong>
                    {{ $ordem->created_at->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <div class="titulo">
            DADOS DO CLIENTE
        </div>

        <p>
            <strong>Nome:</strong>
            {{ $ordem->cliente->nome }}
        </p>

        <p>
            <strong>Veículo:</strong>
            {{ $ordem->veiculo->marca }} - {{ $ordem->veiculo->modelo }}
        </p>

        <p>
            <strong>Placa:</strong>
            {{ $ordem->veiculo->placa }}
        </p>

    </div>

    <div class="box">
        <div class="titulo">
            PROBLEMA RELATADO
        </div>

        <p>
            {{ $ordem->descricao_problema }}
        </p>
    </div>

    <div class="box">
        <div class="titulo">
            SERVIÇOS E PEÇAS
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Qtd.</th>
                    <th>Valor</th>
                </tr>
            </thead>

            <tbody>
                @foreach($ordem->itens as $item)
                <tr>
                    <td>{{ $item->tipo_item == 'servico' ? 'Serviço' : 'Peça' }}</td>
                    <td>{{ $item->descricao }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>
                        R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            TOTAL: R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
        </div>
    </div>

    <div class="assinatura">
        <div class="linha-assinatura">
            Assinatura do Cliente
        </div>
    </div>

    <div class="rodape">
        Documento emitido em
        {{ now()->format('d/m/Y') }}
    </div>

</body>

</html>