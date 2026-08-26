@extends('layouts.auth')

@section('title', 'Verificar E-mail — MecDesk')

@section('content')

    <div class="auth-card-head">
        <h1 class="auth-card-title">Verifique seu e-mail</h1>
        <p class="auth-card-desc">
            Obrigado por se cadastrar! Antes de começar, por favor confirme seu endereço de e-mail clicando no link que acabamos de enviar.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert auth-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>Um novo link de verificação foi enviado para o seu e-mail cadastrado.</span>
        </div>
    @endif

    <div class="auth-form">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn-primary">
                <span>Reenviar e-mail de verificação</span>
                <i class="bi bi-send-fill"></i>
            </button>
        </form>

        <div class="auth-card-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-link" style="background:none; border:none; cursor:pointer;">
                    Sair da conta
                </button>
            </form>
        </div>
    </div>

@endsection
