<header class="topbar bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
    <span class="font-bold text-gray-800 text-lg">{{ $title ?? 'MECDESK' }}</span>

    <div class="hidden md:flex items-center bg-gray-100 rounded-md px-3 py-1.5 focus-within:ring-2 focus-within:ring-blue-500 focus-within:bg-white transition-all w-64">
        <i class="bi bi-search text-gray-400"></i>
        <input type="text" placeholder="Buscar..." class="bg-transparent border-none focus:ring-0 text-sm ml-2 w-full placeholder-gray-500 text-gray-800 p-0">
    </div>

    <div class="flex items-center gap-3">
        <button class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-colors relative" aria-label="Notificações">
            <i class="bi bi-bell text-xl"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
        <button class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-colors" aria-label="Ajuda">
            <i class="bi bi-question-circle text-xl"></i>
        </button>
        
        <div class="ml-2 border-l border-gray-200 pl-4">
            @include('partials._logout')
        </div>
    </div>
</header>