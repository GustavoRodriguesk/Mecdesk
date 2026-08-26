<x-public-layout>
    <x-slot name="title">Planos & Preços — MecDesk</x-slot>

    <!-- ── 1. Hero Section ── -->
    <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        <!-- Background Glow -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-blue-400/10 blur-[120px] rounded-full pointer-events-none -z-10">
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-bold uppercase tracking-wider mb-6">
                <i class="bi bi-tools text-blue-600"></i> Gestão Completa para Oficinas Mecânicas
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-950 tracking-tight leading-[1.1] mb-6">
                A gestão da sua oficina,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">simples e
                    organizada.</span>
            </h1>

            <p class="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed mb-10">
                Chega de papelada e desorganização. Controle ordens de serviço, clientes, veículos, peças e equipe em um
                único sistema moderno.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('planos.contratar') }}"
                    class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <span>Começar agora</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <a href="#recursos"
                    class="w-full sm:w-auto px-7 py-4 text-base font-semibold text-slate-700 hover:text-slate-950 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-grid-fill text-slate-400 text-sm"></i>
                    Ver recursos
                </a>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-slate-500">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i> Sem fidelidade
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i> Ativação imediata
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i> Cancele quando quiser
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i> Pagamento seguro Mercado Pago
                </span>
            </div>
        </div>
    </section>

    <!-- ── 2. Seção de Recursos Reais do MecDesk ── -->
    <section id="recursos" class="py-20 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-md">O
                    que o sistema oferece</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight mt-3 mb-4">
                    Tudo o que sua oficina precisa no dia a dia
                </h2>
                <p class="text-slate-600 text-base leading-relaxed">
                    Ferramentas práticas e intuitivas, construídas especificamente para resolver os principais gargalos
                    operacionais da oficina mecânica.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Recurso 1: Ordens de Serviço -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Ordens de Serviço Completas</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Abra orçamentos, registre os problemas relatados, adicione serviços e peças e acompanhe o status
                        em tempo real (Orçamento, Em Andamento, Concluído).
                    </p>
                </div>

                <!-- Recurso 2: Aprovação por Link -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Aprovação de Orçamento por Link</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Envie um link seguro para o cliente visualizar os itens do orçamento e aprovar ou recusar
                        diretamente pelo celular, sem necessidade de login.
                    </p>
                </div>

                <!-- Recurso 3: Clientes & Veículos -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-600/10 text-indigo-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Clientes e Veículos</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Cadastre proprietários e veículos com placa, marca, modelo, ano e km. Acesse todo o histórico de
                        manutenções e OS de cada carro em segundos.
                    </p>
                </div>

                <!-- Recurso 4: Peças & Serviços -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-600/10 text-amber-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Catálogo de Peças e Serviços</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Organize sua tabela de preços de mão de obra e catálogo de peças com preço de custo, preço de
                        venda e controle da quantidade em estoque.
                    </p>
                </div>

                <!-- Recurso 5: Impressão e PDF -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-rose-600/10 text-rose-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Emissão de PDF da Ordem</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Gere documentos profissionais em PDF prontos para impressão ou compartilhamento, contendo a logo
                        da sua oficina, itens e valores detalhados.
                    </p>
                </div>

                <!-- Recurso 6: Dashboard & Indicadores -->
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-12 h-12 rounded-xl bg-cyan-600/10 text-cyan-600 flex items-center justify-center text-2xl mb-6 group-hover:bg-cyan-600 group-hover:text-white transition-all">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Dashboard da Oficina</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Acompanhe em tempo real a quantidade de ordens abertas, em andamento e concluídas, com métricas
                        operacionais para tomada de decisão.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 3. Seção do Plano MecDesk Pro ── -->
    <section id="plano" class="py-20 lg:py-28 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span
                    class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Plano
                    Disponível</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight mt-3 mb-4">
                    Assine o MecDesk Pro
                </h2>
                <p class="text-slate-600 text-base">
                    Gestão completa para oficinas mecânicas que querem organizar sua operação, clientes e ordens de
                    serviço em um único sistema.
                </p>
            </div>

            <!-- Card Comercial do MecDesk Pro -->
            <div
                class="bg-white rounded-3xl border-2 border-blue-600 shadow-xl shadow-blue-500/10 p-8 sm:p-12 relative overflow-hidden">
                <!-- Badge de destaque -->
                <div
                    class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-extrabold uppercase tracking-wider py-1.5 px-6 rounded-bl-2xl shadow-sm">
                    Acesso Completo
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Informações e Preço -->
                    <div class="lg:col-span-6 space-y-4">
                        <div class="inline-flex items-center gap-2 text-blue-600 font-bold text-sm">
                            <i class="bi bi-lightning-charge-fill"></i>
                            <span>Plano Profissional</span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-950">MecDesk Pro</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Tudo o que sua oficina precisa para gerenciar clientes, veículos e ordens de serviço de
                            ponta a ponta.
                        </p>

                        <div class="pt-2">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl sm:text-5xl font-black text-slate-950">R$ 99,90</span>
                                <span class="text-base font-semibold text-slate-500">/ mês</span>
                            </div>
                            <p class="text-xs font-medium text-slate-500 mt-1">
                                Cobrança recorrente mensal no cartão &middot; Cancele a qualquer momento
                            </p>
                        </div>

                        <div class="pt-4">
                            <a href="{{ route('planos.contratar') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 transition-all text-center">
                                <span>Começar agora</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Lista de Benefícios Reais -->
                    <div class="lg:col-span-6 bg-slate-50 rounded-2xl p-6 sm:p-8 border border-slate-200/80">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">O que está incluído
                            no Pro:</h4>
                        <ul class="space-y-3.5 text-sm text-slate-700">
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Ordens de Serviço completas</strong> com controle de status</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Aprovação online</strong> de orçamento por link seguro do cliente</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Cadastro de Clientes e Veículos</strong> com histórico</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Catálogo de Serviços e Peças</strong> com controle de estoque</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Geração e Impressão de PDFs</strong> com logo da sua oficina</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Dashboard Operacional</strong> com métricas da oficina</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0 mt-0.5"></i>
                                <span><strong>Acesso para sua equipe</strong> (múltiplos usuários)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 4. Seção FAQ ── -->
    <section id="faq" class="py-20 bg-slate-100/70 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span
                    class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-md">Tire
                    suas dúvidas</span>
                <h2 class="text-3xl font-extrabold text-slate-950 tracking-tight mt-3 mb-4">
                    Perguntas Frequentes
                </h2>
                <p class="text-slate-600 text-sm">
                    Tudo o que você precisa saber sobre o MecDesk e o processo de assinatura.
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <i class="bi bi-credit-card text-blue-600"></i>
                        Como funciona a assinatura e o pagamento?
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        A assinatura do MecDesk Pro custa R$ 99,90 por mês com cobrança recorrente no cartão de crédito,
                        processada com total segurança diretamente pelo Mercado Pago.
                    </p>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <i class="bi bi-x-circle text-blue-600"></i>
                        Existe contrato de fidelidade ou taxa de cancelamento?
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Não! Você pode cancelar sua assinatura a qualquer momento diretamente pela área "Minha
                        Assinatura" dentro do sistema, sem multas, taxas ou burocracia.
                    </p>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <i class="bi bi-lightning-charge text-blue-600"></i>
                        O acesso ao sistema é liberado na hora?
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Sim. Assim que a autorização do pagamento é confirmada pelo Mercado Pago, a sua oficina é
                        ativada imediatamente e você já pode começar a cadastrar clientes e emitir ordens de serviço.
                    </p>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <i class="bi bi-shield-check text-blue-600"></i>
                        Os dados dos meus clientes e veículos ficam seguros?
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Sim. O MecDesk opera com conexão criptografada SSL (HTTPS), isolamento de dados por empresa e
                        rotinas de backup para garantir a total privacidade e segurança das informações da sua oficina.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 5. CTA Final Banner ── -->
    <section class="py-16 bg-slate-950 text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                Pronto para transformar a gestão da sua oficina?
            </h2>
            <p class="text-slate-400 text-base max-w-xl mx-auto">
                Crie sua conta agora e comece a organizar suas ordens de serviço e clientes hoje mesmo com o MecDesk
                Pro.
            </p>
            <div class="pt-2">
                <a href="{{ route('planos.contratar') }}"
                    class="inline-flex items-center justify-center gap-2 px-9 py-4 text-base font-bold text-slate-950 bg-white hover:bg-slate-100 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <span>Começar agora por R$ 99,90/mês</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
