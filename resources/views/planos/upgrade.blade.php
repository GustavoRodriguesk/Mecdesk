<x-app-layout>
    <x-slot name="header">Upgrade de Plano</x-slot>

    <style>
        /* ── Page-specific overrides ── */
        .plans-hero {
            text-align: center;
            padding: 8px 0 36px;
        }
        .plans-hero .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #EFF6FF;
            color: #2563EB;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 14px;
        }
        .plans-hero h2 {
            font-size: 30px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        .plans-hero p {
            font-size: 15px;
            color: #64748B;
            max-width: 480px;
            margin: 0 auto;
        }

        /* ── Billing toggle ── */
        .billing-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 24px 0 36px;
        }
        .billing-toggle span {
            font-size: 14px;
            font-weight: 500;
            color: #64748B;
        }
        .billing-toggle span.active-label { color: #0F172A; font-weight: 600; }
        .toggle-switch {
            position: relative;
            width: 48px;
            height: 26px;
            cursor: pointer;
        }
        .toggle-switch input { display: none; }
        .toggle-track {
            position: absolute;
            inset: 0;
            background: #CBD5E1;
            border-radius: 13px;
            transition: background 0.25s;
        }
        .toggle-thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
            transition: transform 0.25s;
        }
        input:checked ~ .toggle-track { background: #2563EB; }
        input:checked ~ .toggle-thumb { transform: translateX(22px); }
        .discount-badge {
            background: linear-gradient(135deg, #22C55E, #16A34A);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        /* ── Plan cards grid ── */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 48px;
        }
        @media (max-width: 900px) {
            .plans-grid { grid-template-columns: 1fr; max-width: 420px; margin-inline: auto; }
        }

        /* ── Plan card ── */
        .plan-card {
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px;
            border: 2px solid #E8ECF0;
            position: relative;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            display: flex;
            flex-direction: column;
        }
        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(0,0,0,0.10);
        }
        .plan-card.popular {
            border-color: #2563EB;
            box-shadow: 0 8px 28px rgba(37,99,235,0.18);
            transform: translateY(-6px);
        }
        .plan-card.popular:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 48px rgba(37,99,235,0.22);
        }
        .popular-badge {
            position: absolute;
            top: -13px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 16px;
            border-radius: 20px;
            white-space: nowrap;
            letter-spacing: 0.4px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
        }

        .plan-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .plan-icon.free  { background: #F1F5F9; color: #475569; }
        .plan-icon.pro   { background: #EFF6FF; color: #2563EB; }
        .plan-icon.ultra { background: #F5F3FF; color: #7C3AED; }

        .plan-name {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .plan-name.free  { color: #475569; }
        .plan-name.pro   { color: #2563EB; }
        .plan-name.ultra { color: #7C3AED; }

        .plan-desc {
            font-size: 12.5px;
            color: #94A3B8;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .plan-price-wrap { margin-bottom: 20px; }
        .plan-price {
            font-size: 36px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1;
        }
        .plan-price sup { font-size: 18px; font-weight: 600; vertical-align: super; }
        .plan-price sub { font-size: 13px; font-weight: 400; color: #64748B; vertical-align: baseline; margin-left: 2px; }
        .plan-price-old {
            font-size: 13px;
            color: #94A3B8;
            text-decoration: line-through;
            margin-left: 6px;
        }
        .plan-period { font-size: 12px; color: #94A3B8; margin-top: 4px; }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 24px;
            flex: 1;
        }
        .plan-features li {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13.5px;
            color: #334155;
            padding: 7px 0;
            border-bottom: 1px solid #F8FAFC;
        }
        .plan-features li:last-child { border-bottom: none; }
        .plan-features .feat-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 10px;
        }
        .feat-icon.yes { background: #F0FDF4; color: #16A34A; }
        .feat-icon.no  { background: #F8FAFC; color: #CBD5E1; }

        .plan-feat-label { flex: 1; }
        .plan-feat-limit {
            font-size: 11px;
            font-weight: 600;
            color: #94A3B8;
            background: #F1F5F9;
            padding: 1px 7px;
            border-radius: 10px;
        }

        /* CTA buttons */
        .btn-plan {
            display: block;
            text-align: center;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
            border: none;
            text-decoration: none;
        }
        .btn-plan.current {
            background: #F1F5F9;
            color: #64748B;
            cursor: default;
        }
        .btn-plan.upgrade-pro {
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
        }
        .btn-plan.upgrade-pro:hover {
            box-shadow: 0 6px 20px rgba(37,99,235,0.45);
            transform: translateY(-1px);
            color: #fff;
        }
        .btn-plan.upgrade-ultra {
            background: linear-gradient(135deg, #7C3AED, #6D28D9);
            color: #fff;
            box-shadow: 0 4px 14px rgba(124,58,237,0.30);
        }
        .btn-plan.upgrade-ultra:hover {
            box-shadow: 0 6px 20px rgba(124,58,237,0.40);
            transform: translateY(-1px);
            color: #fff;
        }

        /* ── Comparison table ── */
        .compare-section { margin-bottom: 48px; }
        .compare-title {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
        }
        .compare-sub { font-size: 13px; color: #64748B; margin-bottom: 20px; }

        .compare-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .compare-table thead tr { background: #F8FAFC; }
        .compare-table th { padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #F1F5F9; }
        .compare-table th:first-child { text-align: left; width: 35%; }
        .compare-table th.col-pro { color: #2563EB; }
        .compare-table th.col-ultra { color: #7C3AED; }
        .compare-table td { padding: 13px 16px; font-size: 13.5px; color: #334155; border-bottom: 1px solid #F8FAFC; text-align: center; }
        .compare-table td:first-child { text-align: left; font-weight: 500; }
        .compare-table tbody tr:last-child td { border-bottom: none; }
        .compare-table tbody tr:hover { background: #FAFCFF; }
        .compare-table .section-row td {
            background: #F8FAFC;
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 16px;
        }
        .compare-table .check { color: #16A34A; font-size: 16px; }
        .compare-table .cross { color: #CBD5E1; font-size: 16px; }
        .compare-table .val { font-weight: 600; color: #0F172A; }

        /* ── FAQ ── */
        .faq-section { margin-bottom: 48px; }
        .faq-title { font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px; }
        .faq-item {
            background: #fff;
            border-radius: 10px;
            border: 1.5px solid #F1F5F9;
            margin-bottom: 10px;
            overflow: hidden;
            transition: border-color 0.18s;
        }
        .faq-item.open { border-color: #BFDBFE; }
        .faq-q {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #0F172A;
            user-select: none;
        }
        .faq-q .faq-arrow {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.22s, background 0.18s;
            flex-shrink: 0;
        }
        .faq-item.open .faq-arrow { transform: rotate(180deg); background: #EFF6FF; color: #2563EB; }
        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.2s;
            font-size: 13.5px;
            color: #475569;
            line-height: 1.65;
            padding: 0 20px;
        }
        .faq-a.open { max-height: 200px; padding: 0 20px 16px; }

        /* ── CTA bottom banner ── */
        .upgrade-banner {
            background: linear-gradient(135deg, #081A3A 0%, #1E3A6E 100%);
            border-radius: 16px;
            padding: 36px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .upgrade-banner::before {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(37,99,235,0.15);
        }
        .upgrade-banner::after {
            content: '';
            position: absolute;
            right: 80px;
            bottom: -60px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(124,58,237,0.1);
        }
        .upgrade-banner h3 { font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .upgrade-banner p { font-size: 13.5px; color: rgba(255,255,255,0.65); }
        .banner-actions { display: flex; gap: 10px; flex-shrink: 0; position: relative; z-index: 1; }
        .btn-banner-primary {
            padding: 12px 24px;
            background: #2563EB;
            color: #fff;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s;
            white-space: nowrap;
        }
        .btn-banner-primary:hover { background: #1D4ED8; box-shadow: 0 4px 14px rgba(37,99,235,0.4); color: #fff; }
        .btn-banner-ghost {
            padding: 12px 20px;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.18s;
            white-space: nowrap;
        }
        .btn-banner-ghost:hover { background: rgba(255,255,255,0.14); color: #fff; }

        @media (max-width: 640px) {
            .upgrade-banner { flex-direction: column; align-items: flex-start; }
            .banner-actions { width: 100%; }
            .btn-banner-primary, .btn-banner-ghost { flex: 1; text-align: center; }
        }

        /* ── Animated entrance ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .plan-card { animation: fadeUp 0.45s ease both; }
        .plan-card:nth-child(1) { animation-delay: 0.05s; }
        .plan-card:nth-child(2) { animation-delay: 0.15s; }
        .plan-card:nth-child(3) { animation-delay: 0.25s; }
    </style>

    {{-- Hero --}}
    <div class="plans-hero">
        <div class="eyebrow">
            <i class="bi bi-lightning-charge-fill"></i> Planos & Preços
        </div>
        <h2>Escolha o plano ideal<br>para sua oficina</h2>
        <p>Comece grátis e escale conforme seu negócio cresce. Sem taxas escondidas.</p>
    </div>

    {{-- Billing toggle --}}
    <div class="billing-toggle">
        <span id="label-monthly" class="active-label">Mensal</span>
        <label class="toggle-switch" for="billingToggle">
            <input type="checkbox" id="billingToggle" onchange="toggleBilling()">
            <div class="toggle-track"></div>
            <div class="toggle-thumb"></div>
        </label>
        <span id="label-annual">Anual <span class="discount-badge">-20%</span></span>
    </div>

    {{-- Plan Cards --}}
    <div class="plans-grid">

        {{-- FREE --}}
        <div class="plan-card">
            <div class="plan-icon free"><i class="bi bi-rocket"></i></div>
            <div class="plan-name free">Free</div>
            <div class="plan-desc">Perfeito para começar e testar o sistema.</div>
            <div class="plan-price-wrap">
                <div>
                    <span class="plan-price"><sup>R$</sup>0</span>
                </div>
                <div class="plan-period">Para sempre gratuito</div>
            </div>
            <ul class="plan-features">
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Ordens de Serviço</span>
                    <span class="plan-feat-limit">até 20/mês</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Clientes</span>
                    <span class="plan-feat-limit">até 50</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Catálogo de Peças</span>
                    <span class="plan-feat-limit">até 100</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">1 Usuário</span>
                </li>
                <li>
                    <span class="feat-icon no"><i class="bi bi-x"></i></span>
                    <span class="plan-feat-label" style="color:#CBD5E1">Relatórios avançados</span>
                </li>
                <li>
                    <span class="feat-icon no"><i class="bi bi-x"></i></span>
                    <span class="plan-feat-label" style="color:#CBD5E1">Aprovação por link</span>
                </li>
                <li>
                    <span class="feat-icon no"><i class="bi bi-x"></i></span>
                    <span class="plan-feat-label" style="color:#CBD5E1">Suporte prioritário</span>
                </li>
            </ul>
            @php $planoAtual = auth()->user()->empresa?->plano ?? 'free'; @endphp
            @if($planoAtual === 'free')
                <span class="btn-plan current"><i class="bi bi-check-circle-fill"></i> Plano Atual</span>
            @else
                <a href="#" class="btn-plan upgrade-pro" onclick="alert('Entre em contato para fazer downgrade.')">Downgrade</a>
            @endif
        </div>

        {{-- PRO (popular) --}}
        <div class="plan-card popular">
            <div class="popular-badge"><i class="bi bi-star-fill"></i> Mais Popular</div>
            <div class="plan-icon pro"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="plan-name pro">Pro</div>
            <div class="plan-desc">Para oficinas em crescimento que precisam de mais recursos.</div>
            <div class="plan-price-wrap">
                <div style="display:flex;align-items:baseline;gap:6px">
                    <span class="plan-price" id="pro-price"><sup>R$</sup>97<sub>/mês</sub></span>
                    <span class="plan-price-old" id="pro-old" style="display:none">R$97</span>
                </div>
                <div class="plan-period" id="pro-period">Cobrado mensalmente</div>
            </div>
            <ul class="plan-features">
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Ordens de Serviço</span>
                    <span class="plan-feat-limit">ilimitadas</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Clientes</span>
                    <span class="plan-feat-limit">ilimitados</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Catálogo de Peças</span>
                    <span class="plan-feat-limit">ilimitado</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Até 5 Usuários</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Relatórios avançados</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Aprovação por link</span>
                </li>
                <li>
                    <span class="feat-icon no"><i class="bi bi-x"></i></span>
                    <span class="plan-feat-label" style="color:#CBD5E1">API + Integrações</span>
                </li>
            </ul>
            @if($planoAtual === 'pro')
                <span class="btn-plan current"><i class="bi bi-check-circle-fill"></i> Plano Atual</span>
            @else
                <a href="#" class="btn-plan upgrade-pro" onclick="solicitarUpgrade('pro')">
                    <i class="bi bi-arrow-up-circle"></i> Fazer Upgrade — Pro
                </a>
            @endif
        </div>

        {{-- ULTRA --}}
        <div class="plan-card">
            <div class="plan-icon ultra"><i class="bi bi-gem"></i></div>
            <div class="plan-name ultra">Ultra</div>
            <div class="plan-desc">Solução completa para redes e grandes operações.</div>
            <div class="plan-price-wrap">
                <div style="display:flex;align-items:baseline;gap:6px">
                    <span class="plan-price" id="ultra-price"><sup>R$</sup>197<sub>/mês</sub></span>
                    <span class="plan-price-old" id="ultra-old" style="display:none">R$197</span>
                </div>
                <div class="plan-period" id="ultra-period">Cobrado mensalmente</div>
            </div>
            <ul class="plan-features">
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Tudo do Pro</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Usuários ilimitados</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Multi-filial</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">API + Integrações</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Suporte prioritário 24/7</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">Gestor de conta dedicado</span>
                </li>
                <li>
                    <span class="feat-icon yes"><i class="bi bi-check"></i></span>
                    <span class="plan-feat-label">SLA garantido</span>
                </li>
            </ul>
            @if($planoAtual === 'ultra')
                <span class="btn-plan current"><i class="bi bi-check-circle-fill"></i> Plano Atual</span>
            @else
                <a href="#" class="btn-plan upgrade-ultra" onclick="solicitarUpgrade('ultra')">
                    <i class="bi bi-gem"></i> Fazer Upgrade — Ultra
                </a>
            @endif
        </div>
    </div>

    {{-- Comparison Table --}}
    <div class="compare-section">
        <div class="compare-title">Comparativo completo</div>
        <div class="compare-sub">Veja o que está incluído em cada plano</div>
        <div style="overflow-x:auto">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Recurso</th>
                        <th>Free</th>
                        <th class="col-pro">Pro</th>
                        <th class="col-ultra">Ultra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="section-row"><td colspan="4">Operacional</td></tr>
                    <tr>
                        <td>Ordens de Serviço</td>
                        <td class="val">20/mês</td>
                        <td class="val">Ilimitadas</td>
                        <td class="val">Ilimitadas</td>
                    </tr>
                    <tr>
                        <td>Clientes cadastrados</td>
                        <td class="val">50</td>
                        <td class="val">Ilimitados</td>
                        <td class="val">Ilimitados</td>
                    </tr>
                    <tr>
                        <td>Peças no catálogo</td>
                        <td class="val">100</td>
                        <td class="val">Ilimitadas</td>
                        <td class="val">Ilimitadas</td>
                    </tr>
                    <tr>
                        <td>Aprovação de OS por link</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>PDF de Ordens de Serviço</td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>

                    <tr class="section-row"><td colspan="4">Usuários & Equipe</td></tr>
                    <tr>
                        <td>Usuários</td>
                        <td class="val">1</td>
                        <td class="val">5</td>
                        <td class="val">Ilimitados</td>
                    </tr>
                    <tr>
                        <td>Controle de permissões</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Multi-filial</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>

                    <tr class="section-row"><td colspan="4">Relatórios & Analytics</td></tr>
                    <tr>
                        <td>Dashboard com gráficos</td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Relatórios avançados</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Exportação para Excel/PDF</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>

                    <tr class="section-row"><td colspan="4">Integrações & API</td></tr>
                    <tr>
                        <td>Integração WhatsApp</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>API REST pública</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Webhooks personalizados</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>

                    <tr class="section-row"><td colspan="4">Suporte</td></tr>
                    <tr>
                        <td>Suporte por e-mail</td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Suporte prioritário</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>Gestor de conta dedicado</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                    <tr>
                        <td>SLA garantido</td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-x-circle cross"></i></td>
                        <td><i class="bi bi-check-circle-fill check"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- FAQ --}}
    <div class="faq-section">
        <div class="faq-title">Perguntas frequentes</div>

        <div class="faq-item" id="faq-1">
            <div class="faq-q" onclick="toggleFaq('faq-1')">
                Posso cancelar a qualquer momento?
                <span class="faq-arrow"><i class="bi bi-chevron-down" style="font-size:12px"></i></span>
            </div>
            <div class="faq-a" id="faq-1-body">
                Sim! Você pode cancelar sua assinatura a qualquer momento sem multas ou taxas adicionais. Ao cancelar, você continua com acesso até o fim do período pago.
            </div>
        </div>

        <div class="faq-item" id="faq-2">
            <div class="faq-q" onclick="toggleFaq('faq-2')">
                O que acontece com meus dados se eu fizer downgrade?
                <span class="faq-arrow"><i class="bi bi-chevron-down" style="font-size:12px"></i></span>
            </div>
            <div class="faq-a" id="faq-2-body">
                Seus dados ficam preservados por 90 dias. Se os limites do plano inferior forem excedidos, novos cadastros ficam bloqueados mas os existentes são mantidos.
            </div>
        </div>

        <div class="faq-item" id="faq-3">
            <div class="faq-q" onclick="toggleFaq('faq-3')">
                Há desconto para plano anual?
                <span class="faq-arrow"><i class="bi bi-chevron-down" style="font-size:12px"></i></span>
            </div>
            <div class="faq-a" id="faq-3-body">
                Sim! Assinando anualmente você economiza 20% em relação ao valor mensal. É equivalente a ganhar mais de 2 meses grátis.
            </div>
        </div>

        <div class="faq-item" id="faq-4">
            <div class="faq-q" onclick="toggleFaq('faq-4')">
                Como funciona o suporte prioritário do plano Pro?
                <span class="faq-arrow"><i class="bi bi-chevron-down" style="font-size:12px"></i></span>
            </div>
            <div class="faq-a" id="faq-4-body">
                Clientes Pro têm atendimento por e-mail com resposta garantida em até 4 horas úteis. Clientes Ultra contam com suporte 24/7 via chat e gestor de conta dedicado.
            </div>
        </div>

        <div class="faq-item" id="faq-5">
            <div class="faq-q" onclick="toggleFaq('faq-5')">
                O upgrade é imediato?
                <span class="faq-arrow"><i class="bi bi-chevron-down" style="font-size:12px"></i></span>
            </div>
            <div class="faq-a" id="faq-5-body">
                Após a confirmação do pagamento, o upgrade é aplicado instantaneamente. Todos os novos recursos ficam disponíveis de imediato, sem necessidade de reiniciar o sistema.
            </div>
        </div>
    </div>

    {{-- Bottom CTA Banner --}}
    <div class="upgrade-banner">
        <div style="position:relative;z-index:1">
            <h3>Pronto para escalar sua oficina?</h3>
            <p>Junte-se a centenas de oficinas que já usam o MecDesk Pro.</p>
        </div>
        <div class="banner-actions">
            <a href="#" class="btn-banner-ghost" onclick="solicitarUpgrade('pro')">Teste 14 dias grátis</a>
            <a href="#" class="btn-banner-primary" onclick="solicitarUpgrade('pro')">
                <i class="bi bi-arrow-up-circle"></i> Fazer Upgrade Agora
            </a>
        </div>
    </div>

    {{-- Upgrade Modal --}}
    <div id="upgradeModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(8,26,58,0.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:20px;padding:36px 32px;max-width:420px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,0.2);animation:fadeUp 0.3s ease both;position:relative">
            <button onclick="fecharModal()" style="position:absolute;top:16px;right:16px;background:#F1F5F9;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px;color:#64748B;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-x"></i>
            </button>
            <div id="modal-icon" style="width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:20px;"></div>
            <h3 id="modal-title" style="font-size:20px;font-weight:700;color:#0F172A;margin-bottom:8px"></h3>
            <p style="font-size:13.5px;color:#64748B;margin-bottom:24px;line-height:1.6">Para realizar o upgrade, entre em contato com nossa equipe. Responderemos em minutos.</p>

            <div style="display:flex;flex-direction:column;gap:10px">
                <a href="#" id="modal-whatsapp" target="_blank"
                   style="display:flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:13px 18px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all 0.18s">
                    <i class="bi bi-whatsapp" style="font-size:18px"></i>
                    Falar pelo WhatsApp
                </a>
                <a href="#" id="modal-email"
                   style="display:flex;align-items:center;gap:10px;background:#F1F5F9;color:#334155;padding:13px 18px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all 0.18s">
                    <i class="bi bi-envelope-fill" style="font-size:16px;color:#2563EB"></i>
                    Enviar por E-mail
                </a>
            </div>
        </div>
    </div>

    <script>
        // ── Billing toggle ──
        const proMonthly   = 97,  proAnnual   = Math.round(97 * 0.8);
        const ultraMonthly = 197, ultraAnnual = Math.round(197 * 0.8);

        function toggleBilling() {
            const annual = document.getElementById('billingToggle').checked;

            // Pro
            document.getElementById('pro-price').innerHTML =
                '<sup>R$<\/sup>' + (annual ? proAnnual : proMonthly) + '<sub>\/mês<\/sub>';
            document.getElementById('pro-old').textContent = 'R$' + proMonthly;
            document.getElementById('pro-old').style.display = annual ? 'inline' : 'none';
            document.getElementById('pro-period').textContent =
                annual ? 'Cobrado anualmente (R$' + (proAnnual * 12) + '/ano)' : 'Cobrado mensalmente';

            // Ultra
            document.getElementById('ultra-price').innerHTML =
                '<sup>R$<\/sup>' + (annual ? ultraAnnual : ultraMonthly) + '<sub>\/mês<\/sub>';
            document.getElementById('ultra-old').textContent = 'R$' + ultraMonthly;
            document.getElementById('ultra-old').style.display = annual ? 'inline' : 'none';
            document.getElementById('ultra-period').textContent =
                annual ? 'Cobrado anualmente (R$' + (ultraAnnual * 12) + '/ano)' : 'Cobrado mensalmente';

            // Labels
            document.getElementById('label-monthly').classList.toggle('active-label', !annual);
            document.getElementById('label-annual').classList.toggle('active-label', annual);
        }

        // ── FAQ accordion ──
        function toggleFaq(id) {
            const item = document.getElementById(id);
            const body = document.getElementById(id + '-body');
            const isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-item').forEach(function(el) {
                el.classList.remove('open');
                el.querySelector('.faq-a').classList.remove('open');
            });

            if (!isOpen) {
                item.classList.add('open');
                body.classList.add('open');
            }
        }

        // ── Upgrade modal ──
        const empresaNome = '{{ addslashes(auth()->user()->empresa?->nome_fantasia ?? auth()->user()->name) }}';

        function solicitarUpgrade(plano) {
            const annual = document.getElementById('billingToggle').checked;
            const prices = { pro: annual ? proAnnual : proMonthly, ultra: annual ? ultraAnnual : ultraMonthly };
            const icons  = { pro: '<i class="bi bi-lightning-charge-fill"><\/i>', ultra: '<i class="bi bi-gem"><\/i>' };
            const styles = { pro: 'background:#EFF6FF;color:#2563EB', ultra: 'background:#F5F3FF;color:#7C3AED' };
            const names  = { pro: 'Pro', ultra: 'Ultra' };

            document.getElementById('modal-icon').setAttribute('style',
                'width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:20px;' + styles[plano]);
            document.getElementById('modal-icon').innerHTML = icons[plano];
            document.getElementById('modal-title').textContent =
                'Upgrade para o Plano ' + names[plano] + ' — R$' + prices[plano] + '/mês';

            const msg = encodeURIComponent('Olá! Quero fazer upgrade para o Plano ' + names[plano] + ' (R$' + prices[plano] + '/mês). Minha empresa é: ' + empresaNome);
            document.getElementById('modal-whatsapp').href = 'https://wa.me/5500000000000?text=' + msg;
            document.getElementById('modal-email').href =
                'mailto:comercial@mecdesk.com.br?subject=Upgrade%20Plano%20' + names[plano] + '&body=' + msg;

            const modal = document.getElementById('upgradeModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function fecharModal() {
            document.getElementById('upgradeModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('upgradeModal').addEventListener('click', function(e) {
            if (e.target === this) fecharModal();
        });
    </script>

</x-app-layout>
