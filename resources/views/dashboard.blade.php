<?php

use App\Models\Cliente;
use App\Models\Veiculo;


$clientes = Cliente::count();
$veiculos = Veiculo::count();

?>
<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

            Dashboard

        </h2>

    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

            <a href="{{ route('clientes.index') }}">
    Clientes
</a>

            <p class="text-3xl mt-4 font-bold">
                {{ $clientes }}
            </p>

        </div>

    </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

            <a href="{{ route('veiculos.index') }}">
    Veiculos
</a>

            <p class="text-3xl mt-4 font-bold">
                {{ $veiculos }}
            </p>

        </div>

    </div>

</x-app-layout>