<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Checklist Vistoria Veículo - {{ $ordem->numero_os }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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
        }

        /* ── HEADER ── */
        .header {
            width: 100%;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo-img {
            max-width: 110px;
            max-height: 40px;
            width: auto;
            height: auto;
            display: block;
        }

        .empresa {
            text-align: right;
        }

        .empresa h1 {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 1px;
        }

        .empresa p {
            font-size: 7.5pt;
            color: #6b7280;
            line-height: 1.35;
        }

        /* ── BANNER OS ── */
        .os-banner {
            width: 100%;
            background: #111827;
            border-radius: 5px;
            padding: 5px 12px;
            margin-bottom: 2px;
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
            border-radius: 5px;
            margin-bottom: 5px;
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
            border-radius: 5px;
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

        /* ── TABELA DE ITENS DE CHECAGEM ── */
        .itens-table {
            width: 100%;
            border-collapse: collapse;
        }

        .itens-table thead tr {
            background: #111827;
        }

        .itens-table thead th {
            color: #ffffff;
            font-size: 6.8pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 3.5px 7px;
            text-align: left;
        }

        .itens-table thead th.center {
            text-align: center;
        }

        .itens-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            border-radius: 5px;
        }

        .itens-table tbody tr:nth-child(even) {
            background: #f9fafb;
            border-radius: 5px;

        }

        .itens-table tbody tr.last {
            border-bottom: none;
        }

        .itens-table tbody td {
            padding: 2.5px 7px;
            font-size: 7.2pt;
            color: #374151;
            vertical-align: middle;
        }

        .check-col {
            text-align: center;
            font-size: 7.2pt;
            color: #374151;
            white-space: nowrap;
            font-weight: bold;
        }

        .obs-line {
            border-bottom: 1px dotted #cbd5e1;
            height: 9px;
            width: 100%;
            display: block;
        }

        .alert-note {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            color: #4b5563;
            font-size: 6.5pt;
            font-style: italic;
            padding: 2.5px 7px;
            border-radius: 0 0 5px 5px;
        }

        /* ── FOTOS ── */
        .foto-frame {
            display: inline-block;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 3px;
            border-radius: 4px;
            text-align: center;
        }

        .foto-img {
            display: block;
            margin: 0 auto;
            border-radius: 2px;
            width: auto;
            height: auto;
        }

        .foto-caption {
            font-size: 6.2pt;
            color: #6b7280;
            font-weight: bold;
            margin-top: 2px;
        }

        .sem-fotos-box {
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            padding: 4px;
            text-align: center;
            background: #f9fafb;
        }

        .sem-fotos-hint {
            font-size: 6.5pt;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── OBSERVAÇÕES / AVARIAS ── */
        .problema-text {
            font-size: 7.8pt;
            color: #1f2937;
            background: #f9fafb;
            border-left: 3px solid #374151;
            padding: 4px 8px;
            line-height: 1.3;
        }

        .problema-text.vazio {
            color: #9ca3af;
            font-style: italic;
        }

        /* ── ASSINATURAS ── */
        .assinatura-section {
            width: 100%;
            margin-top: 50px;
            margin-bottom: 3px;
        }

        .assinatura-col {
            width: 48%;
            text-align: center;
            vertical-align: bottom;
        }

        .assinatura-linha {
            width: 85%;
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
            @if ($empresa->logo_path)
                <td style="width: 110px; vertical-align: middle;">
                    <img src="{{ $empresa->logo_path }}" class="logo-img">
                </td>
            @elseif (file_exists(public_path('img/logo.png')))
                <td style="width: 110px; vertical-align: middle;">
                    <img src="{{ public_path('img/logo.png') }}" class="logo-img">
                </td>
            @endif
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
                    <p>
                        {{ $empresa->logradouro }}{{ $empresa->numero ? ', ' . $empresa->numero : '' }}
                        {{ $empresa->bairro ? ' - ' . $empresa->bairro : '' }}
                        {{ $empresa->cidade ? ' — ' . $empresa->cidade . '/' . $empresa->estado : '' }}
                        {{ $empresa->cep ? ' | CEP ' . $empresa->cep : '' }}
                    </p>
                @endif
            </td>
        </tr>
    </table>

    {{-- BANNER OS --}}
    <table class="os-banner" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="os-titulo">CHECKLIST DE VISTORIA VEICULAR</span></td>
            <td style="text-align: right;"><span class="os-numero">{{ $ordem->numero_os }}</span></td>
        </tr>
    </table>
    <div class="os-meta">
        <strong>Data da Vistoria:</strong>
        {{ optional($ordem->data_entrada ?? $ordem->created_at)->format('d/m/Y H:i') }}
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

    {{-- INSPEÇÃO DE ENTRADA --}}
    <table class="card-wrapper" style="border-radius: 5px; margin-top: 12px">
        <tr style="border-radius: 5px;">
            <td>
                <table class="itens-table">
                    <thead>
                        <tr>
                            <th style="width: 48%;">Item de Checagem</th>
                            <th class="center" style="width: 22%;">Status / Conforme</th>
                            <th style="width: 30%;">Observação / Anotação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itensChecklist = [
                                '01. Nível de Óleo, Água e Fluidos',
                                '02. Extintor de Incêndio e Cintos de Segurança',
                                '03. Limpador de Para-brisa e Palhetas',
                                '04. Vidros, Travas e Espelhos Retrovisores',
                                '05. Faróis, Lanternas, Setas e Iluminação',
                                '06. Estepe, Macaco, Chave de Roda e Triângulo',
                                '07. Documentação do Veículo (CRLV) e Chaves',
                                '08. Pintura, Lataria, Arranhões e Avarias Externas',
                            ];
                            $totalItens = count($itensChecklist);
                        @endphp
                        @foreach ($itensChecklist as $idx => $item)
                            <tr class="{{ $idx + 1 === $totalItens ? 'last' : '' }}">
                                <td>{{ $item }}</td>
                                <td class="check-col">
                                    [&nbsp;&nbsp;&nbsp;] SIM &nbsp;&nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;] NÃO
                                </td>
                                <td><span class="obs-line"></span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="alert-note">
                    * Conferência e vistoria realizadas conjuntamente no momento do recebimento do veículo.
                </div>
            </td>
        </tr>
    </table>

    {{-- AVALIAÇÃO VISUAL / FOTOS DO VEÍCULO --}}
    @php
        $fotos = $ordem->fotos ?? collect();
        $qtdFotos = count($fotos);
    @endphp
    <table class="card-wrapper" style="border-radius: 5px; margin-top: 12px">
        <tr>
            <td>
                <div class="card-header">
                    Avaliação Visual &amp; Registro Fotográfico do Veículo
                    @if ($qtdFotos > 0)
                        <span
                            style="font-size:6.5pt; font-weight:normal; text-transform:none; color:#6b7280;">({{ $qtdFotos }}
                            {{ $qtdFotos == 1 ? 'foto registrada' : 'fotos registradas' }})</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 4px 6px;">
                    @if ($qtdFotos == 1)
                        {{-- 1 FOTO: CENTRALIZADA E PROPORCIONAL --}}
                        <div style="text-align: center; padding: 2px 0;">
                            <div class="foto-frame">
                                @if (file_exists(storage_path('app/public/' . $fotos[0]->caminho_foto)))
                                    <img src="{{ storage_path('app/public/' . $fotos[0]->caminho_foto) }}"
                                        class="foto-img" style="max-height: 100px; max-width: 260px;">
                                @else
                                    <div style="font-size: 7.2pt; color: #9ca3af; padding: 12px 25px;">[Foto
                                        indisponível]</div>
                                @endif
                                <div class="foto-caption">Registro Fotográfico #1</div>
                            </div>
                        </div>
                    @elseif ($qtdFotos == 2)
                        {{-- 2 FOTOS: LADO A LADO --}}
                        <table style="width: 100%;">
                            <tr>
                                @foreach ($fotos as $idx => $foto)
                                    <td
                                        style="width: 50%; text-align: center; vertical-align: middle; padding: 2px 4px;">
                                        <div class="foto-frame" style="width: 95%;">
                                            @if (file_exists(storage_path('app/public/' . $foto->caminho_foto)))
                                                <img src="{{ storage_path('app/public/' . $foto->caminho_foto) }}"
                                                    class="foto-img" style="max-height: 85px; max-width: 100%;">
                                            @else
                                                <div style="font-size: 7.2pt; color: #9ca3af; padding: 10px 0;">[Foto
                                                    indisponível]</div>
                                            @endif
                                            <div class="foto-caption">Registro #{{ $idx + 1 }}</div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    @elseif ($qtdFotos > 2)
                        {{-- 3 OU MAIS FOTOS: GRADE DE 3 OU 4 COLUNAS --}}
                        @php
                            $cols = $qtdFotos == 3 ? 3 : 4;
                            $cellWidth = $qtdFotos == 3 ? '33.33%' : '25%';
                            $maxH = $qtdFotos <= 4 ? '70px' : '60px';
                        @endphp
                        <table style="width: 100%;">
                            @foreach ($fotos->chunk($cols) as $chunkIdx => $row)
                                <tr>
                                    @foreach ($row as $fotoIdx => $foto)
                                        <td
                                            style="width: {{ $cellWidth }}; text-align: center; vertical-align: middle; padding: 2px 3px;">
                                            <div class="foto-frame" style="width: 95%;">
                                                @if (file_exists(storage_path('app/public/' . $foto->caminho_foto)))
                                                    <img src="{{ storage_path('app/public/' . $foto->caminho_foto) }}"
                                                        class="foto-img"
                                                        style="max-height: {{ $maxH }}; max-width: 100%;">
                                                @else
                                                    <div style="font-size: 6.5pt; color: #9ca3af; padding: 8px 0;">[Foto
                                                        indisponível]</div>
                                                @endif
                                                <div class="foto-caption">Foto
                                                    #{{ $chunkIdx * $cols + $fotoIdx + 1 }}</div>
                                            </div>
                                        </td>
                                    @endforeach
                                    @for ($i = 0; $i < $cols - count($row); $i++)
                                        <td style="width: {{ $cellWidth }};"></td>
                                    @endfor
                                </tr>
                            @endforeach
                        </table>
                    @else
                        {{-- DIAGRAMA ILUSTRATIVO DE INSPEÇÃO SE NÃO HOUVER FOTOS --}}
                        <div class="sem-fotos-box">
                            <svg viewBox="0 0 540 100" width="100%" height="36" xmlns="http://www.w3.org/2000/svg">
                                <!-- Lateral Esquerda -->
                                <g stroke="#374151" stroke-width="1.2" fill="none">
                                    <path
                                        d="M 10 40 Q 20 35 40 35 L 70 35 Q 85 15 115 15 L 160 15 Q 180 15 195 35 L 215 35 Q 225 42 225 50 L 225 58 Q 225 62 215 62 L 205 62 A 10 10 0 0 1 185 62 L 65 62 A 10 10 0 0 1 45 62 L 10 62 Z" />
                                    <circle cx="55" cy="62" r="7" />
                                    <circle cx="195" cy="62" r="7" />
                                    <path d="M 85 35 L 115 20 L 135 20 L 135 35 Z" />
                                    <path d="M 140 35 L 140 20 L 160 20 L 190 35 Z" />
                                    <text x="110" y="74" font-size="6.5" fill="#6b7280" text-anchor="middle"
                                        stroke="none" font-weight="bold">Lateral Esquerda</text>
                                </g>
                                <!-- Frente -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(240, 5)">
                                    <rect x="10" y="20" width="70" height="35" rx="6" />
                                    <path d="M 18 20 L 25 8 L 65 8 L 72 20 Z" />
                                    <circle cx="22" cy="34" r="5" />
                                    <circle cx="68" cy="34" r="5" />
                                    <rect x="35" y="42" width="20" height="7" rx="2" />
                                    <text x="45" y="69" font-size="6.5" fill="#6b7280" text-anchor="middle"
                                        stroke="none" font-weight="bold">Dianteira</text>
                                </g>
                                <!-- Traseira -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(340, 5)">
                                    <rect x="10" y="20" width="70" height="35" rx="6" />
                                    <path d="M 18 20 L 25 10 L 65 10 L 72 20 Z" />
                                    <rect x="15" y="28" width="12" height="5" rx="1"
                                        fill="#e5e7eb" />
                                    <rect x="63" y="28" width="12" height="5" rx="1"
                                        fill="#e5e7eb" />
                                    <rect x="32" y="42" width="26" height="7" rx="1" />
                                    <text x="45" y="69" font-size="6.5" fill="#6b7280" text-anchor="middle"
                                        stroke="none" font-weight="bold">Traseira</text>
                                </g>
                                <!-- Superior / Teto -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(440, 5)">
                                    <rect x="10" y="5" width="50" height="60" rx="10" />
                                    <rect x="16" y="14" width="38" height="12" rx="2" />
                                    <rect x="16" y="38" width="38" height="12" rx="2" />
                                    <text x="35" y="79" font-size="6.5" fill="#6b7280" text-anchor="middle"
                                        stroke="none" font-weight="bold">Teto / Superior</text>
                                </g>
                            </svg>
                            <div class="sem-fotos-hint">Nenhuma foto anexada. Indique avarias nas ilustrações e anote
                                no campo abaixo.</div>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- OBSERVAÇÕES DE AVARIAS / PROBLEMAS PRÉVIOS --}}
    <table class="card-wrapper" style="border-radius: 5px; margin-top: 12px">
        <tr>
            <td>
                <div class="card-header">Observações de Avarias / Problemas Prévios</div>
                <div class="card-body">
                    @if ($ordem->problemas_previos)
                        <div class="problema-text">{{ $ordem->problemas_previos }}</div>
                    @else
                        <div class="problema-text vazio">Nenhuma avaria ou problema prévio constatado no momento do
                            recebimento.</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ASSINATURAS --}}
    <table class="assinatura-section" cellpadding="0" cellspacing="0">
        <tr>
            <td class="assinatura-col">
                <div class="assinatura-linha"></div>
                <div class="assinatura-label">Cliente / Proprietário</div>
                <div class="assinatura-sub">Declaro conferência e concordância com a vistoria</div>
            </td>
            <td style="width: 4%;"></td>
            <td class="assinatura-col">
                <div class="assinatura-linha"></div>
                <div class="assinatura-label">Responsável pela Vistoria</div>
                <div class="assinatura-sub">{{ $empresa->nome_fantasia }}</div>
            </td>
        </tr>
    </table>

    {{-- RODAPÉ --}}
    <div class="rodape">
        Comprovante de Vistoria de Entrada gerado automaticamente pelo <span class="rodape-strong">MecDesk</span>
        &nbsp;&middot;&nbsp; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
