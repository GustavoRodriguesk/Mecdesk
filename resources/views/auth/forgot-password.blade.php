@extends('layouts.auth')

@section('title', 'Recuperar Senha — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Recuperar Senha</h1>
        <p class="auth-card-desc">
            Informe seu e-mail cadastrado e enviaremos um link seguro para você redefinir sua senha.
        </p>
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

    <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
        @csrf

        {{-- E-mail --}}
        <div class="auth-field">
            <label for="email" class="auth-label">E-mail cadastrado</label>
            <div class="auth-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>
            @error('email')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão de Enviar Link --}}
        <button type="submit" class="auth-btn-primary">
            <span>Enviar link de recuperação</span>
            <i class="bi bi-send-fill"></i>
        </button>
    </form>

    <div class="auth-card-footer">
        Lembrou da senha?
        <a href="{{ route('login') }}" class="auth-link">Voltar para o login</a>
    </div>

@endsection
