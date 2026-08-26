<x-public-layout>
    <x-slot name="title">Contrate o MecDesk — Gestão Completa de Oficina Mecânica</x-slot>

    <div class="max-w-5xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">

        {{-- ── Cabeçalho do Fluxo ── --}}
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-bold uppercase tracking-wider mb-3">
                <i class="bi bi-shield-check text-blue-600"></i> Contratação Segura & Sem Fidelidade
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight">
                Contrate o MecDesk
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-2">
                Configure sua oficina e tenha acesso imediato a todas as ferramentas de gestão.
            </p>
        </div>

        {{-- ── Barra de Progresso / Stepper (3 Etapas) ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm mb-8 sm:mb-10">
            <div class="relative flex items-center justify-between">

                {{-- Linha conectora de fundo --}}
                <div class="absolute left-0 right-0 top-5 sm:top-5 h-0.5 bg-slate-200 mx-5 sm:mx-6 -z-0">
                    <div id="stepperProgressLine"
                        class="h-full bg-blue-600 transition-all duration-500 {{ $initialStep === 2 ? 'w-1/2' : 'w-0' }}">
                    </div>
                </div>

                {{-- Etapa 1: Seus Dados --}}
                <div id="stepHeader1" class="relative z-10 flex flex-col items-center gap-1.5 text-center">
                    <div id="stepBadge1"
                        class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm {{ $initialStep === 1 ? 'bg-blue-600 text-white shadow-blue-500/25 ring-4 ring-blue-100' : 'bg-emerald-600 text-white' }}">
                        @if ($initialStep > 1)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <span>1</span>
                        @endif
                    </div>
                    <div>
                        <span id="stepTitle1"
                            class="block text-xs sm:text-sm font-bold {{ $initialStep === 1 ? 'text-slate-950' : 'text-emerald-700' }}">1. Seus dados</span>
                        <span class="hidden sm:block text-[11px] text-slate-500">Conta & Oficina</span>
                    </div>
                </div>

                {{-- Etapa 2: Pagamento --}}
                <div id="stepHeader2" class="relative z-10 flex flex-col items-center gap-1.5 text-center">
                    <div id="stepBadge2"
                        class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm {{ $initialStep === 2 ? 'bg-blue-600 text-white shadow-blue-500/25 ring-4 ring-blue-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                        <span>2</span>
                    </div>
                    <div>
                        <span id="stepTitle2"
                            class="block text-xs sm:text-sm font-bold {{ $initialStep === 2 ? 'text-slate-950' : 'text-slate-500' }}">2. Pagamento</span>
                        <span class="hidden sm:block text-[11px] text-slate-500">Mercado Pago</span>
                    </div>
                </div>

                {{-- Etapa 3: Confirmação --}}
                <div id="stepHeader3" class="relative z-10 flex flex-col items-center gap-1.5 text-center">
                    <div id="stepBadge3"
                        class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-slate-100 text-slate-500 border border-slate-200 transition-all duration-300 shadow-sm">
                        <span>3</span>
                    </div>
                    <div>
                        <span id="stepTitle3" class="block text-xs sm:text-sm font-bold text-slate-500">3. Confirmação</span>
                        <span class="hidden sm:block text-[11px] text-slate-500">Acesso liberado</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Container de Erros Globais --}}
        <div id="globalAlert"
            class="hidden mb-6 p-4 rounded-2xl border text-sm flex items-center justify-between shadow-sm transition-all">
            <div class="flex items-center gap-3">
                <i id="globalAlertIcon" class="bi bi-exclamation-circle-fill text-lg shrink-0"></i>
                <span id="globalAlertText"></span>
            </div>
            <button type="button" onclick="hideGlobalAlert()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        {{-- ETAPA 1 — CRIAR CONTA E OFICINA                                       --}}
        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        <div id="stepSection1" class="{{ $initialStep === 1 ? 'block' : 'hidden' }}">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Formulário de Cadastro --}}
                <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="mb-6">
                        <span
                            class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Etapa
                            1 de 3</span>
                        <h2 class="text-2xl font-black text-slate-950 mt-2">Dados da conta e da oficina</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Preencha as informações para criar seu acesso
                            ao sistema</p>
                    </div>

                    <form id="formCadastro" onsubmit="submitStep1(event)" novalidate class="space-y-4">
                        @csrf

                        {{-- Nome da Oficina --}}
                        <div>
                            <label for="empresa"
                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nome da Oficina <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="empresa" name="empresa" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                    placeholder="Ex: Auto Mecânica São Paulo" autofocus>
                            </div>
                            <p id="error_empresa" class="text-xs text-rose-600 mt-1 hidden"></p>
                        </div>

                        {{-- Nome Completo do Responsável --}}
                        <div>
                            <label for="name"
                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Seu Nome Completo <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="name" name="name" required autocomplete="name"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                    placeholder="Ex: João da Silva">
                            </div>
                            <p id="error_name" class="text-xs text-rose-600 mt-1 hidden"></p>
                        </div>

                        {{-- E-mail e Telefone em 2 Colunas --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email"
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    E-mail de Acesso <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" required autocomplete="email"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                        placeholder="seuemail@exemplo.com">
                                </div>
                                <p id="error_email" class="text-xs text-rose-600 mt-1 hidden"></p>
                            </div>

                            <div>
                                <label for="telefone"
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    WhatsApp / Telefone
                                </label>
                                <div class="relative">
                                    </span>
                                    <input type="text" id="telefone" name="telefone" autocomplete="tel"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                        placeholder="(11) 99999-9999">
                                </div>
                                <p id="error_telefone" class="text-xs text-rose-600 mt-1 hidden"></p>
                            </div>
                        </div>

                        {{-- Senha e Confirmação em 2 Colunas --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password"
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Senha <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required
                                        autocomplete="new-password"
                                        class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                        placeholder="Mínimo 8 dígitos">
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600"
                                        aria-label="Mostrar/ocultar senha">
                                        <svg id="eyeOpen_password" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="eyeClosed_password" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                <p id="error_password" class="text-xs text-rose-600 mt-1 hidden"></p>
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Confirmar Senha <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required autocomplete="new-password"
                                        class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                                        placeholder="Repita a senha">
                                    <button type="button" onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600"
                                        aria-label="Mostrar/ocultar senha">
                                        <svg id="eyeOpen_password_confirmation" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="eyeClosed_password_confirmation" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Botão de Ação Step 1 --}}
                        <div class="pt-4">
                            <button type="submit" id="btnSubmitStep1"
                                class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-base">
                                <span id="btnTextStep1">Continuar para o Pagamento</span>
                                <svg id="btnIconStep1" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                                <svg id="btnSpinnerStep1" class="animate-spin h-5 w-5 text-white hidden"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <p class="text-xs text-slate-500">
                                Já possui uma conta?
                                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Fazer
                                    login</a>
                            </p>
                        </div>
                    </form>
                </div>

                {{-- Resumo Lateral da Contratação --}}
                <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <span
                            class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Plano
                            Selecionado</span>
                        <h3 class="text-2xl font-black text-slate-950 mt-3 mb-1">MecDesk {{ $plano->nome }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $plano->descricao }}</p>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-3">
                        <div class="flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-800">Mensalidade</span>
                            <div class="text-right">
                                <span class="text-2xl sm:text-3xl font-black text-blue-600">
                                    R$ {{ number_format($amount, 2, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-500 font-normal block">/ mês</span>
                            </div>
                        </div>

                        <div
                            class="bg-slate-50 rounded-2xl p-4 text-xs text-slate-600 border border-slate-100 space-y-2">
                            <div class="flex items-center gap-2 font-bold text-slate-800">
                                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                                <span>Acesso Imediato e Completo:</span>
                            </div>
                            <ul class="space-y-1.5 pl-5 text-slate-600 list-disc">
                                <li>Ordens de Serviço e Orçamentos</li>
                                <li>Aprovação por link seguro de cliente</li>
                                <li>Cadastro de clientes e veículos</li>
                                <li>Tabela de serviços e estoque de peças</li>
                                <li>Emissão de PDF com a sua marca</li>
                            </ul>
                        </div>
                    </div>

                    <div
                        class="border-t border-slate-100 pt-4 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 font-semibold">
                        <span class="flex items-center gap-1.5 text-emerald-700">
                            <i class="bi bi-shield-check text-emerald-600"></i> Sem fidelidade
                        </span>
                        <span class="flex items-center gap-1.5 text-emerald-700">
                            <i class="bi bi-x-circle text-emerald-600"></i> Cancele quando quiser
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        {{-- ETAPA 2 — PAGAMENTO (MERCADO PAGO)                                    --}}
        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        <div id="stepSection2" class="{{ $initialStep === 2 ? 'block' : 'hidden' }}">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Card Resumo da Assinatura --}}
                <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <div>
                        <span
                            class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Resumo
                            da Assinatura</span>
                        <h2 class="text-2xl font-black text-slate-950 mt-3 mb-1">MecDesk {{ $plano->nome }}</h2>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $plano->descricao }}</p>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Oficina</span>
                            <span id="summaryOficinaNome" class="font-bold text-slate-900 truncate max-w-[180px]">
                                {{ $empresa?->nome_fantasia ?? 'Sua Oficina' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Responsável</span>
                            <span id="summaryUserName" class="font-bold text-slate-900 truncate max-w-[180px]">
                                {{ $user?->name ?? 'Responsável' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Cobrança</span>
                            <span class="font-bold text-slate-900">Recorrência Mensal</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Forma</span>
                            <span class="font-bold text-slate-900">Cartão de Crédito</span>
                        </div>

                        <div class="border-t border-slate-100 pt-4 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-800">Total a pagar</span>
                            <div class="text-right">
                                <span class="text-3xl font-black text-blue-600">
                                    R$ {{ number_format($amount, 2, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-500 font-normal block">/ mês</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-blue-50/60 rounded-2xl p-4 text-xs text-blue-900 border border-blue-100 space-y-1.5">
                        <p class="font-bold flex items-center gap-1.5">
                            <i class="bi bi-shield-lock-fill text-blue-600"></i>
                            Segurança Garantida Mercado Pago
                        </p>
                        <p class="text-slate-600 leading-relaxed">
                            Dados protegidos por criptografia de ponta a ponta. Cancele sua assinatura a qualquer
                            momento no painel da sua conta.
                        </p>
                    </div>

                    {{-- Botão Voltar para Etapa 1 --}}
                    @if (!$user)
                        <div class="pt-2">
                            <button type="button" onclick="goToStep(1)"
                                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                <i class="bi bi-arrow-left"></i> Alterar dados cadastrais
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Formulário Mercado Pago Card Brick --}}
                <div
                    class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm min-h-[480px]">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <span
                                class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Etapa
                                2 de 3</span>
                            <h3 class="text-xl font-bold text-slate-900 mt-2">Dados do Cartão de Crédito</h3>
                            <p class="text-xs text-slate-500">Informe os dados do seu cartão para ativação imediata</p>
                        </div>
                        <div
                            class="hidden sm:flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-full">
                            <i class="bi bi-shield-check text-emerald-600"></i> SSL Seguro
                        </div>
                    </div>

                    <div id="brickLoading" class="flex flex-col items-center justify-center py-16 text-slate-400">
                        <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Carregando formulário seguro do Mercado Pago...</span>
                    </div>

                    <div id="cardPaymentBrick_container"></div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        {{-- ETAPA 3 — CONFIRMAÇÃO & CONCLUÍDO                                     --}}
        {{-- ══════════════════════════════════════════════════════════════════════ --}}
        <div id="stepSection3" class="hidden">
            <div
                class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 sm:p-12 text-center relative overflow-hidden max-w-3xl mx-auto">

                {{-- Background Accent Glow --}}
                <div
                    class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none">
                </div>

                {{-- Ícone com Animação de Sucesso --}}
                <div
                    class="relative z-10 w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/10 border border-emerald-200/60 ring-8 ring-emerald-50">
                    <i class="bi bi-check-circle-fill text-4xl"></i>
                </div>

                <h2 class="relative z-10 text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
                    Parabéns! Sua conta está ativa 🎉
                </h2>
                <p class="relative z-10 text-base text-slate-600 max-w-lg mx-auto leading-relaxed mb-8">
                    O pagamento da sua assinatura do <strong class="text-slate-900">MecDesk Pro</strong> foi confirmado
                    com sucesso. Agora sua oficina está pronta para operar!
                </p>

                {{-- Card Resumo da Ativação --}}
                <div
                    class="relative z-10 bg-slate-50 border border-slate-200/80 rounded-2xl p-6 max-w-md mx-auto mb-8 text-left space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                        <div>
                            <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400">Oficina
                                Ativada</span>
                            <h3 id="step3Oficina" class="text-base font-bold text-slate-900 mt-0.5">
                                {{ $empresa?->nome_fantasia ?? 'Sua Oficina' }}
                            </h3>
                        </div>
                        <span
                            class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full flex items-center gap-1.5 border border-emerald-200/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Ativo
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                        <div>
                            <span class="text-slate-500 block mb-0.5 font-medium">Plano Contratado</span>
                            <span class="font-bold text-slate-900 text-sm">MecDesk Pro</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block mb-0.5 font-medium">Valor</span>
                            <span class="font-bold text-blue-600 text-sm">R$ {{ number_format($amount, 2, ',', '.') }}
                                /mês</span>
                        </div>
                    </div>
                </div>

                {{-- Botão de Ação Primária --}}
                <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('dashboard') }}"
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2 text-base">
                        <span>Acessar o MecDesk</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            const publicKey = '{{ $publicKey }}';
            const amount = {{ $amount }};
            let currentStep = {{ $initialStep }};
            let userEmail = '{{ $user?->email ?? '' }}';
            let userName = '{{ $user?->name ?? '' }}';
            let empresaNome = '{{ $empresa?->nome_fantasia ?? '' }}';

            function generateUUID() {
                if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
                    return crypto.randomUUID();
                }
                return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                    (c ^ (typeof crypto !== 'undefined' && crypto.getRandomValues ? crypto.getRandomValues(new Uint8Array(
                        1))[0] : Math.floor(Math.random() * 16)) & (15 >> (c / 4))).toString(16)
                );
            }
            const idempotencyKey = generateUUID();

            let mp = null;
            let bricksBuilder = null;
            let cardPaymentBrickMounted = false;

            function showGlobalAlert(msg, type = 'error') {
                const alert = document.getElementById('globalAlert');
                const text = document.getElementById('globalAlertText');
                const icon = document.getElementById('globalAlertIcon');

                if (!alert || !text) return;

                text.textContent = msg;
                alert.classList.remove('hidden', 'bg-rose-50', 'border-rose-200', 'text-rose-800', 'bg-emerald-50',
                    'border-emerald-200', 'text-emerald-800', 'bg-blue-50', 'border-blue-200', 'text-blue-800');

                if (type === 'success') {
                    alert.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                    icon.className = 'bi bi-check-circle-fill text-lg shrink-0 text-emerald-600';
                } else if (type === 'info') {
                    alert.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-800');
                    icon.className = 'bi bi-info-circle-fill text-lg shrink-0 text-blue-600';
                } else {
                    alert.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-800');
                    icon.className = 'bi bi-exclamation-circle-fill text-lg shrink-0 text-rose-600';
                }

                alert.classList.remove('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function hideGlobalAlert() {
                const alert = document.getElementById('globalAlert');
                if (alert) alert.classList.add('hidden');
            }

            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                const openIcon = document.getElementById('eyeOpen_' + inputId);
                const closedIcon = document.getElementById('eyeClosed_' + inputId);
                if (openIcon) openIcon.classList.toggle('hidden', !isPassword);
                if (closedIcon) closedIcon.classList.toggle('hidden', isPassword);
            }

            const CHECK_SVG =
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';

            function updateStepperUI(step) {
                currentStep = step;

                const badge1 = document.getElementById('stepBadge1');
                const title1 = document.getElementById('stepTitle1');
                const badge2 = document.getElementById('stepBadge2');
                const title2 = document.getElementById('stepTitle2');
                const badge3 = document.getElementById('stepBadge3');
                const title3 = document.getElementById('stepTitle3');
                const progressLine = document.getElementById('stepperProgressLine');
                const BASE_BADGE =
                    'w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-sm';

                if (step === 1) {
                    badge1.className = BASE_BADGE + ' bg-blue-600 text-white shadow-blue-500/25 ring-4 ring-blue-100';
                    badge1.innerHTML = '1';
                    title1.className = 'block text-xs sm:text-sm font-bold text-slate-950';

                    badge2.className = BASE_BADGE + ' bg-slate-100 text-slate-500 border border-slate-200';
                    badge2.innerHTML = '2';
                    title2.className = 'block text-xs sm:text-sm font-bold text-slate-500';

                    badge3.className = BASE_BADGE + ' bg-slate-100 text-slate-500 border border-slate-200';
                    badge3.innerHTML = '3';
                    title3.className = 'block text-xs sm:text-sm font-bold text-slate-500';

                    if (progressLine) progressLine.style.width = '0%';
                } else if (step === 2) {
                    badge1.className = BASE_BADGE + ' bg-emerald-600 text-white';
                    badge1.innerHTML = CHECK_SVG;
                    title1.className = 'block text-xs sm:text-sm font-bold text-emerald-700';

                    badge2.className = BASE_BADGE + ' bg-blue-600 text-white shadow-blue-500/25 ring-4 ring-blue-100';
                    badge2.innerHTML = '2';
                    title2.className = 'block text-xs sm:text-sm font-bold text-slate-950';

                    badge3.className = BASE_BADGE + ' bg-slate-100 text-slate-500 border border-slate-200';
                    badge3.innerHTML = '3';
                    title3.className = 'block text-xs sm:text-sm font-bold text-slate-500';

                    if (progressLine) progressLine.style.width = '50%';
                } else if (step === 3) {
                    badge1.className = BASE_BADGE + ' bg-emerald-600 text-white';
                    badge1.innerHTML = CHECK_SVG;
                    title1.className = 'block text-xs sm:text-sm font-bold text-emerald-700';

                    badge2.className = BASE_BADGE + ' bg-emerald-600 text-white';
                    badge2.innerHTML = CHECK_SVG;
                    title2.className = 'block text-xs sm:text-sm font-bold text-emerald-700';

                    badge3.className = BASE_BADGE + ' bg-emerald-600 text-white ring-4 ring-emerald-100';
                    badge3.innerHTML = CHECK_SVG;
                    title3.className = 'block text-xs sm:text-sm font-bold text-emerald-700';

                    if (progressLine) progressLine.style.width = '100%';
                }
            }

            function goToStep(step) {
                hideGlobalAlert();

                document.getElementById('stepSection1').classList.toggle('hidden', step !== 1);
                document.getElementById('stepSection2').classList.toggle('hidden', step !== 2);
                document.getElementById('stepSection3').classList.toggle('hidden', step !== 3);

                updateStepperUI(step);

                if (step === 2) {
                    document.getElementById('summaryOficinaNome').textContent = empresaNome || 'Sua Oficina';
                    document.getElementById('summaryUserName').textContent = userName || 'Responsável';
                    initCardBrick();
                } else if (step === 3) {
                    const s3Oficina = document.getElementById('step3Oficina');
                    if (s3Oficina) s3Oficina.textContent = empresaNome || 'Sua Oficina';
                }

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            async function submitStep1(event) {
                event.preventDefault();
                hideGlobalAlert();

                // Clear errors
                ['empresa', 'name', 'email', 'telefone', 'password'].forEach(field => {
                    const el = document.getElementById('error_' + field);
                    if (el) {
                        el.textContent = '';
                        el.classList.add('hidden');
                    }
                });

                const btn = document.getElementById('btnSubmitStep1');
                const btnText = document.getElementById('btnTextStep1');
                const btnIcon = document.getElementById('btnIconStep1');
                const btnSpinner = document.getElementById('btnSpinnerStep1');

                btn.disabled = true;
                btnText.textContent = 'Criando sua conta...';
                btnIcon.classList.add('hidden');
                btnSpinner.classList.remove('hidden');

                const formData = {
                    empresa: document.getElementById('empresa').value,
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    telefone: document.getElementById('telefone').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value,
                };

                try {
                    const res = await fetch("{{ route('contratar.criar-conta') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errEl = document.getElementById('error_' + key);
                                if (errEl) {
                                    errEl.textContent = data.errors[key][0];
                                    errEl.classList.remove('hidden');
                                }
                            });
                            showGlobalAlert('Por favor, corrija os campos destacados.');
                        } else {
                            showGlobalAlert(data.message || 'Erro ao criar conta.');
                        }
                        return;
                    }

                    // Salva os dados na memória do browser e avança para a Etapa 2
                    userEmail = data.user.email;
                    userName = data.user.name;
                    empresaNome = data.empresa.nome_fantasia;

                    goToStep(2);

                } catch (err) {
                    console.error(err);
                    showGlobalAlert('Erro de conexão ao processar o cadastro. Tente novamente.');
                } finally {
                    btn.disabled = false;
                    btnText.textContent = 'Continuar para o Pagamento';
                    btnIcon.classList.remove('hidden');
                    btnSpinner.classList.add('hidden');
                }
            }

            async function initCardBrick() {
                if (cardPaymentBrickMounted) return;

                const loadingSpinner = document.getElementById('brickLoading');
                if (loadingSpinner) loadingSpinner.style.display = 'flex';

                if (!mp) {
                    mp = new MercadoPago(publicKey, {
                        locale: 'pt-BR'
                    });
                    bricksBuilder = mp.bricks();
                }

                const settings = {
                    initialization: {
                        amount: amount,
                        payer: {
                            email: userEmail || 'cliente@exemplo.com.br',
                        },
                    },
                    customization: {
                        visual: {
                            style: {
                                theme: "flat",
                            },
                        },
                        paymentMethods: {
                            minInstallments: 1,
                            maxInstallments: 1,
                        },
                    },
                    callbacks: {
                        onReady: () => {
                            if (loadingSpinner) loadingSpinner.style.display = 'none';
                            cardPaymentBrickMounted = true;
                        },
                        onSubmit: (cardFormData) => {
                            hideGlobalAlert();

                            return new Promise((resolve, reject) => {
                                fetch("{{ route('checkout.processar') }}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                            "Accept": "application/json"
                                        },
                                        body: JSON.stringify({
                                            card_token_id: cardFormData.token,
                                            idempotency_key: idempotencyKey,
                                        }),
                                    })
                                    .then(async (response) => {
                                        const data = await response.json();
                                        if (!response.ok || data.error) {
                                            const msg = data.message || data.error ||
                                                'Não foi possível processar a assinatura com este cartão.';
                                            showGlobalAlert(msg);
                                            reject(msg);
                                        } else {
                                            resolve();
                                            if (data.status === 'authorized') {
                                                goToStep(3);
                                            } else {
                                                window.location.href =
                                                    "{{ route('assinatura.pendente') }}";
                                            }
                                        }
                                    })
                                    .catch((error) => {
                                        const msg = typeof error === 'string' ? error : (error
                                            .message || 'Erro de conexão.');
                                        showGlobalAlert(msg);
                                        reject(error);
                                    });
                            });
                        },
                        onError: (error) => {
                            console.error("Erro no Card Payment Brick:", error);
                            showGlobalAlert(
                                "Ocorreu um erro no formulário de pagamento. Por favor, recarregue a página.");
                        },
                    },
                };

                window.cardPaymentBrickController = await bricksBuilder.create(
                    "cardPayment",
                    "cardPaymentBrick_container",
                    settings
                );
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (currentStep === 2) {
                    initCardBrick();
                }
            });
        </script>
    @endpush
</x-public-layout>
