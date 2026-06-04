<aside class="w-64 bg-gray-800 text-white min-h-screen">

    <div class="p-6 border-b border-gray-700">

        <h1 class="text-2xl font-bold">
            MECDESK
        </h1>

        <p class="text-sm text-gray-400 mt-1">
            Gestão de Oficina
        </p>

    </div>

    <nav class="p-4 space-y-2">

        <a href="/dashboard"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">

            Dashboard

        </a>

        <a href="/clientes"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">

            Clientes

        </a>

        <a href="{{ route('veiculos.index') }}"
   class="block px-4 py-2 hover:bg-gray-700 rounded">
    Veículos
</a>

        <a href="{{ route('ordens.index') }}"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">

            Ordens de Serviço

        </a>
        <a href="{{ route('servicos.index') }}"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">

            Serviços
        </a>
            <a href="{{ route('pecas.index') }}"
            class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">
    
                Peças   
            </a>
    

        <a href="#"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">

            Orçamentos

        </a>

    </nav>

</aside>