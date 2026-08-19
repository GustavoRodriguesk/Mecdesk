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
        $plano = Plano::where('slug', 'pro')->where('ativo', true)->first();
        $planos = Plano::where('ativo', true)->get();

        return view('auth.register', compact('plano', 'planos'));
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
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $plano = Plano::where('slug', 'pro')->where('ativo', true)->firstOrFail();
        $user = null;

        DB::transaction(function () use ($request, $plano, &$user) {
            $empresa = new Empresa([
                'nome_fantasia' => $request->empresa,
                'email'         => $request->email,
                'telefone'      => $request->telefone,
                'plano_id'      => $plano->id,
            ]);

            $empresa->ativo = false;
            $empresa->save();

            Assinatura::create([
                'empresa_id'       => $empresa->id,
                'plano_id'         => $plano->id,
                'metodo_pagamento' => 'cartao',
                'status'           => 'pending',
                'preco_contratado' => $plano->preco_mensal,
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

        return redirect()->route('checkout.show');
    }
}