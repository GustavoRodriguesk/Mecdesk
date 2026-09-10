<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use App\Http\Requests\StorePecaRequest;
use App\Http\Requests\UpdatePecaRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PecaController extends Controller
{
    public function index(Request $request)
    {
        $query = Peca::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%");

            });
        }

        if ($request->filled('nome')) {

            $query->where(
                'nome',
                'like',
                '%'.$request->nome.'%'
            );
        }

        if ($request->filled('codigo')) {

            $query->where(
                'codigo',
                'like',
                '%'.$request->codigo.'%'
            );
        }

        if ($request->filled('estoque_min')) {

            $query->where(
                'estoque',
                '>=',
                $request->estoque_min
            );
        }

        if ($request->filled('estoque_max')) {

            $query->where(
                'estoque',
                '<=',
                $request->estoque_max
            );
        }

        if ($request->filled('valor_min')) {

            $query->where(
                'valor_unitario',
                '>=',
                $request->valor_min
            );
        }

        if ($request->filled('valor_max')) {

            $query->where(
                'valor_unitario',
                '<=',
                $request->valor_max
            );
        }

        $pecas = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'pecas.index',
            compact('pecas')
        );
    }

    public function create()
    {
        return view('pecas.create');
    }

    public function store(StorePecaRequest $request)
    {
        $peca = Peca::create($request->except('empresa_id'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peça cadastrada com sucesso!',
                'peca' => $peca,
            ], 201);
        }

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça cadastrada com sucesso!');
    }

    public function show(Peca $peca)
    {
        return redirect()->route('pecas.edit', $peca->id);
    }

    public function edit(Peca $peca)
    {
        return view('pecas.edit', compact('peca'));
    }

    public function update(UpdatePecaRequest $request, Peca $peca)
    {
        $peca->update($request->except('empresa_id'));

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça atualizada com sucesso!');
    }

    public function destroy(Peca $peca)
    {
        abort_if(! auth()->user()->canDelete(), 403);

        if ($peca->ordemServicoItens()->exists()) {
            return redirect()
                ->route('pecas.index')
                ->with('error', 'Não é possível excluir esta peça pois ela já está vinculada a uma ou mais ordens de serviço.');
        }

        $peca->delete();

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça excluída com sucesso!');
    }
}
