<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalhes do Veículo
        </h2>
    </x-slot>

    <style>
        .data-row { transition: background-color 0.12s ease; }
        .data-row:hover { background-color: #F0F4FA; }
        .btn-action { transition: opacity 0.12s ease; }
        .btn-action:hover { opacity: 0.85; }
    </style>

    <div class="py-8 px-8 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold uppercase select-none">
                        <i class="bi bi-car-front-fill"></i>
                    </span>
                    {{ $veiculo->marca }} {{ $veiculo->modelo }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Informações do veículo e histórico de serviços
                </p>
            </div>
            
            <a href="{{ route('veiculos.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-500"></i>
                    Dados do Veículo
                </h3>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                    <i class="bi bi-pencil"></i>
                    Editar
                </a>
                @endif
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Placa</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-200 text-gray-800 border border-gray-300 uppercase">
                        {{ $veiculo->placa }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ano</span>
                    <span class="text-sm font-medium text-gray-900">{{ $veiculo->ano ?: '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cor</span>
                    <span class="text-sm font-medium text-gray-900">{{ $veiculo->cor ?: '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">KM Atual</span>
                    <span class="text-sm font-medium text-gray-900">{{ $veiculo->quilometragem ? number_format($veiculo->quilometragem, 0, ',', '.') . ' km' : '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100 sm:col-span-2 md:col-span-4 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente Associado</span>
                        <span class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            <i class="bi bi-person text-gray-400"></i> {{ $veiculo->cliente->nome }}
                        </span>
                    </div>
                    <a href="{{ route('clientes.show', $veiculo->cliente->id) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">
                        Ver Cliente &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-tools text-gray-500"></i>
                    Histórico de Ordens de Serviço ({{ $veiculo->ordensServico->count() }})
                </h3>
                <a href="{{ route('ordens.create', ['veiculo' => $veiculo->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-plus-lg"></i>
                    Nova OS
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white border-b border-gray-100">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Nº OS</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Data</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Valor</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($veiculo->ordensServico as $ordem)
                            <tr class="data-row">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                        #{{ $ordem->numero_os }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 tabular-nums">
                                    {{ $ordem->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $ordem->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 tabular-nums">
                                    R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('ordens.show', $ordem->id) }}" class="btn-action inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 whitespace-nowrap">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                                        <i class="bi bi-tools text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Nenhuma ordem de serviço registrada para este veículo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-app-layout>