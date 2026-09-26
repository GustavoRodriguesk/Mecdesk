<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUsuarioRequest;
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

    public function store(StoreUsuarioRequest $request)
    {
        $empresa = auth()->user()->empresa;
        $maxUsuarios = $empresa?->plano?->max_usuarios ?? 1;

        if ($empresa && $empresa->users()->count() >= $maxUsuarios) {
            return back()->with('error', 'Limite de usuários do seu plano foi atingido.');
        }

        $validated = $request->validated();

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

    public function toggleStatus(User $usuario)
    {
        abort_if(! auth()->user()->canManageCompany(), 403, 'Ação restrita a administradores.');
        abort_if($usuario->empresa_id !== auth()->user()->empresa_id, 403, 'Usuário não pertence a esta empresa.');
        abort_if($usuario->id === auth()->id(), 422, 'Você não pode alterar o status da sua própria conta.');

        $usuario->ativo = ! $usuario->isAtivo();
        $usuario->save();

        $statusMsg = $usuario->isAtivo() ? 'ativado' : 'desativado';

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Usuário {$usuario->name} foi {$statusMsg} com sucesso!");
    }

    public function destroy(User $usuario)
    {
        abort_if(! auth()->user()->canManageCompany(), 403, 'Ação restrita a administradores.');
        abort_if($usuario->empresa_id !== auth()->user()->empresa_id, 403, 'Usuário não pertence a esta empresa.');
        abort_if($usuario->id === auth()->id(), 422, 'Você não pode excluir sua própria conta.');

        $nome = $usuario->name;
        $usuario->delete();

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Usuário {$nome} foi removido com sucesso!");
    }
}
