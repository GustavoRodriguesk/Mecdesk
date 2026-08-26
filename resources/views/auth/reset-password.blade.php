@extends('layouts.auth')

@section('title', 'Redefinir Senha — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Redefinir Senha</h1>
        <p class="auth-card-desc">Crie uma nova senha segura para acessar sua conta</p>
    </div>

    {{-- Alerta de Erro Geral --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="auth-form" novalidate>
        @csrf

        {{-- Token de Redefinição --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- E-mail --}}
        <div class="auth-field">
            <label for="email" class="auth-label">E-mail</label>
            <div class="auth-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $request->email) }}"
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

        {{-- Nova Senha --}}
        <div class="auth-field">
            <label for="password" class="auth-label">Nova Senha</label>
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
            </div>
            @error('password')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmação de Senha --}}
        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirmar Nova Senha</label>
            <div class="auth-input-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="auth-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    placeholder="Repita a nova senha"
                    autocomplete="new-password"
                    required
                >
            </div>
            @error('password_confirmation')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão de Redefinir --}}
        <button type="submit" class="auth-btn-primary">
            <span>Redefinir senha</span>
            <i class="bi bi-shield-check"></i>
        </button>
    </form>

    <div class="auth-card-footer">
        <a href="{{ route('login') }}" class="auth-link">Voltar para o login</a>
    </div>

@endsection
