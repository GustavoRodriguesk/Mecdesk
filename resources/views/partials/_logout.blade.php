<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="logout-btn">
        <i class="ti ti-logout"></i> Sair da conta
    </button>
</form>