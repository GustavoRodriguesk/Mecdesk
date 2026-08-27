<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Checklist Vistoria Veículo - {{ $ordem->numero_os }}</title>
    <style>
        @page {
            margin: 10px 18px 8px 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 8pt;
            color: #1f2937;
            line-height: 1.25;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ── HEADER (mesmo padrão da Ordem de Serviço) ── */
        .header {
            width: 100%;
            border-bottom: 1.5px solid #111827;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo-img {
            max-width: 85px;
            max-height: 40px;
            width: auto;
            height: auto;
        }

        .empresa {
            text-align: right;
        }

        .empresa h1 {
            margin-right: 20px;
            font-size: 13pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 1px;
        }

        .empresa p {
            font-size: 7pt;
            color: #6b7280;
            line-height: 1.3;
        }

        /* ── BANNER OS (mesmo padrão da Ordem de Serviço) ── */
        .os-banner {
            width: 100%;
            background: #111827;
            border-radius: 4px;
            padding: 5px 10px;
            margin-bottom: 2px;
            text-align: center;
        }

        .os-titulo {
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .os-data {
            font-size: 7pt;
            color: #6b7280;
            padding-left: 2px;
            padding-top: 2px;
            padding-bottom: 4px;
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
            font-size: 7.5pt;
            font-weight: bold;
            color: #374151;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            border-radius: 5px 5px 0 0;
        }

        .card-body {
            padding: 5px 8px;
        }

        /* ── DOIS PAINÉIS LADO A LADO ── */
        .two-col td {
            vertical-align: top;
            width: 50%;
            padding: 0 6px 0 0;
        }

        .two-col td+td {
            padding: 0 0 0 6px;
            border-left: 1px solid #e5e7eb;
        }

        .field-label {
            font-size: 6.5pt;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 0px;
        }

        .field-value {
            font-size: 8pt;
            color: #111827;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .field-value-light {
            font-size: 8pt;
            color: #374151;
            font-weight: normal;
            margin-bottom: 3px;
        }

        /* ── TABELA DE ITENS DE CHECAGEM ── */
        .itens-table thead tr {
            background: #111827;
        }

        .itens-table thead th {
            color: #ffffff;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 3.5px 7px;
            text-align: left;
        }

        .itens-table thead th span {
            font-size: 6pt;
            font-weight: normal;
            text-transform: none;
            letter-spacing: 0;
        }

        .itens-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .itens-table tbody tr.last {
            border-bottom: none;
        }

        .itens-table tbody td {
            padding: 3px 7px;
            font-size: 7.4pt;
            color: #374151;
        }

        .check-col {
            text-align: center;
            font-size: 7.5pt;
            color: #6b7280;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .alert-note {
            color: #b91c1c;
            font-size: 6.8pt;
            font-weight: bold;
            padding: 3px 8px 3px 8px;
        }

        /* ── FOTOS ── */
        .foto-cell {
            padding: 2px;
            text-align: center;
        }

        .foto-img {
            width: 100%;
            object-fit: cover;
            border: 1px solid #d1d5db;
            border-radius: 3px;
            display: block;
        }

        .foto-legenda {
            font-size: 6pt;
            color: #9ca3af;
            margin-top: 1px;
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
            color: #9ca3af;
            margin-top: 1px;
        }

        /* ── OBSERVAÇÕES / AVARIAS ── */
        .problema-text {
            font-size: 7.8pt;
            color: #1f2937;
            background: #f9fafb;
            border-left: 3px solid #374151;
            padding: 4px 8px;
            line-height: 1.25;
        }

        .problema-text.vazio {
            color: #9ca3af;
            font-style: italic;
        }

        /* ── ASSINATURAS ── */
        .assinatura-wrap {
            width: 100%;
            margin-top: 10px;
        }

        .assinatura-col {
            width: 48%;
            text-align: center;
        }

        .assinatura-linha {
            width: 88%;
            margin: 0 auto 3px auto;
            border-bottom: 1px solid #111827;
            height: 18px;
        }

        .assinatura-label {
            font-size: 7pt;
            color: #4b5563;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* ── RODAPÉ ── */
        .rodape {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            color: #9ca3af;
            font-size: 6.8pt;
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
            <td width="70">
                @if ($empresa->logo && file_exists(storage_path('app/public/' . $empresa->logo)))
                    <img src="{{ storage_path('app/public/' . $empresa->logo) }}" class="logo-img">
                @else
                    <img src="{{ public_path('img/logo.png') }}" class="logo-img">
                @endif
            </td>
            <td class="empresa">
                <h1>{{ $empresa->nome_fantasia }}</h1>
                <p>
                    @php
                        $contatoPartes = [];
                        if ($empresa->cnpj) $contatoPartes[] = 'CNPJ: ' . $empresa->cnpj;
                        if ($empresa->telefone) $contatoPartes[] = 'Tel: ' . $empresa->telefone;
                        if ($empresa->whatsapp) $contatoPartes[] = 'WhatsApp: ' . $empresa->whatsapp;
                    @endphp
                    {{ implode(' | ', $contatoPartes) }}
                </p>
            </td>
        </tr>
    </table>

    {{-- BANNER OS --}}
    <table class="os-banner" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="os-titulo">CHECKLIST DE VISTORIA &nbsp;&#183;&nbsp; OS &nbsp;#&nbsp;{{ $ordem->numero_os }}</span></td>
        </tr>
    </table>
    <span class="os-data">Data da vistoria: {{ optional($ordem->data_entrada ?? $ordem->created_at)->format('d/m/Y H:i') }}</span>

    {{-- CLIENTE E VEÍCULO --}}
    <table class="card-wrapper" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="card-header">Cliente &amp; Veículo</div>
                <div class="card-body">
                    <table class="two-col">
                        <tr>
                            <td>
                                <div class="field-label">Cliente</div>
                                <div class="field-value">{{ $ordem->cliente->nome }}</div>
                                <div class="field-label">Telefone</div>
                                <div class="field-value-light">{{ $ordem->cliente->telefone_formatado ?? ($ordem->cliente->telefone ?: '-') }}</div>
                                <div class="field-label">CPF/CNPJ</div>
                                <div class="field-value-light" style="margin-bottom:0;">{{ $ordem->cliente->cpf_cnpj_formatado ?? ($ordem->cliente->cpf_cnpj ?: '-') }}</div>
                            </td>
                            <td>
                                <div class="field-label">Veículo</div>
                                <div class="field-value">{{ $ordem->veiculo->marca }} {{ $ordem->veiculo->modelo }} ({{ $ordem->veiculo->ano ?: '-' }})</div>
                                <div class="field-label">Placa &nbsp;|&nbsp; Cor</div>
                                <div class="field-value-light">{{ $ordem->veiculo->placa_formatada ?? $ordem->veiculo->placa }} &nbsp;|&nbsp; {{ $ordem->veiculo->cor ?: '-' }}</div>
                                <div class="field-label">KM Entrada</div>
                                <div class="field-value-light" style="margin-bottom:0;">{{ $ordem->veiculo->quilometragem ? number_format($ordem->veiculo->quilometragem, 0, ',', '.') . ' km' : '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- INSPEÇÃO DE ENTRADA --}}
    <table class="card-wrapper" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="card-header">Inspeção de Entrada</div>
                <table class="itens-table">
                    <thead>
                        <tr>
                            <th style="width:54%">Itens de Checagem</th>
                            <th class="check-col" style="width:14%;">Atende?<br><span>Sim &nbsp;&nbsp; Não</span></th>
                            <th style="width:32%">Observação / Anotação</th>
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
                            $totalItensChecklist = count($itensChecklist);
                        @endphp
                        @foreach ($itensChecklist as $idx => $item)
                            <tr class="{{ $idx + 1 === $totalItensChecklist ? 'last' : '' }}">
                                <td>{{ $item }}</td>
                                <td class="check-col">[&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;[&nbsp;&nbsp;]</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="alert-note">Dar visto e assinar no momento da recepção e vistoria do veículo.</div>
            </td>
        </tr>
    </table>

    {{-- AVALIAÇÃO VISUAL / FOTOS --}}
    @php
        $fotos = $ordem->fotos;
        $qtdFotos = count($fotos);
        // Grade sempre com 4 colunas; a altura diminui proporcionalmente para caber em 1 única folha A4.
        $fotoCols = 4;
        if ($qtdFotos <= 4) {
            $fotoHeight = '65px';
        } elseif ($qtdFotos <= 8) {
            $fotoHeight = '48px';
        } elseif ($qtdFotos <= 12) {
            $fotoHeight = '38px';
        } elseif ($qtdFotos <= 16) {
            $fotoHeight = '30px';
        } else {
            $fotoHeight = '25px';
        }
    @endphp
    <table class="card-wrapper" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="card-header">Avaliação Visual &amp; Fotos do Veículo</div>
                <div class="card-body">
                    @if ($qtdFotos > 0)
                        <table style="width:100%;">
                            <tr>
                                @foreach ($fotos as $idx => $foto)
                                    @if ($idx > 0 && $idx % $fotoCols == 0)
                                        </tr><tr>
                                    @endif
                                    <td width="25%" class="foto-cell">
                                        @if (file_exists(storage_path('app/public/' . $foto->caminho_foto)))
                                            <img src="{{ storage_path('app/public/' . $foto->caminho_foto) }}" class="foto-img" style="height: {{ $fotoHeight }};">
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    @else
                        {{-- DIAGRAMA VETORIAL ILUSTRATIVO DE 4 VISTAS SE NÃO HOUVER FOTOS --}}
                        <div class="sem-fotos-box">
                            <svg viewBox="0 0 540 120" width="100%" height="52" xmlns="http://www.w3.org/2000/svg">
                                <!-- Lateral Esquerda -->
                                <g stroke="#374151" stroke-width="1.2" fill="none">
                                    <path d="M 10 50 Q 20 45 40 45 L 70 45 Q 85 25 115 25 L 160 25 Q 180 25 195 45 L 215 45 Q 225 52 225 60 L 225 68 Q 225 72 215 72 L 205 72 A 10 10 0 0 1 185 72 L 65 72 A 10 10 0 0 1 45 72 L 10 72 Z"/>
                                    <circle cx="55" cy="72" r="8"/>
                                    <circle cx="195" cy="72" r="8"/>
                                    <path d="M 85 45 L 115 30 L 135 30 L 135 45 Z"/>
                                    <path d="M 140 45 L 140 30 L 160 30 L 190 45 Z"/>
                                    <text x="110" y="86" font-size="7" fill="#9ca3af" text-anchor="middle" stroke="none">Vista Lateral</text>
                                </g>
                                <!-- Frente -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(240, 15)">
                                    <rect x="10" y="25" width="70" height="40" rx="8"/>
                                    <path d="M 18 25 L 25 10 L 65 10 L 72 25 Z"/>
                                    <circle cx="22" cy="40" r="6"/>
                                    <circle cx="68" cy="40" r="6"/>
                                    <rect x="35" y="48" width="20" height="8" rx="2"/>
                                    <text x="45" y="76" font-size="7" fill="#9ca3af" text-anchor="middle" stroke="none">Dianteira</text>
                                </g>
                                <!-- Traseira -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(340, 15)">
                                    <rect x="10" y="25" width="70" height="40" rx="8"/>
                                    <path d="M 18 25 L 25 12 L 65 12 L 72 25 Z"/>
                                    <rect x="15" y="34" width="12" height="6" rx="2" fill="#e5e7eb"/>
                                    <rect x="63" y="34" width="12" height="6" rx="2" fill="#e5e7eb"/>
                                    <rect x="32" y="48" width="26" height="8" rx="1"/>
                                    <text x="45" y="76" font-size="7" fill="#9ca3af" text-anchor="middle" stroke="none">Traseira</text>
                                </g>
                                <!-- Superior / Teto -->
                                <g stroke="#374151" stroke-width="1.2" fill="none" transform="translate(440, 15)">
                                    <rect x="10" y="10" width="50" height="70" rx="12"/>
                                    <rect x="16" y="20" width="38" height="15" rx="3"/>
                                    <rect x="16" y="50" width="38" height="15" rx="3"/>
                                    <text x="35" y="91" font-size="7" fill="#9ca3af" text-anchor="middle" stroke="none">Teto / Superior</text>
                                </g>
                                </svg>
                            <div class="sem-fotos-hint">Marque com um x a área com avaria e descreva nas observações abaixo.</div>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- OBSERVAÇÕES DE AVARIAS / PROBLEMAS PRÉVIOS --}}
    <table class="card-wrapper" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="card-header">Observações de Avarias / Problemas Prévios</div>
                <div class="card-body">
                    @if ($ordem->problemas_previos)
                        <div class="problema-text">{{ $ordem->problemas_previos }}</div>
                    @else
                        <div class="problema-text vazio">Nenhuma avaria ou problema prévio constatado no momento do recebimento.</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ASSINATURAS --}}
    <table class="assinatura-wrap" cellpadding="0" cellspacing="0">
        <tr>
            <td class="assinatura-col">
                <div class="assinatura-linha"></div>
                <div class="assinatura-label">Assinatura do Cliente / Proprietário</div>
            </td>
            <td style="width:4%;"></td>
            <td class="assinatura-col">
                <div class="assinatura-linha"></div>
                <div class="assinatura-label">Responsável pela Vistoria / Oficina</div>
            </td>
        </tr>
    </table>

    {{-- RODAPÉ --}}
    <div class="rodape">
        Comprovante de Vistoria de Entrada gerado automaticamente pelo <span class="rodape-strong">MecDesk</span> &nbsp;&middot;&nbsp; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>