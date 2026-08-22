<x-public-layout>
    <x-slot name="title">Checkout de Assinatura — MecDesk Pro</x-slot>

    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Voltar -->
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('planos.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>
                Voltar aos planos
            </a>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1 rounded-full">
                <i class="bi bi-shield-lock-fill text-emerald-600"></i>
                Ambiente 100% Seguro
            </div>
        </div>

        <div id="errorMessageContainer" class="hidden bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl mb-8 text-sm flex items-center justify-between shadow-sm">
            <span id="errorMessageText"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- ── Resumo do Pedido (Zero-Trust) ── -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Resumo da Assinatura</span>
                    <h2 class="text-2xl font-black text-slate-950 mt-3 mb-1">MecDesk {{ $plano->nome }}</h2>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $plano->descricao }}</p>
                </div>

                <div class="border-t border-slate-100 pt-5 space-y-3.5">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Empresa / Oficina</span>
                        <span class="font-bold text-slate-900 truncate max-w-[180px]">{{ auth()->user()->empresa?->nome_fantasia ?? 'Sua Oficina' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Cobrança</span>
                        <span class="font-bold text-slate-900">Recorrência Mensal</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Forma de Pagamento</span>
                        <span class="font-bold text-slate-900">Cartão de Crédito</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-emerald-600">
                        <span class="flex items-center gap-1.5">
                            <i class="bi bi-shield-check"></i>
                            Segurança SSL
                        </span>
                        <span class="font-semibold text-xs bg-emerald-50 px-2 py-0.5 rounded">Criptografado</span>
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

                <div class="bg-slate-50 rounded-2xl p-4 text-xs text-slate-600 border border-slate-100 space-y-1.5">
                    <p class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="bi bi-arrow-repeat text-blue-600"></i>
                        Cobrança Automática Recorrente
                    </p>
                    <p class="leading-relaxed">
                        A assinatura é renovada automaticamente a cada mês. Você pode cancelar a qualquer momento sem taxas ou fidelidade.
                    </p>
                </div>
            </div>

            <!-- ── Formulário Mercado Pago Card Brick ── -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm min-h-[480px]">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Dados do Pagamento</h3>
                    <p class="text-xs text-slate-500">Informe os dados do seu cartão de crédito para ativar o acesso</p>
                </div>

                <div id="brickLoading" class="flex flex-col items-center justify-center py-16 text-slate-400">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">Carregando formulário seguro do Mercado Pago...</span>
                </div>

                <div id="cardPaymentBrick_container"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const publicKey = '{{ $publicKey }}';
        const amount    = {{ $amount }};
        
        function generateUUID() {
            if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
                return crypto.randomUUID();
            }
            return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                (c ^ (typeof crypto !== 'undefined' && crypto.getRandomValues ? crypto.getRandomValues(new Uint8Array(1))[0] : Math.floor(Math.random() * 16)) & (15 >> (c / 4))).toString(16)
            );
        }
        
        const idempotencyKey = generateUUID();

        let mp = null;
        let bricksBuilder = null;

        function showCheckoutError(msg) {
            const container = document.getElementById('errorMessageContainer');
            const text = document.getElementById('errorMessageText');
            if (container && text) {
                text.textContent = msg;
                container.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        async function renderCardPaymentBrick() {
            const container = document.getElementById('cardPaymentBrick_container');
            const loadingSpinner = document.getElementById('brickLoading');

            if (loadingSpinner) loadingSpinner.style.display = 'flex';

            const settings = {
                initialization: {
                    amount: amount,
                    payer: {
                        email: '{{ auth()->user()->email }}',
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
                    },
                    onSubmit: (cardFormData) => {
                        document.getElementById('errorMessageContainer').classList.add('hidden');

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
                                    const msg = data.message || data.error || 'Não foi possível processar a assinatura com este cartão.';
                                    showCheckoutError(msg);
                                    reject(msg);
                                } else {
                                    resolve();
                                    if (data.status === 'authorized') {
                                        window.location.href = "{{ route('dashboard') }}";
                                    } else {
                                        window.location.href = "{{ route('assinatura.pendente') }}";
                                    }
                                }
                            })
                            .catch((error) => {
                                const msg = typeof error === 'string' ? error : (error.message || 'Erro de conexão.');
                                showCheckoutError(msg);
                                reject(error);
                            });
                        });
                    },
                    onError: (error) => {
                        console.error("Erro no Card Payment Brick:", error);
                        showCheckoutError("Ocorreu um erro no formulário de pagamento. Por favor, recarregue a página.");
                    },
                },
            };

            window.cardPaymentBrickController = await bricksBuilder.create(
                "cardPayment",
                "cardPaymentBrick_container",
                settings
            );
        }

        document.addEventListener('DOMContentLoaded', function () {
            mp = new MercadoPago(publicKey, { locale: 'pt-BR' });
            bricksBuilder = mp.bricks();
            renderCardPaymentBrick();
        });
    </script>
    @endpush
</x-public-layout>
