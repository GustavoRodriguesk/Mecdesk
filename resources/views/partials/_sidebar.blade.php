<aside class="w-64 bg-gray-900 text-white min-h-screen shadow-lg flex flex-col">

    <div class="p-6 border-b border-gray-800 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm">
            M
        </div>
        <div>
            <h1 class="text-lg font-bold tracking-wider text-white">
                MECDESK
            </h1>
            <p class="text-xs text-gray-400 font-medium tracking-wide">
                Gestão de Oficina
            </p>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('dashboard') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-grid text-lg"></i>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        <div class="pt-4 pb-1">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Cadastros
            </p>
        </div>

        <a href="/clientes" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('clientes*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-people text-lg"></i>
            <span class="font-medium text-sm">Clientes</span>
        </a>

        <a href="{{ route('veiculos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('veiculos*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-car-front text-lg"></i>
            <span class="font-medium text-sm">Veículos</span>
        </a>

        <div class="pt-4 pb-1">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Operacional
            </p>
        </div>

        <a href="{{ route('ordens.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('ordens*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-card-checklist text-lg"></i>
            <span class="font-medium text-sm">Ordens de Serviço</span>
        </a>

        <a href="{{ route('servicos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('servicos*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-wrench text-lg"></i>
            <span class="font-medium text-sm">Serviços</span>
        </a>

        <a href="{{ route('pecas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('pecas*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-box-seam text-lg"></i>
            <span class="font-medium text-sm">Peças</span>
        </a>

    </nav>
    
    <div class="p-4 border-t border-gray-800">
        <a href="{{ route('empresa.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('empresa*') ? 'bg-gray-800 text-white' : '' }}">
            <i class="bi bi-gear text-lg"></i>
            <span class="font-medium text-sm">Minha Empresa</span>
        </a>
    </div>

</aside>