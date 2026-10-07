<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('veiculos.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-car-front"></i>
                <span>Veículos</span>
            </a>
            <i class="bi bi-chevron-right text-xs text-gray-400"></i>
            <span class="font-semibold text-gray-900 text-base flex items-center gap-1.5">
                <i class="bi bi-pencil-square text-blue-600"></i>
                Editar Veículo: <span class="uppercase text-blue-700">{{ $veiculo->placa }}</span>
            </span>
        </div>
    </x-slot>

    <div class="w-full">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    <span
                        class="shrink-0 inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold select-none">
                        <i class="bi bi-car-front-fill"></i>
                    </span>
                    Editar Veículo
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Atualize os dados cadastrais do veículo <span class="font-semibold text-gray-700 uppercase">{{ $veiculo->placa }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('veiculos.show', $veiculo->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                    <i class="bi bi-eye"></i>
                    Ver Detalhes / Histórico
                </a>

                <a href="{{ route('veiculos.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">

            <form action="{{ route('veiculos.update', $veiculo->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cliente <span class="text-red-500 font-bold">*</span>
                        </label>
                        <select name="cliente_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ old('cliente_id', $veiculo->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Marca <span class="text-red-500 font-bold">*</span>
                        </label>
                        <select name="marca"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                            @php
                                $marcas = [
                                    'Chevrolet',
                                    'Fiat',
                                    'Volkswagen',
                                    'Honda',
                                    'Ford',
                                    'Renault',
                                    'Hyundai',
                                    'Jeep',
                                    'Citroen',
                                    'Peugeot',
                                    'BMW',
                                    'Mercedes-Benz',
                                    'Audi',
                                    'Volvo',
                                    'Nissan',
                                    'Toyota',
                                    'Kia',
                                    'Suzuki',
                                    'Outros',
                                ];
                                $selectedMarca = old('marca', $veiculo->marca);
                            @endphp
                            @if (!in_array($selectedMarca, $marcas) && !empty($selectedMarca))
                                <option value="{{ $selectedMarca }}" selected>{{ $selectedMarca }}</option>
                            @endif
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca }}" {{ $selectedMarca == $marca ? 'selected' : '' }}>
                                    {{ $marca }}
                                </option>
                            @endforeach
                        </select>
                        @error('marca')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Modelo <span class="text-red-500 font-bold">*</span>
                        </label>
                        <input type="text" name="modelo" value="{{ old('modelo', $veiculo->modelo) }}"
                            placeholder="Ex: Onix, Gol, Civic..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150"
                            required>
                        @error('modelo')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Ano
                        </label>
                        <input type="number" name="ano" value="{{ old('ano', $veiculo->ano) }}"
                            placeholder="Ex: 2020" min="1900" max="{{ date('Y') + 1 }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('ano')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Placa <span class="text-red-500 font-bold">*</span>
                        </label>
                        <input type="text" name="placa" value="{{ old('placa', $veiculo->placa) }}"
                            placeholder="ABC1234 ou ABC1D23" maxlength="7"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150 uppercase"
                            required>
                        @error('placa')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cor
                        </label>
                        <input type="text" name="cor" value="{{ old('cor', $veiculo->cor) }}"
                            placeholder="Ex: Prata, Preto, Branco..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('cor')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Quilometragem (km)
                        </label>
                        <input type="number" name="quilometragem"
                            value="{{ old('quilometragem', $veiculo->quilometragem) }}"
                            placeholder="Ex: 50000" min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                        @error('quilometragem')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('veiculos.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors cursor-pointer shadow-xs">
                        <i class="bi bi-check2"></i>
                        Atualizar Veículo
                    </button>
                </div>

            </form>

        </div>

    </div>

    <script>
        document.querySelector('input[name="placa"]')?.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (value.length > 7) {
                value = value.substring(0, 7);
            }
            e.target.value = value;
        });
    </script>

</x-app-layout>
