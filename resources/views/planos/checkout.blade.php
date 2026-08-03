<x-app-layout>
    <x-slot name="header">Checkout de Assinatura — Plano {{ $plano->nome }}</x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4">
        <div class="mb-6">
            <a href="{{ route('planos.upgrade') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar aos planos
            </a>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl mb-6 text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Seletor de Modalidade de Pagamento -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <label class="block text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-3">Escolha a Modalidade de Pagamento</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Opção 1: Assinatura Mensal (Apenas 1x) -->
                <label id="opt-mensal-label" class="cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-blue-600 bg-blue-50/50">
                    <input type="radio" name="modalidade_pagamento" value="mensal" {{ $tipoPagamento === 'mensal' ? 'checked' : '' }} onchange="alterarModalidade('mensal')" class="mt-1 text-blue-600 focus:ring-blue-500">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-extrabold text-slate-900 text-base">Assinatura Mensal</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md">1x Sem Juros</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Cobrança recorrente a cada 30 dias. Pagamento em parcela única (1x).</p>
                        <div class="mt-2 text-sm font-black text-slate-900">
                            R$ {{ number_format($precoMensal, 2, ',', '.') }} <span class="text-xs font-normal text-slate-500">/mês</span>
                        </div>
                    </div>
                </label>

                <!-- Opção 2: Pagamento Único Anual (Até 12x) -->
                <label id="opt-unico-label" class="cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-slate-200 hover:border-slate-300">
                    <input type="radio" name="modalidade_pagamento" value="unico" {{ $tipoPagamento === 'unico' ? 'checked' : '' }} onchange="alterarModalidade('unico')" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-extrabold text-slate-900 text-base">Pagar Apenas Uma Vez (Anual)</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">Até 12x no Cartão</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">12 meses de acesso pelo valor com desconto especial. Parcelamento disponível.</p>
                        <div class="mt-2 text-sm font-black text-emerald-700">
                            R$ {{ number_format($precoUnico, 2, ',', '.') }} <span class="text-xs font-normal text-slate-500">/ano total</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Resumo do Plano (Servidor) -->
            <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
                <span class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Resumo do Pedido</span>
                <h3 class="text-2xl font-black text-slate-900 mt-4 mb-1">{{ $plano->nome }}</h3>
                <p class="text-xs text-slate-500 mb-6">{{ $plano->descricao }}</p>

                <div class="border-t border-slate-100 pt-4 mb-6 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Modalidade</span>
                        <span id="summaryModalidadeText" class="font-bold text-slate-900">
                            {{ $tipoPagamento === 'unico' ? 'Pagamento Único (Anual)' : 'Assinatura Mensal' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Parcelas permitidas</span>
                        <span id="summaryParcelasText" class="font-bold text-slate-900">
                            {{ $tipoPagamento === 'unico' ? 'Até 12x no Cartão' : 'Apenas 1x (Parcela Única)' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-emerald-600">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Segurança SSL
                        </span>
                        <span class="font-semibold text-xs">Criptografado</span>
                    </div>
                    <div class="border-t border-slate-100 mt-4 pt-4 flex justify-between items-center text-base font-extrabold text-slate-900">
                        <span>Total Hoje</span>
                        <span id="summaryTotalText" class="text-blue-600 text-2xl font-black">
                            R$ {{ number_format($amount, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 text-xs text-slate-500 border border-slate-100">
                    <p class="font-semibold text-slate-700 mb-1">Pagamento 100% Seguro</p>
                    O valor é validado estritamente no servidor. Seus dados são processados com segurança pelo Mercado Pago.
                </div>
            </div>

            <!-- Mercado Pago Payment Brick Container -->
            <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm min-h-[480px]">
                <div id="brickLoading" class="flex flex-col items-center justify-center py-16 text-slate-400">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Carregando formulário seguro do Mercado Pago...</span>
                </div>

                <div id="paymentBrick_container"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        let currentTipo = '{{ $tipoPagamento }}';
        const precoMensal = {{ $precoMensal }};
        const precoUnico  = {{ $precoUnico }};
        const publicKey   = '{{ $publicKey }}';

        let mp = null;
        let bricksBuilder = null;

        function formatBrl(valor) {
            return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        async function renderPaymentBrick(tipo) {
            const container = document.getElementById('paymentBrick_container');
            const loadingSpinner = document.getElementById('brickLoading');

            if (loadingSpinner) loadingSpinner.style.display = 'flex';
            container.innerHTML = '';

            // Se o controller anterior já existir, remove a instância antiga
            if (window.paymentBrickController && typeof window.paymentBrickController.unmount === 'function') {
                try {
                    await window.paymentBrickController.unmount();
                } catch (e) {
                    console.warn('Brick unmount warning:', e);
                }
            }

            const isMensal = (tipo === 'mensal');
            const currentAmount = isMensal ? precoMensal : precoUnico;
            // REGRA ESTREITA DE NEGÓCIO: Mensalidade permite APENAS 1x; Pagamento Único permite até 12x
            const maxInstallments = isMensal ? 1 : 12;

            const settings = {
                initialization: {
                    amount: currentAmount,
                    payer: {
                        email: '{{ auth()->user()->email }}',
                    },
                },
                customization: {
                    paymentMethods: {
                        creditCard: "all",
                        debitCard: "all",
                        ticket: "all",
                        bankTransfer: "all", // PIX
                        maxInstallments: maxInstallments,
                    },
                    visual: {
                        style: {
                            theme: "default",
                        },
                    },
                },
                callbacks: {
                    onReady: () => {
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {
                        // Injeta a modalidade escolhida no payload JSON enviado para o backend
                        formData.tipo_pagamento = currentTipo;

                        return new Promise((resolve, reject) => {
                            fetch("{{ route('checkout.processar') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                },
                                body: JSON.stringify(formData),
                            })
                            .then(async (response) => {
                                const data = await response.json();
                                if (!response.ok || data.error) {
                                    reject(data.message || 'Erro ao processar pagamento.');
                                } else {
                                    resolve();
                                    if (data.status === 'approved') {
                                        window.location.href = "{{ route('dashboard') }}";
                                    } else {
                                        window.location.href = "{{ route('assinatura.pendente') }}";
                                    }
                                }
                            })
                            .catch((error) => {
                                reject(error);
                            });
                        });
                    },
                    onError: (error) => {
                        console.error("Erro no Payment Brick:", error);
                    },
                },
            };

            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
            );
        }

        function alterarModalidade(novoTipo) {
            currentTipo = novoTipo;
            const isMensal = (novoTipo === 'mensal');

            // Atualiza destaque visual dos cards de seleção
            const labelMensal = document.getElementById('opt-mensal-label');
            const labelUnico  = document.getElementById('opt-unico-label');

            if (isMensal) {
                labelMensal.className = "cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-blue-600 bg-blue-50/50";
                labelUnico.className  = "cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-slate-200 hover:border-slate-300";
            } else {
                labelMensal.className = "cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-slate-200 hover:border-slate-300";
                labelUnico.className  = "cursor-pointer border-2 rounded-xl p-4 flex items-start space-x-3 transition-all border-emerald-600 bg-emerald-50/50";
            }

            // Atualiza dados no resumo lateral
            document.getElementById('summaryModalidadeText').textContent = isMensal ? 'Assinatura Mensal' : 'Pagamento Único (Anual)';
            document.getElementById('summaryParcelasText').textContent   = isMensal ? 'Apenas 1x (Parcela Única)' : 'Até 12x no Cartão';
            document.getElementById('summaryTotalText').textContent       = formatBrl(isMensal ? precoMensal : precoUnico);

            // Re-renderiza o Brick dinamicamente com as novas regras
            renderPaymentBrick(currentTipo);
        }

        document.addEventListener('DOMContentLoaded', function () {
            mp = new MercadoPago(publicKey, { locale: 'pt-BR' });
            bricksBuilder = mp.bricks();
            renderPaymentBrick(currentTipo);
        });
    </script>
    @endpush
</x-app-layout>
