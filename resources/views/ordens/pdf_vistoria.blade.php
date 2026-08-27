<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Checklist Vistoria Veículo - {{ $ordem->numero_os }}</title>
    <style>
        @page {
            margin: 12px 18px 12px 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 8pt;
            color: #0f172a;
            line-height: 1.25;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 3px 5px;
            font-size: 7.5pt;
            vertical-align: middle;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #94a3b8;
        }

        .header-logo {
            width: 110px;
            max-height: 45px;
            object-fit: contain;
        }

        .header-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: right;
            color: #0f172a;
            letter-spacing: 0.3px;
        }

        .header-sub {
            font-size: 7.5pt;
            color: #475569;
            text-align: right;
        }

        .bg-light {
            background-color: #f1f5f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-red {
            color: #dc2626;
            font-size: 6.8pt;
            font-weight: bold;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .section-title {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background: #e2e8f0;
            padding: 2.5px;
            border: 1px solid #94a3b8;
            margin-top: 5px;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .avarias-box {
            border: 1px solid #94a3b8;
            min-height: 36px;
            padding: 4px 6px;
            font-size: 7.5pt;
            background: #fffbeb;
            color: #78350f;
            line-height: 1.3;
        }

        .foto-img {
            width: 100%;
            object-fit: cover;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }

        .assinatura-box {
            width: 48%;
            display: inline-block;
            text-align: center;
            vertical-align: bottom;
        }

        .assinatura-line {
            border-top: 1px solid #0f172a;
            margin-top: 26px;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            padding-top: 2px;
            text-transform: uppercase;
        }

        .rodape {
            margin-top: 6px;
            padding-top: 3px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #94a3b8;
            font-size: 6.8pt;
        }
    </style>
</head>

<body>

    {{-- CABEÇALHO COM LOGO E TÍTULO NO ESTILO CHECKLIST --}}
    <table style="margin-bottom: 4px;">
        <tr>
            <td width="120">
                @if ($empresa->logo && file_exists(storage_path('app/public/' . $empresa->logo)))
                    <img src="{{ storage_path('app/public/' . $empresa->logo) }}" class="header-logo">
                @else
                    <div style="font-size: 11pt; font-weight: bold; color: #1e293b;">{{ $empresa->nome_fantasia }}</div>
                @endif
            </td>
            <td class="header-title">
                Checklist Vistoria de Ve&iacute;culo
                <div class="header-sub">Ordem de Servi&ccedil;o #{{ $ordem->numero_os }} &nbsp;&middot;&nbsp; Data: {{ optional($ordem->data_entrada ?? $ordem->created_at)->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    {{-- TABELA DE DADOS DO VEÍCULO E CLIENTE --}}
    <table class="table-bordered" style="margin-bottom: 4px;">
        <tr>
            <td class="bg-light font-bold" width="12%">Cliente:</td>
            <td width="38%">{{ $ordem->cliente->nome }}</td>
            <td class="bg-light font-bold" width="12%">Modelo:</td>
            <td width="38%">{{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }} ({{ $ordem->veiculo->ano ?: '-' }})</td>
        </tr>
        <tr>
            <td class="bg-light font-bold">Telefone:</td>
            <td>{{ $ordem->cliente->telefone_formatado ?? ($ordem->cliente->telefone ?: '-') }}</td>
            <td class="bg-light font-bold">Placa:</td>
            <td class="font-bold">{{ $ordem->veiculo->placa_formatada ?? $ordem->veiculo->placa }} &nbsp;|&nbsp; Cor: {{ $ordem->veiculo->cor ?: '-' }}</td>
        </tr>
        <tr>
            <td class="bg-light font-bold">CPF/CNPJ:</td>
            <td>{{ $ordem->cliente->cpf_cnpj_formatado ?? ($ordem->cliente->cpf_cnpj ?: '-') }}</td>
            <td class="bg-light font-bold">KM Entrada:</td>
            <td>{{ $ordem->veiculo->quilometragem ? number_format($ordem->veiculo->quilometragem, 0, ',', '.') . ' km' : '-' }}</td>
        </tr>
    </table>

    {{-- TABELA DE INSPEÇÃO DE ITENS --}}
    <div class="section-title">INSPE&Ccedil;&Atilde;O DE ENTRADA</div>
    <table class="table-bordered">
        <thead>
            <tr class="bg-light">
                <th width="52%" style="text-align: left;">ITENS DE CHECAGEM</th>
                <th width="14%" class="text-center">ATENDE?<br><span style="font-size: 6.5pt; font-weight: normal;">S &nbsp;&nbsp;&nbsp; N</span></th>
                <th width="34%" style="text-align: left;">OBSERVA&Ccedil;&Atilde;O / ANOTA&Ccedil;&Atilde;O</th>
            </tr>
        </thead>
        <tbody>
            @php
                $itensChecklist = [
                    '01 - Nível de Óleo / Água / Combustível',
                    '02 - Extintor de Incêndio / Cintos de Segurança',
                    '03 - Limpador de Para-brisas e Palhetas',
                    '04 - Vidros, Travas e Espelhos Retrovisores',
                    '05 - Faróis, Lanternas e Luzes de Seta',
                    '06 - Estepe, Macaco, Chave de Roda e Triângulo',
                    '07 - Documentação do Veículo (CRLV) / Chaves',
                    '08 - Arranhões, Amassados ou Avarias Externas',
                ];
            @endphp
            @foreach ($itensChecklist as $item)
                <tr>
                    <td style="font-size: 7.2pt;">{{ $item }}</td>
                    <td class="text-center" style="font-size: 8pt; color: #94a3b8;">[ &nbsp; ] &nbsp; [ &nbsp; ]</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-red">Dar visto e assinar no momento da recepção e vistoria do veículo.</div>

    {{-- AVALIAÇÃO VISUAL / REGISTRO DE FOTOS DO VEÍCULO --}}
    <div class="section-title">AVALIA&Ccedil;&Atilde;O VISUAL &amp; FOTOS DO VE&Iacute;CULO</div>
    @php
        $fotos = $ordem->fotos;
        $qtdFotos = count($fotos);
        $fotoHeight = '80px';
        if ($qtdFotos > 4 && $qtdFotos <= 8) {
            $fotoHeight = '55px';
        } elseif ($qtdFotos > 8) {
            $fotoHeight = '42px';
        }
    @endphp

    @if ($qtdFotos > 0)
        <table style="width: 100%; margin-bottom: 2px;">
            <tr>
                @foreach ($fotos as $idx => $foto)
                    @if ($idx > 0 && $idx % 4 == 0)
                        </tr><tr>
                    @endif
                    <td width="25%" style="padding: 2px; text-align: center;">
                        @if (file_exists(storage_path('app/public/' . $foto->caminho_foto)))
                            <img src="{{ storage_path('app/public/' . $foto->caminho_foto) }}" class="foto-img" style="height: {{ $fotoHeight }};">
                        @endif
                    </td>
                @endforeach
            </tr>
        </table>
    @else
        {{-- DIAGRAMA VETORIAL ILUSTRATIVO DE 4 VISTAS SE NÃO HOUVER FOTOS --}}
        <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px; text-align: center; background: #fafafa; margin-bottom: 2px;">
            <svg viewBox="0 0 540 120" width="100%" height="75" xmlns="http://www.w3.org/2000/svg">
                <!-- Lateral Esquerda -->
                <g stroke="#334155" stroke-width="1.2" fill="none">
                    <path d="M 10 50 Q 20 45 40 45 L 70 45 Q 85 25 115 25 L 160 25 Q 180 25 195 45 L 215 45 Q 225 52 225 60 L 225 68 Q 225 72 215 72 L 205 72 A 10 10 0 0 1 185 72 L 65 72 A 10 10 0 0 1 45 72 L 10 72 Z"/>
                    <circle cx="55" cy="72" r="8"/>
                    <circle cx="195" cy="72" r="8"/>
                    <path d="M 85 45 L 115 30 L 135 30 L 135 45 Z"/>
                    <path d="M 140 45 L 140 30 L 160 30 L 190 45 Z"/>
                    <text x="110" y="86" font-size="7" fill="#64748b" text-anchor="middle" stroke="none">Vista Lateral</text>
                </g>
                <!-- Frente -->
                <g stroke="#334155" stroke-width="1.2" fill="none" transform="translate(240, 15)">
                    <rect x="10" y="25" width="70" height="40" rx="8"/>
                    <path d="M 18 25 L 25 10 L 65 10 L 72 25 Z"/>
                    <circle cx="22" cy="40" r="6"/>
                    <circle cx="68" cy="40" r="6"/>
                    <rect x="35" y="48" width="20" height="8" rx="2"/>
                    <text x="45" y="76" font-size="7" fill="#64748b" text-anchor="middle" stroke="none">Dianteira</text>
                </g>
                <!-- Traseira -->
                <g stroke="#334155" stroke-width="1.2" fill="none" transform="translate(340, 15)">
                    <rect x="10" y="25" width="70" height="40" rx="8"/>
                    <path d="M 18 25 L 25 12 L 65 12 L 72 25 Z"/>
                    <rect x="15" y="34" width="12" height="6" rx="2" fill="#e2e8f0"/>
                    <rect x="63" y="34" width="12" height="6" rx="2" fill="#e2e8f0"/>
                    <rect x="32" y="48" width="26" height="8" rx="1"/>
                    <text x="45" y="76" font-size="7" fill="#64748b" text-anchor="middle" stroke="none">Traseira</text>
                </g>
                <!-- Superior / Teto -->
                <g stroke="#334155" stroke-width="1.2" fill="none" transform="translate(440, 15)">
                    <rect x="10" y="10" width="50" height="70" rx="12"/>
                    <rect x="16" y="20" width="38" height="15" rx="3"/>
                    <rect x="16" y="50" width="38" height="15" rx="3"/>
                    <text x="35" y="91" font-size="7" fill="#64748b" text-anchor="middle" stroke="none">Teto / Superior</text>
                </g>
            </svg>
            <div style="font-size: 6.5pt; color: #dc2626; margin-top: 1px;">Marque com um x a &aacute;rea com avaria e descreva nas observa&ccedil;&otilde;es abaixo.</div>
        </div>
    @endif

    {{-- OBSERVAÇÕES DE AVARIAS / PROBLEMAS PRÉVIOS --}}
    <div style="font-size: 7.5pt; font-weight: bold; margin-bottom: 2px;">OBSERVA&Ccedil;&Otilde;ES DE AVARIAS / PROBLEMAS PR&Eacute;VIOS:</div>
    <div class="avarias-box">
        @if ($ordem->problemas_previos)
            {{ $ordem->problemas_previos }}
        @else
            <span style="color: #94a3b8; font-style: italic;">Nenhuma avaria ou problema pr&eacute;vio constatado no momento do recebimento.</span>
        @endif
    </div>

    {{-- ASSINATURAS DO CLIENTE E RESPONSÁVEL --}}
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td width="48%" style="text-align: center; vertical-align: bottom;">
                <div class="assinatura-line">Assinatura do Cliente / Propriet&aacute;rio</div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="text-align: center; vertical-align: bottom;">
                <div class="assinatura-line">Respons&aacute;vel pela Vistoria / Oficina</div>
            </td>
        </tr>
    </table>

    {{-- RODAPÉ --}}
    <div class="rodape">
        Comprovante de Vistoria de Entrada gerado pelo sistema MecDesk &nbsp;&middot;&nbsp; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
