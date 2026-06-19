<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Empresa;

class UsuarioController extends Controller
{
    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        return view('usuarios.create');
    }

    public function store(Request $request)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:admin,funcionario'],
    ]);

    User::create([
        'empresa_id' => auth()->user()->empresa_id,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    return redirect()
        ->route('empresa.edit')
        ->with('success', 'Funcionário cadastrado com sucesso!');
}
}
