<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Novo Veículo
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <form action="{{ route('veiculos.store') }}"
              method="POST"
              class="space-y-4">

            @csrf

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Cliente
                </label>

                <select name="cliente_id"
                        class="w-full border rounded-lg">

                    @foreach($clientes as $cliente)

                        <option value="{{ $cliente->id }}">

                            {{ $cliente->nome }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>
                <label class="block mb-1 font-medium mt-4   ">Marca</label>
                <select
                    name="marca"
                    class="w-full border rounded-lg p-3"
                >
                    <option value="">Selecionar Marca</option>

                    <option value="Chevrolet"
                        {{ request('marca') == 'aberta' ? 'selected' : '' }}>
                        Chevrolet
                    </option>

                    <option value="Fiat"
                        {{ request('marca') == 'concluida' ? 'selected' : '' }}>
                        Fiat
                    </option>

                    <option value="Volkswagen"
                        {{ request('marca') == 'Volkswagen' ? 'selected' : '' }}>
                        Volkswagen
                    </option>

                    <option value="Honda"
                        {{ request('marca') == 'Honda' ? 'selected' : '' }}>
                        Honda
                    </option>

                    <option value="Ford"
                        {{ request('marca') == 'Ford' ? 'selected' : '' }}>
                        Ford
                    </option>

                    <option value="Renault"
                        {{ request('marca') == 'Renault' ? 'selected' : '' }}>
                        Renault
                    </option>

                    <option value="Hyundai"
                        {{ request('marca') == 'Hyundai' ? 'selected' : '' }}>
                        Hyundai
                    </option>

                    <option value="Jeep"
                        {{ request('marca') == 'Jeep' ? 'selected' : '' }}>
                        Jeep
                    </option>

                    <option value="Citroen">
                        Citroen
                    </option>

                    <option value="Peugeot">
                        Peugeot
                    </option>

                    <option value="BMW"
                        {{ request('marca') == 'BMW' ? 'selected' : '' }}>
                        BMW
                    </option>

                    <option value="Mercedes-Benz"
                        {{ request('marca') == 'Mercedes-Benz' ? 'selected' : '' }}>
                        Mercedes-Benz
                    </option>

                    <option value="Audi"
                        {{ request('marca') == 'Audi' ? 'selected' : '' }}>
                        Audi
                    </option>

                    <option value="Volvo"
                        {{ request('marca') == 'Volvo' ? 'selected' : '' }}>
                        Volvo
                    </option>

                    <option value="Nissan"
                        {{ request('marca') == 'Nissan' ? 'selected' : '' }}>
                        Nissan
                    </option>

                    <option value="Toyota"
                        {{ request('marca') == 'Toyota' ? 'selected' : '' }}>
                        Toyota
                    </option>

                    <option value="Kia"
                        {{ request('marca') == 'Kia' ? 'selected' : '' }}>
                        Kia
                    </option>

                    <option value="Suzuki"
                        {{ request('marca') == 'Suzuki' ? 'selected' : '' }}>
                        Suzuki
                    </option>

                    <option value="Outros"
                        {{ request('marca') == 'Outros' ? 'selected' : '' }}>
                        Outros
                    </option>

                </select>

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Modelo
                </label>

                <input type="text"
                       name="modelo"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Ano
                </label>

                <input type="number"
                       name="ano"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Placa
                </label>

                <input type="text"
                       name="placa"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Cor
                </label>

                <input type="text"
                       name="cor"
                       class="w-full border rounded-lg p-3">

            </div>

            <div>

                <label class="block mb-1 font-medium mt-4">
                    Quilometragem
                </label>

                <input type="number"
                       name="quilometragem"
                       class="w-full border rounded-lg p-3">

            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded mt-4">

                Salvar

            </button>

        </form>

    </div>

</x-app-layout>