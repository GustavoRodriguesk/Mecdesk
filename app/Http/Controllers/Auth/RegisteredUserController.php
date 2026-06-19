<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
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
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'empresa' => ['required', 'string', 'max:255'],

            'name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'email', 'unique:users,email'],

            'telefone' => ['nullable', 'string', 'max:20'],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        DB::transaction(function () use ($request, &$user) {

            $empresa = Empresa::create([
    'nome_fantasia' => $request->empresa,
    'email' => $request->email,
    'telefone' => $request->telefone,

    'plano' => 'starter',

    'ativo' => true,
]);

            $user = User::create([
                'empresa_id' => $empresa->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}