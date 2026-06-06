@extends('layouts.auth')

@section('title', 'Criar conta — MecDesk')

@section('content')

    <div class="auth-top-bar">
        <a href="{{ url('/') }}" class="auth-register-btn auth-login-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
            </svg>
            Já tenho conta
        </a>
    </div>

    <div class="auth-card">
        <h2 class="auth-card-title">Criar sua conta</h2>
        <p class="auth-card-subtitle">Preencha os dados abaixo para começar a usar o MecDesk</p>

        @if ($errors->any())
            <div class="auth-alert auth-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="auth-form-group">
                <label for="name" class="auth-label">Nome completo</label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="auth-input {{ $errors->has('name') ? 'has-error' : '' }}"
                        value="{{ old('name') }}"
                        placeholder="Seu nome"
                        autocomplete="name"
                        autofocus
                        required
                    >
                </div>
                @error('name')
                    <p class="auth-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="email" class="auth-label">E-mail</label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="auth-input {{ $errors->has('email') ? 'has-error' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="voce@exemplo.com.br"
                        autocomplete="email"
                        required
                    >
                </div>
                @error('email')
                    <p class="auth-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="password" class="auth-label">Senha</label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input {{ $errors->has('password') ? 'has-error' : '' }}"
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="auth-toggle-pwd" data-target="password" aria-label="Mostrar/ocultar senha">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="auth-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="password_confirmation" class="auth-label">Confirmar senha</label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
                        placeholder="Repita a senha"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="auth-toggle-pwd" data-target="password_confirmation" aria-label="Mostrar/ocultar senha">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="auth-input-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn-submit">
                Criar conta
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </form>

        <p class="auth-already">
            Já tem uma conta?
            <a href="{{ url('/') }}">Fazer login</a>
        </p>
    </div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.auth-toggle-pwd').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        });
    });
</script>
@endpush
