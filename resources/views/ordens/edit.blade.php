<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Ordem de Serviço
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Editar Ordem #{{ $ordem->numero_os }}
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Atualize os dados e o status do atendimento
                </p>
            </div>
            
            <a href="{{ route('ordens.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('ordens.update', $ordem->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cliente
                        </label>
                        <select name="cliente_id" id="cliente_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $ordem->cliente_id == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Veículo
                        </label>
                        <select name="veiculo_id" id="veiculo_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            @foreach($veiculos as $veiculo)
                                <option value="{{ $veiculo->id }}" {{ $ordem->veiculo_id == $veiculo->id ? 'selected' : '' }}>
                                    {{ $veiculo->marca }} {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Problema Relatado
                        </label>
                        <textarea name="descricao_problema" rows="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>{{ old('descricao_problema', $ordem->descricao_problema) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            <option value="aberta" {{ $ordem->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                            <option value="em_andamento" {{ $ordem->status == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                            <option value="aguardando_aprovacao" {{ $ordem->status == 'aguardando_aprovacao' ? 'selected' : '' }}>Aguardando aprovação</option>
                            <option value="aprovada" {{ $ordem->status == 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                            <option value="reprovada" {{ $ordem->status == 'reprovada' ? 'selected' : '' }}>Reprovada</option>
                            <option value="concluida" {{ $ordem->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="entregue" {{ $ordem->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                            <option value="cancelada" {{ $ordem->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('ordens.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Atualizar Ordem
                    </button>
                </div>

            </form>

        </div>

    </div>

    <script>
    document.getElementById('cliente_id').addEventListener('change', function () {
        let clienteId = this.value;
        if (!clienteId) return;
        
        fetch(`/clientes/${clienteId}/veiculos`)
            .then(response => response.json())
            .then(data => {
                let select = document.getElementById('veiculo_id');
                select.innerHTML = '<option value="">Selecione um veículo...</option>';
                data.forEach(veiculo => {
                    select.innerHTML += `<option value="${veiculo.id}">${veiculo.marca} ${veiculo.modelo} - ${veiculo.placa}</option>`;
                });
            });
    });
    </script>

</x-app-layout>