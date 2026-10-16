<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Models\PerfilAcesso;
use App\Models\User;
use App\Support\PerfisPadrao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UsuarioController extends Controller
{
    public function create()
    {
        $this->authorize('create', User::class);

        $empresa = auth()->user()->empresa;
        $maxUsuarios = $empresa?->plano?->max_usuarios ?? 1;

        if ($empresa && $empresa->users()->count() >= $maxUsuarios) {
            return redirect()
                ->route('empresa.edit')
                ->with('error', 'Limite de usuários do seu plano foi atingido.');
        }

        $perfis = PerfilAcesso::orderBy('nome')->get();

        return view('usuarios.create', compact('perfis'));
    }

    public function store(StoreUsuarioRequest $request)
    {
        $this->authorize('create', User::class);

        $empresa = auth()->user()->empresa;
        $maxUsuarios = $empresa?->plano?->max_usuarios ?? 1;

        if ($empresa && $empresa->users()->count() >= $maxUsuarios) {
            return back()->with('error', 'Limite de usuários do seu plano foi atingido.');
        }

        $validated = $request->validated();
        $perfilId = $validated['perfil_acesso_id'] ?? null;
        $role = in_array($validated['role'] ?? 'funcionario', ['gerente', 'funcionario'], true)
            ? ($validated['role'] ?? 'funcionario')
            : 'funcionario';

        if (! $perfilId && $empresa) {
            $perfilId = PerfilAcesso::where('empresa_id', $empresa->id)
                ->where(function ($q) use ($role) {
                    $nome = $role === 'gerente' ? 'Gerente' : 'Funcionário';
                    $q->where('nome', $nome)->orWhere('nome', 'like', "{$nome}%");
                })
                ->value('id');

            if (! $perfilId) {
                PerfisPadrao::criarParaEmpresa($empresa->id);
                $perfilId = PerfilAcesso::where('empresa_id', $empresa->id)
                    ->where('nome', $role === 'gerente' ? 'Gerente' : 'Atendente')
                    ->value('id');
            }
        }

        User::create([
            'empresa_id'       => auth()->user()->empresa_id,
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'password'         => Hash::make($validated['password']),
            'role'             => $role,
            'perfil_acesso_id' => $perfilId,
        ]);

        return redirect()
            ->route('empresa.edit')
            ->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function edit(User $usuario)
    {
        $this->authorize('update', $usuario);

        $perfis = PerfilAcesso::orderBy('nome')->get();

        return view('usuarios.edit', compact('usuario', 'perfis'));
    }

    public function update(Request $request, User $usuario)
    {
        $this->authorize('update', $usuario);

        $empresaId = auth()->user()->empresa_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'perfil_acesso_id' => [
                'required',
                Rule::exists('perfis_acesso', 'id')->where('empresa_id', $empresaId),
            ],
        ], [
            'perfil_acesso_id.required' => 'Selecione um perfil de acesso para o funcionário.',
            'perfil_acesso_id.exists'   => 'O perfil selecionado não pertence a esta oficina.',
        ]);

        $dados = [
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'perfil_acesso_id' => $validated['perfil_acesso_id'],
        ];

        if (! empty($validated['password'])) {
            $dados['password'] = Hash::make($validated['password']);
        }

        $usuario->update($dados);
        $usuario->limparCachePermissoes();

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Dados do funcionário {$usuario->name} atualizados com sucesso!");
    }

    public function toggleStatus(User $usuario)
    {
        $this->authorize('desativar', $usuario);
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
        $this->authorize('delete', $usuario);
        abort_if($usuario->id === auth()->id(), 422, 'Você não pode excluir sua própria conta.');

        $nome = $usuario->name;
        $usuario->delete();

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Usuário {$nome} foi removido com sucesso!");
    }
}
