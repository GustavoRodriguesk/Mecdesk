<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\Assinatura;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $planoSelecionado = $request->query('plano', 'free');
        $planos = Plano::where('ativo', true)->get();

        return view('auth.register', compact('planoSelecionado', 'planos'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'empresa'  => ['required', 'string', 'max:255'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'plano'    => ['nullable', 'string', 'exists:planos,slug'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $planoSlug = $request->input('plano', 'free');
        $plano = Plano::where('slug', $planoSlug)->where('ativo', true)->first();

        if (!$plano) {
            $plano = Plano::where('slug', 'free')->firstOrFail();
        }

        $user = null;

        DB::transaction(function () use ($request, $plano, &$user) {
            $isFree = $plano->slug === 'free';

            $empresa = new Empresa([
                'nome_fantasia' => $request->empresa,
                'email'         => $request->email,
                'telefone'      => $request->telefone,
                'plano_id'      => $plano->id,
            ]);

            // Regra de Segurança: Apenas plano 'free' começa ativo. Planos pagos exigem webhook do MP.
            $empresa->ativo = $isFree;
            $empresa->save();

            Assinatura::create([
                'empresa_id'       => $empresa->id,
                'plano_id'         => $plano->id,
                'metodo_pagamento' => $isFree ? 'free' : 'cartao',
                'status'           => $isFree ? 'authorized' : 'pending',
                'preco_contratado' => $plano->preco_mensal,
                'data_inicio'      => $isFree ? now() : null,
                'valido_ate'       => $isFree ? null : null,
            ]);

            $user = User::create([
                'empresa_id' => $empresa->id,
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'admin',
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        if ($user->empresa->isAtiva()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('assinatura.pendente');
    }
}