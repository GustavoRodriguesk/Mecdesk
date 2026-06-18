<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    {{-- Cadastros --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 mb-3">Cadastros</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <a href="{{ route('clientes.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:border-gray-300 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                Clientes
            </p>
            <p class="text-3xl font-medium text-gray-800 dark:text-gray-100">{{ $clientes }}</p>
        </a>

        <a href="{{ route('veiculos.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:border-gray-300 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                Veículos
            </p>
            <p class="text-3xl font-medium text-gray-800 dark:text-gray-100">{{ $veiculos }}</p>
        </a>

        <a href="{{ route('ordens.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:border-gray-300 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                Ordens de serviço
            </p>
            <p class="text-3xl font-medium text-gray-800 dark:text-gray-100">{{ $ordens }}</p>
        </a>

        <a href="{{ route('servicos.index') }}"
           class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:border-gray-300 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
            <p class="flex items-center gap-1.5 text-xs text-gray-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>
                Serviços
            </p>
            <p class="text-3xl font-medium text-gray-800 dark:text-gray-100">{{ $servicos }}</p>
        </a>

    </div>

    {{-- Faturamento --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 mt-8 mb-3">Faturamento</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <div class="bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900 rounded-xl p-5">
            <p class="flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Faturamento total
            </p>
            <p class="text-2xl font-medium text-green-700 dark:text-green-300">
                R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900 rounded-xl p-5">
            <p class="flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 mb-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                Faturamento do mês
            </p>
            <p class="text-2xl font-medium text-green-700 dark:text-green-300">
                R$ {{ number_format($faturamentoMes, 2, ',', '.') }}
            </p>
        </div>

    </div>
    {{-- Gráficos --}}
<p class="text-xs font-medium uppercase tracking-widest text-gray-400 mt-8 mb-3">
    Indicadores
</p>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="font-semibold text-lg mb-4">
            Faturamento por Mês
        </h3>

        <div class="h-80">
            <canvas id="faturamentoChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="font-semibold text-lg mb-4">
            Ordens por Status
        </h3>

        <div class="h-80">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

</div>




    {{-- Status das OS --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 mt-8 mb-3">Status das ordens</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

        <div class="bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 rounded-xl p-5">
            <p class="text-xs text-blue-500 dark:text-blue-400 mb-1.5">Abertas</p>
            <p class="text-3xl font-medium text-blue-700 dark:text-blue-300">{{ $osAbertas }}</p>
        </div>

        <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900 rounded-xl p-5">
            <p class="text-xs text-amber-500 dark:text-amber-400 mb-1.5">Em andamento</p>
            <p class="text-3xl font-medium text-amber-700 dark:text-amber-300">{{ $osAndamento }}</p>
        </div>

        <div class="bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900 rounded-xl p-5">
            <p class="text-xs text-green-500 dark:text-green-400 mb-1.5">Concluídas</p>
            <p class="text-3xl font-medium text-green-700 dark:text-green-300">{{ $osConcluidas }}</p>
        </div>

    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mt-6">

    <h3 class="text-lg font-bold text-red-600 mb-4">
        ⚠️ Estoque Baixo
    </h3>

    @if($pecasBaixas->count())

        <ul class="space-y-2">

            @foreach($pecasBaixas as $peca)

                <li class="flex justify-between border-b pb-2">

                    <span>
                        {{ $peca->nome }}
                    </span>

                    <span class="font-bold text-red-500">
                        {{ $peca->estoque }} un.
                    </span>

                </li>

            @endforeach

        </ul>

    @else

        <p class="text-green-600">
            Nenhuma peça com estoque baixo.
        </p>

    @endif

</div>

    {{-- Ações rápidas --}}
    <p class="text-xs font-medium uppercase tracking-widest text-gray-400 mt-8 mb-3">Ações rápidas</p>

    <div class="flex flex-wrap gap-2">

        <a href="{{ route('clientes.create') }}"
           class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
            Novo cliente
        </a>

        <a href="{{ route('veiculos.create') }}"
           class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Novo veículo
        </a>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const faturamentoCtx =
        document.getElementById('faturamentoChart');

    if (faturamentoCtx) {

        new Chart(faturamentoCtx, {
            type: 'bar',
            data: {
                labels: @json($faturamentoChart->pluck('mes')),
                datasets: [{
                    label: 'Faturamento',
                    data: @json($faturamentoChart->pluck('total'))
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

    }

    const statusCtx =
        document.getElementById('statusChart');

    if (statusCtx) {

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($statusChart->pluck('status')),
                datasets: [{
                    data: @json($statusChart->pluck('total'))
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

    }

});




</script>

</x-app-layout>