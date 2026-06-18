<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use Illuminate\Http\Request;

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
            '%' . $request->nome . '%'
        );
    }

    if ($request->filled('codigo')) {

        $query->where(
            'codigo',
            'like',
            '%' . $request->codigo . '%'
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
        abort_if(!auth()->user()->isAdmin(), 403);

        return view('pecas.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome' => 'required',
            'codigo' => 'unique:pecas,codigo',
            'estoque' => 'required|integer|min:0',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

        Peca::create($request->all());

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça cadastrada com sucesso!');
    }

    public function show(Peca $peca)
    {
        return view('pecas.show', compact('peca'));
    }

    public function edit(Peca $peca)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        return view('pecas.edit', compact('peca'));
    }

    public function update(Request $request, Peca $peca)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nome' => 'required',
            'codigo' => 'required|unique:pecas,codigo,' . $peca->id,
            'estoque' => 'required|integer|min:0',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

        $peca->update($request->all());

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça atualizada com sucesso!');
    }

    public function destroy(Peca $peca)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $peca->delete();

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça excluída com sucesso!');
    }
}