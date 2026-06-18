<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,funcionario'],
        ]);

        User::create([
            'empresa_id' => auth()->user()->empresa_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('empresa.edit')->with('success', 'Funcionário cadastrado com sucesso!');
    }
}
