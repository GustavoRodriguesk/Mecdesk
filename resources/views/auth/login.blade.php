@extends('layouts.auth')

@section('title', 'Entrar — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Acessar o MecDesk</h1>
        <p class="auth-card-desc">Informe suas credenciais para acessar o sistema</p>
    </div>

    {{-- Alerta de Erro Geral --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Alerta de Mensagem de Sessão --}}
    @if (session('status'))
        <div class="auth-alert auth-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
        @csrf

        {{-- E-mail --}}
        <div class="auth-field">
            <label for="email" class="auth-label">E-mail</label>
            <div class="auth-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="exemplo@oficina.com.br"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>
            @error('email')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Senha --}}
        <div class="auth-field">
            <label for="password" class="auth-label">Senha</label>
            <div class="auth-input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Sua senha de acesso"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="auth-input-icon-right" id="togglePasswordBtn" aria-label="Mostrar ou ocultar senha">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lembrar de mim & Esqueci senha --}}
        <div class="auth-row-options">
            <label class="auth-checkbox-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        {{-- Botão de Login --}}
        <button type="submit" class="auth-btn-primary">
            <span>Entrar no sistema</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <div class="auth-card-footer">
        Ainda não tem conta no MecDesk?<br>
        <a href="{{ route('planos.index') }}" class="auth-link font-bold">Conheça nossos planos e assine</a>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }
    });
</script>
@endpush
