<x-app-layout>
    <x-slot name="header">Pagamento PIX — Plano {{ $plano->nome }}</x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
            <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full mb-4">
                Aguardando Pagamento PIX
            </span>

            <h2 class="text-2xl font-bold text-slate-800 mb-2">Escaneie o QR Code para Ativar</h2>
            <p class="text-slate-600 text-sm mb-6">
                Abra o aplicativo do seu banco, escolha a opção PIX e escaneie o código abaixo ou utilize o Copia e Cola.
            </p>

            <!-- Exibição do QR Code Base64 -->
            @if(!empty($qr_code_base64))
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 w-64 h-64 mx-auto mb-6 flex items-center justify-center">
                    <img src="data:image/jpeg;base64,{{ $qr_code_base64 }}" alt="QR Code PIX Mercado Pago" class="w-full h-full object-contain rounded-xl">
                </div>
            @endif

            <!-- Valor -->
            <div class="mb-6">
                <span class="text-xs uppercase font-bold text-slate-400">Valor a Pagar</span>
                <div class="text-3xl font-black text-slate-900">R$ {{ number_format($valor, 2, ',', '.') }}</div>
            </div>

            <!-- PIX Copia e Cola -->
            @if(!empty($qr_code))
                <div class="mb-6 text-left">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">PIX Copia e Cola</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="pixCode" readonly value="{{ $qr_code }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-mono text-slate-700 select-all focus:outline-none">
                        <button type="button" onclick="copyPixCode()" class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all whitespace-nowrap">
                            Copiar Código
                        </button>
                    </div>
                    <span id="copyNotice" class="text-xs text-emerald-600 font-semibold mt-1 hidden block">Código PIX copiado para a área de transferência!</span>
                </div>
            @endif

            <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-4 rounded-xl mb-6 text-left">
                <strong>Importante:</strong> Assim que a transferência for concluída, o Mercado Pago notificará nosso servidor e seu acesso será liberado automaticamente em poucos segundos.
            </div>

            <a href="{{ route('assinatura.pendente') }}" class="inline-block px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition-all">
                Verificar Status da Assinatura
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyPixCode() {
            const input = document.getElementById('pixCode');
            input.select();
            document.execCommand('copy');
            const notice = document.getElementById('copyNotice');
            notice.classList.remove('hidden');
            setTimeout(() => notice.classList.add('hidden'), 4000);
        }

        // Auto-check status a cada 5 segundos
        setInterval(() => {
            fetch('{{ route("assinatura.pendente") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => {
                    if (res.redirected) {
                        window.location.href = res.url;
                    }
                });
        }, 5000);
    </script>
    @endpush
</x-app-layout>
