@extends('layouts.auth')

@section('title', 'Criar Conta — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Criar sua conta</h1>
        <p class="auth-card-desc">Preencha os dados abaixo para começar a usar o MecDesk</p>
    </div>

    {{-- Alerta de Erro Geral --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
        @csrf

        {{-- Nome da oficina --}}
        <div class="auth-field">
            <label for="empresa" class="auth-label">Nome da oficina</label>
            <div class="auth-input-wrapper">
                <input
                    type="text"
                    id="empresa"
                    name="empresa"
                    class="auth-input {{ $errors->has('empresa') ? 'is-invalid' : '' }}"
                    value="{{ old('empresa') }}"
                    placeholder="Ex: Mecânica São Paulo"
                    autofocus
                    required
                >
            </div>
            @error('empresa')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nome completo --}}
        <div class="auth-field">
            <label for="name" class="auth-label">Seu nome completo</label>
            <div class="auth-input-wrapper">
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="auth-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}"
                    placeholder="Ex: João da Silva"
                    autocomplete="name"
                    required
                >
            </div>
            @error('name')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

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
                    placeholder="seu@email.com"
                    autocomplete="email"
                    required
                >
            </div>
            @error('email')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Telefone --}}
        <div class="auth-field">
            <label for="telefone" class="auth-label">WhatsApp / Telefone</label>
            <div class="auth-input-wrapper">
                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    class="auth-input {{ $errors->has('telefone') ? 'is-invalid' : '' }}"
                    value="{{ old('telefone') }}"
                    placeholder="(00) 00000-0000"
                >
            </div>
            @error('telefone')
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
                    placeholder="Mínimo de 8 caracteres"
                    autocomplete="new-password"
                    required
                >
                <button type="button" class="auth-input-icon-right" data-target="password" aria-label="Mostrar ou ocultar senha">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar Senha --}}
        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirmar senha</label>
            <div class="auth-input-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="auth-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    placeholder="Repita a senha"
                    autocomplete="new-password"
                    required
                >
                <button type="button" class="auth-input-icon-right" data-target="password_confirmation" aria-label="Mostrar ou ocultar senha">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão de Criar Conta --}}
        <button type="submit" class="auth-btn-primary">
            <span>Criar conta e continuar</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <div class="auth-card-footer">
        Já tem uma conta?
        <a href="{{ route('login') }}" class="auth-link">Fazer login</a>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.auth-input-icon-right[data-target]').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = btn.querySelector('i');
                if (input && icon) {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                }
            });
        });
    });
</script>
@endpush
