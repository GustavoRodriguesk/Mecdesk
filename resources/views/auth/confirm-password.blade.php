@extends('layouts.auth')

@section('title', 'Confirmar Senha — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Confirmar Senha</h1>
        <p class="auth-card-desc">
            Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.
        </p>
    </div>

    {{-- Alerta de Erro Geral --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form" novalidate>
        @csrf

        {{-- Senha --}}
        <div class="auth-field">
            <label for="password" class="auth-label">Sua senha atual</label>
            <div class="auth-input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                    autofocus
                >
            </div>
            @error('password')
                <p class="auth-error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão de Confirmação --}}
        <button type="submit" class="auth-btn-primary">
            <span>Confirmar acesso</span>
            <i class="bi bi-shield-lock-fill"></i>
        </button>
    </form>

@endsection
