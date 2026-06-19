<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalhes do Cliente
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold uppercase select-none">
                        {{ mb_substr($cliente->nome, 0, 1) }}
                    </span>
                    {{ $cliente->nome }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Informações detalhadas e veículos associados
                </p>
            </div>
            
            <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-person-lines-fill text-gray-500"></i>
                    Dados do Cliente
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Telefone</span>
                    <span class="text-sm font-medium text-gray-900">{{ $cliente->telefone ?: '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">E-mail</span>
                    <span class="text-sm font-medium text-gray-900">{{ $cliente->email ?: '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">CPF/CNPJ</span>
                    <span class="text-sm font-medium text-gray-900">{{ $cliente->cpf_cnpj ?: '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-100 md:col-span-3">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Endereço</span>
                    <span class="text-sm font-medium text-gray-900">{{ $cliente->endereco ?: '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-car-front-fill text-gray-500"></i>
                    Veículos ({{ $cliente->veiculos->count() }})
                </h3>
                <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-3 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-plus-lg"></i>
                    Novo Veículo
                </a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($cliente->veiculos as $veiculo)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0 inline-flex items-center bg-gray-100 justify-center h-8 w-8 rounded-full text-gray-500 shrink-0">
                                <i class="bi bi-car-front text-lg"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">
                                    {{ $veiculo->marca }} {{ $veiculo->modelo }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Placa: <span class="font-medium text-gray-700">{{ $veiculo->placa }}</span> <span class="mx-1">•</span> Ano: <span class="font-medium text-gray-700">{{ $veiculo->ano }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                            <i class="bi bi-car-front text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Nenhum veículo cadastrado.</p>
                        <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}" class="mt-2 inline-block text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline">
                            Cadastrar o primeiro veículo
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>