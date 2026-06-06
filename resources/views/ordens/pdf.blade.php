<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <style>

        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 8px;
        }

    </style>

</head>

<body>

<h1>MecDesk</h1>

<h2>Ordem de Serviço {{ $ordem->numero_os }}</h2>

<hr>

<p>
    <strong>Cliente:</strong>
    {{ $ordem->cliente->nome }}
</p>

<p>
    <strong>Veículo:</strong>
    {{ $ordem->veiculo->marca }}
    {{ $ordem->veiculo->modelo }}
    ({{ $ordem->veiculo->placa }})
</p>

<p>
    <strong>Status:</strong>
    {{ $ordem->status }}
</p>

<p>
    <strong>Problema Relatado:</strong>
</p>

<p>
    {{ $ordem->descricao_problema }}
</p>

<h3>Itens</h3>

<table>

    <thead>

        <tr>

            <th>Tipo</th>
            <th>Descrição</th>
            <th>Qtd</th>
            <th>Valor</th>

        </tr>

    </thead>

    <tbody>

        @foreach($ordem->itens as $item)

        <tr>

            <td>{{ ucfirst($item->tipo_item) }}</td>

            <td>{{ $item->descricao }}</td>

            <td>{{ $item->quantidade }}</td>

            <td>
                R$ {{ number_format($item->valor_total, 2, ',', '.') }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

<h2>

    Total:

    R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}

</h2>

</body>
</html>