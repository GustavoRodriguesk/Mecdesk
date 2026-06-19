<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Veículos
        </h2>
    </x-slot>

    <div class="py-8 px-8 max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Novo Veículo
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Cadastre um novo veículo associado a um cliente
                </p>
            </div>
            
            <a href="{{ route('veiculos.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">

            <form action="{{ route('veiculos.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cliente
                        </label>
                        <select name="cliente_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Marca</label>
                        <select name="marca" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                            <option value="">Selecionar Marca</option>
                            @php
                                $marcas = ['Chevrolet', 'Fiat', 'Volkswagen', 'Honda', 'Ford', 'Renault', 'Hyundai', 'Jeep', 'Citroen', 'Peugeot', 'BMW', 'Mercedes-Benz', 'Audi', 'Volvo', 'Nissan', 'Toyota', 'Kia', 'Suzuki', 'Outros'];
                            @endphp
                            @foreach($marcas as $marca)
                                <option value="{{ $marca }}">{{ $marca }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Modelo
                        </label>
                        <input type="text" name="modelo" placeholder="Ex: Onix 1.0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150" required>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Ano
                        </label>
                        <input type="number" name="ano" placeholder="Ex: 2021" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Placa
                        </label>
                        <input type="text" name="placa" placeholder="ABC-1234" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150 uppercase" required>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cor
                        </label>
                        <input type="text" name="cor" placeholder="Ex: Prata" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Quilometragem
                        </label>
                        <input type="number" name="quilometragem" placeholder="Ex: 50000" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-150">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-6">
                    <a href="{{ route('veiculos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bi bi-check2"></i>
                        Salvar Veículo
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>