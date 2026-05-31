<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <a href="{{ route('clientes.index') }}"
           class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">

            <h3 class="text-gray-500">
                Clientes
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $clientes }}
            </p>

        </a>

        <a href="{{ route('veiculos.index') }}"
           class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">

            <h3 class="text-gray-500">
                Veículos
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $veiculos }}
            </p>

        </a>

    </div>

    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

        <h3 class="text-xl font-bold mb-4">
            Ações Rápidas
        </h3>

        <div class="flex gap-4">

            <a href="{{ route('clientes.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg">

                Novo Cliente

            </a>

            <a href="{{ route('veiculos.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg">

                Novo Veículo

            </a>

        </div>

    </div>

</x-app-layout>