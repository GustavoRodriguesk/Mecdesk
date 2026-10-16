<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AutorizaRecurso;
use App\Models\PerfilAcesso;
use App\Models\Permissao;
use App\Support\Permissoes;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Validation\Rule;

class PerfilAcessoController extends Controller implements HasMiddleware
{
    use AutorizaRecurso;

    public static function middleware(): array
    {
        return static::middlewareDoRecurso(PerfilAcesso::class, 'perfil');
    }

    public function index()
    {
        return redirect()->route('empresa.edit');
    }

    public function create()
    {
        $modulos = Permissoes::modulosAtribuiveis();

        return view('perfis.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:100',
                Rule::unique('perfis_acesso', 'nome')->where('empresa_id', $empresaId),
            ],
            'descricao' => ['nullable', 'string', 'max:255'],
            'permissoes' => ['nullable', 'array'],
            'permissoes.*' => ['string'],
        ], [
            'nome.unique' => 'Já existe um perfil com este nome na sua oficina.',
        ]);

        $permissoesEnviadas = $request->input('permissoes', []);
        $reservadas = array_filter((array) $permissoesEnviadas, fn ($s) => Permissoes::reservada((string) $s));
        if (! empty($reservadas)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'permissoes' => ['Não é permitido atribuir permissões reservadas a administradores.'],
            ]);
        }

        $perfil = new PerfilAcesso([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
        ]);
        $perfil->empresa_id = $empresaId;
        $perfil->save();

        $slugsValidos = Permissoes::normalizar($request->input('permissoes', []));
        $permissoesIds = Permissao::whereIn('slug', $slugsValidos)->pluck('id');
        $perfil->permissoes()->sync($permissoesIds);

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Perfil '{$perfil->nome}' cadastrado com sucesso!");
    }

    public function edit(PerfilAcesso $perfil)
    {
        $modulos = Permissoes::modulosAtribuiveis();
        $permissoesAtivas = $perfil->permissoes()->pluck('slug')->all();

        return view('perfis.edit', compact('perfil', 'modulos', 'permissoesAtivas'));
    }

    public function update(Request $request, PerfilAcesso $perfil)
    {
        $empresaId = auth()->user()->empresa_id;

        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:100',
                Rule::unique('perfis_acesso', 'nome')
                    ->where('empresa_id', $empresaId)
                    ->ignore($perfil->id),
            ],
            'descricao' => ['nullable', 'string', 'max:255'],
            'permissoes' => ['nullable', 'array'],
            'permissoes.*' => ['string'],
        ], [
            'nome.unique' => 'Já existe um perfil com este nome na sua oficina.',
        ]);

        $permissoesEnviadas = $request->input('permissoes', []);
        $reservadas = array_filter((array) $permissoesEnviadas, fn ($s) => Permissoes::reservada((string) $s));
        if (! empty($reservadas)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'permissoes' => ['Não é permitido atribuir permissões reservadas a administradores.'],
            ]);
        }

        $perfil->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        $slugsValidos = Permissoes::normalizar($request->input('permissoes', []));
        $permissoesIds = Permissao::whereIn('slug', $slugsValidos)->pluck('id');
        $perfil->permissoes()->sync($permissoesIds);

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Perfil '{$perfil->nome}' atualizado com sucesso!");
    }

    public function destroy(PerfilAcesso $perfil)
    {
        if ($perfil->users()->exists()) {
            return redirect()
                ->route('empresa.edit')
                ->with('error', "Não é possível excluir o perfil '{$perfil->nome}' porque existem funcionários associados a ele. Reatribua os funcionários antes de excluir.");
        }

        $nome = $perfil->nome;
        $perfil->delete();

        return redirect()
            ->route('empresa.edit')
            ->with('success', "Perfil '{$nome}' removido com sucesso!");
    }
}
