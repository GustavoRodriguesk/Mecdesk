<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('clientes.index') }}"
                class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-people"></i>
                <span>Clientes</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5 truncate max-w-xs sm:max-w-md">
                <i class="bi bi-person-vcard text-blue-600"></i>
                {{ $cliente->nome }}
            </span>
        </div>
    </x-slot>

    <div class="w-full space-y-6">

        {{-- Cabeçalho da Página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span
                        class="shrink-0 inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold uppercase select-none">
                        {{ mb_substr($cliente->nome, 0, 1) }}
                    </span>
                    <span>{{ $cliente->nome }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Consulta de informações cadastrais, veículos vinculados e histórico de ordens
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if ($cliente->possui_whatsapp && $cliente->telefone)
                    <a href="{{ $cliente->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        title="Conversar no WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                @endif

                <a href="{{ route('clientes.edit', $cliente->id) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="bi bi-pencil-square"></i>
                    <span>Editar Cliente</span>
                </a>

                <a href="{{ route('clientes.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <i class="bi bi-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- Card 1: Dados do Cliente --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-person-lines-fill text-gray-500"></i>
                    Informações Pessoais e Contato
                </h3>
                <a href="{{ route('clientes.edit', $cliente->id) }}"
                    class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                    <i class="bi bi-pencil"></i>
                    Editar Dados
                </a>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Nome --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nome
                        Completo</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $cliente->nome }}</span>
                </div>

                {{-- CPF / CNPJ --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">CPF /
                        CNPJ</span>
                    <span class="text-sm font-medium text-gray-900 tabular-nums">
                        {{ $cliente->cpf_cnpj_formatado ?: ($cliente->cpf_cnpj ?: '-') }}
                    </span>
                </div>

                {{-- Cliente Desde --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cliente
                        Desde</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '-' }}
                    </span>
                </div>

                {{-- Telefone Principal --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Telefone
                            Principal</span>
                        <span class="text-sm font-medium text-gray-900 tabular-nums">
                            {{ $cliente->telefone_formatado ?: ($cliente->telefone ?: '-') }}
                        </span>
                    </div>
                    @if ($cliente->possui_whatsapp && $cliente->telefone)
                        <a href="{{ $cliente->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-100/80 border border-emerald-200 rounded-md hover:bg-emerald-200 transition-colors"
                            title="Conversar no WhatsApp">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    @endif
                </div>

                {{-- Outros Telefones --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Outros
                        Telefones</span>
                    <div class="text-sm font-medium text-gray-900 tabular-nums">
                        @if ($cliente->telefone_2 || $cliente->telefone_3)
                            <div>{{ $cliente->telefone_2_formatado ?: $cliente->telefone_2 }}</div>
                            @if ($cliente->telefone_3)
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $cliente->telefone_3_formatado ?: $cliente->telefone_3 }}</div>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                {{-- E-mail --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">E-mail</span>
                    @if ($cliente->email)
                        <a href="mailto:{{ $cliente->email }}"
                            class="text-sm font-medium text-blue-600 hover:underline truncate block">
                            {{ $cliente->email }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>

                {{-- Instagram --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <span
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Instagram</span>
                    <span class="text-sm font-medium text-gray-900">
                        @if ($cliente->instagram)
                            <a href="https://instagram.com/{{ ltrim($cliente->instagram, '@') }}" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 text-pink-600 hover:text-pink-700 font-medium hover:underline">
                                <i class="bi bi-instagram"></i>
                                {{ str_starts_with($cliente->instagram, '@') ? $cliente->instagram : '@' . $cliente->instagram }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </span>
                </div>

                {{-- Endereço Completo --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 md:col-span-2">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Endereço
                        Completo</span>
                    <div class="text-sm font-medium text-gray-900 flex items-start gap-2">
                        <i class="bi bi-geo-alt text-gray-400 mt-0.5 shrink-0"></i>
                        <span>{{ $cliente->endereco_completo ?: ($cliente->endereco ?: 'Endereço não informado') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Veículos Associados --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-car-front-fill text-gray-500"></i>
                    Veículos Associados ({{ $cliente->veiculos->count() }})
                </h3>
                <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="bi bi-car-front"></i>
                    <span>Novo Veículo</span>
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($cliente->veiculos as $veiculo)
                    <div
                        class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="shrink-0 inline-flex items-center bg-gray-100 justify-center h-10 w-10 rounded-full text-gray-500">
                                <i class="bi bi-car-front text-lg"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    {{ $veiculo->marca }} {{ $veiculo->modelo }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200 uppercase font-mono">
                                        {{ $veiculo->placa }}
                                    </span>
                                    <span>•</span>
                                    <span>Ano: <strong class="text-gray-700">{{ $veiculo->ano ?: '-' }}</strong></span>
                                    @if ($veiculo->quilometragem)
                                        <span>•</span>
                                        <span>KM: <strong
                                                class="text-gray-700">{{ number_format($veiculo->quilometragem, 0, ',', '.') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('veiculos.show', $veiculo->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                <i class="bi bi-eye"></i> Detalhes
                            </a>
                            <a href="{{ route('veiculos.edit', $veiculo->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3 text-gray-400">
                            <i class="bi bi-car-front text-2xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Nenhum veículo cadastrado para este cliente.</p>
                        <a href="{{ route('veiculos.create', ['cliente' => $cliente->id]) }}"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                            <i class="bi bi-plus-lg"></i>
                            Cadastrar o primeiro veículo
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Card 3: Histórico de Ordens de Serviço --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-tools text-gray-500"></i>
                    Histórico de Ordens de Serviço ({{ $cliente->ordensServico->count() }})
                </h3>
                @if ($cliente->veiculos->isNotEmpty())
                    <a href="{{ route('ordens.create', ['cliente' => $cliente->id]) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="bi bi-plus-lg"></i>
                        <span>Nova OS</span>
                    </a>
                @endif
            </div>

            @if ($cliente->ordensServico->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="bg-gray-50/50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                                <th class="text-left px-6 py-3.5">Número OS</th>
                                <th class="text-left px-6 py-3.5">Veículo</th>
                                <th class="text-left px-6 py-3.5">Status</th>
                                <th class="text-left px-6 py-3.5">Data Entrada</th>
                                <th class="text-left px-6 py-3.5">Valor Total</th>
                                <th class="text-right px-6 py-3.5">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($cliente->ordensServico as $ordem)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                            #{{ $ordem->numero_os }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        @if ($ordem->veiculo)
                                            <span class="font-medium text-gray-800">{{ $ordem->veiculo->marca }}
                                                {{ $ordem->veiculo->modelo }}</span>
                                            <span
                                                class="text-xs text-gray-400 font-mono ml-1">({{ $ordem->veiculo->placa }})</span>
                                        @else
                                            <span class="text-gray-400 italic">Não informado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ordem->status_color }}">
                                            {{ $ordem->status_formatado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 tabular-nums">
                                        {{ $ordem->data_entrada ? $ordem->data_entrada->format('d/m/Y') : $ordem->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 tabular-nums">
                                        R$ {{ number_format($ordem->valor_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('ordens.show', $ordem->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors"
                                            title="Ver Detalhes da OS">
                                            <i class="bi bi-eye"></i> Ver OS
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3 text-gray-400">
                        <i class="bi bi-file-earmark-text text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Nenhuma ordem de serviço registrada para este cliente.
                    </p>
                    @if ($cliente->veiculos->isNotEmpty())
                        <a href="{{ route('ordens.create', ['cliente' => $cliente->id]) }}"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                            <i class="bi bi-plus-lg"></i>
                            Criar primeira Ordem de Serviço
                        </a>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Cadastre um veículo primeiro para criar uma Ordem de
                            Serviço.</p>
                    @endif
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
