<aside class="w-64 bg-gray-900 text-white h-screen shadow-lg flex flex-col">

    <div class="p-6 border-b border-gray-800 flex items-center shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center" title="MecDesk">
            <x-logo variant="white" class="h-7 w-auto max-w-[190px]" />
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        @if(auth()->check() && (auth()->user()->temPermissao('dashboard.operacional.visualizar') || auth()->user()->temPermissao('dashboard.financeiro.visualizar')))
            <a href="/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('dashboard') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-grid text-lg"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
        @endif

        @if(auth()->check() && (auth()->user()->temPermissao('clientes.visualizar') || auth()->user()->temPermissao('veiculos.visualizar')))
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Cadastros
                </p>
            </div>
        @endif

        @can('clientes.visualizar')
            <a href="/clientes"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('clientes*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-people text-lg"></i>
                <span class="font-medium text-sm">Clientes</span>
            </a>
        @endcan

        @can('veiculos.visualizar')
            <a href="{{ route('veiculos.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('veiculos*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-car-front text-lg"></i>
                <span class="font-medium text-sm">Veículos</span>
            </a>
        @endcan

        @if(auth()->check() && (auth()->user()->temPermissao('ordens.visualizar') || auth()->user()->temPermissao('servicos.visualizar') || auth()->user()->temPermissao('pecas.visualizar')))
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Operacional
                </p>
            </div>
        @endif

        @can('ordens.visualizar')
            <a href="{{ route('ordens.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('ordens*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-card-checklist text-lg"></i>
                <span class="font-medium text-sm">Ordens de Serviço</span>
            </a>
        @endcan

        @can('servicos.visualizar')
            <a href="{{ route('servicos.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('servicos*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-wrench text-lg"></i>
                <span class="font-medium text-sm">Serviços</span>
            </a>
        @endcan

        @can('pecas.visualizar')
            <a href="{{ route('pecas.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('pecas*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-box-seam text-lg"></i>
                <span class="font-medium text-sm">Peças</span>
            </a>
        @endcan

    </nav>

    @if (auth()->check() && auth()->user()->canManageCompany())
        <div class="p-4 border-t border-gray-800 shrink-0">
            <a href="{{ route('empresa.edit') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-gray-300 hover:text-white transition-colors {{ request()->is('empresa*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-gear text-lg"></i>
                <span class="font-medium text-sm">Minha Empresa</span>
            </a>
        </div>
    @endif
</aside>
