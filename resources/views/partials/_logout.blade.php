<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-800 transition-colors bg-red-50 hover:bg-red-100 px-3 py-2 rounded-md w-full text-left">
        <i class="bi bi-box-arrow-right"></i> Sair da conta
    </button>
</form>