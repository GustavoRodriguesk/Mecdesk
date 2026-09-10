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
        abort_if(!auth()->user()->canManageCompany(), 403);

        $empresa = auth()->user()->empresa;
        $maxUsuarios = $empresa?->plano?->max_usuarios ?? 1;

        if ($empresa && $empresa->users()->count() >= $maxUsuarios) {
            return redirect()
                ->route('empresa.edit')
                ->with('error', 'Limite de usuários do seu plano foi atingido.');
        }

        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->canManageCompany(), 403);

        $empresa = auth()->user()->empresa;
        $maxUsuarios = $empresa?->plano?->max_usuarios ?? 1;

        if ($empresa && $empresa->users()->count() >= $maxUsuarios) {
            return back()->with('error', 'Limite de usuários do seu plano foi atingido.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,gerente,funcionario'],
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
