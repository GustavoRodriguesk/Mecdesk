@extends('layouts.auth')

@section('title', 'Entrar — MecDesk')

@section('content')

    @if (Route::has('register'))
        <div class="auth-top-bar">
            <a href="{{ route('register') }}" class="auth-register-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                </svg>
                Criar conta
            </a>
        </div>
    @endif

    <div class="auth-card">
        <h2 class="auth-card-title">Entrar na conta</h2>
        <p class="auth-card-subtitle">Informe suas credenciais para continuar</p>

        @if ($errors->any())
            <div class="auth-alert auth-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="auth-alert auth-alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

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
                        autofocus
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
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="auth-toggle-pwd" data-target="password" aria-label="Mostrar/ocultar senha">
                        <svg class="eye-open" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="auth-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-options">
                <label class="auth-remember">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Lembrar de mim
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-forgot">Esqueci a senha</a>
                @endif
            </div>

            <button type="submit" class="auth-btn-submit">
                Entrar
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </form>

        @if (Route::has('register'))
            <p class="auth-already">
                Não tem uma conta?
                <a href="{{ route('register') }}">Criar conta gratuitamente</a>
            </p>
        @endif
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
