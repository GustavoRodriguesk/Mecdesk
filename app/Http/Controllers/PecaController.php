<?php

namespace App\Http\Controllers;

use App\Models\Peca;
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

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'codigo' => [
                'nullable',
                Rule::unique('pecas', 'codigo')
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'estoque' => 'required|integer|min:0',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

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

    public function update(Request $request, Peca $peca)
    {
        $request->validate([
            'nome' => 'required',
            'codigo' => [
                'required',
                Rule::unique('pecas', 'codigo')
                    ->ignore($peca->id)
                    ->where('empresa_id', auth()->user()->empresa_id),
            ],
            'estoque' => 'required|integer|min:0',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

        $peca->update($request->except('empresa_id'));

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça atualizada com sucesso!');
    }

    public function destroy(Peca $peca)
    {
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
