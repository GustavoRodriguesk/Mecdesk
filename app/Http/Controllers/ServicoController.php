<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
public function index(Request $request)
{
    $query = Servico::query();

    if ($request->filled('search')) {

        $query->where(
            'nome',
            'like',
            '%' . $request->search . '%'
        );
    }

    if ($request->filled('nome')) {

        $query->where(
            'nome',
            'like',
            '%' . $request->nome . '%'
        );
    }

    if ($request->filled('valor_min')) {

        $query->where(
            'valor_base',
            '>=',
            $request->valor_min
        );
    }

    if ($request->filled('valor_max')) {

        $query->where(
            'valor_base',
            '<=',
            $request->valor_max
        );
    }

    $servicos = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view(
        'servicos.index',
        compact('servicos')
    );
}
public function create()
{
    abort_if(!auth()->user()->isAdmin(), 403);

    return view('servicos.create');

}
public function store(Request $request)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $request->validate([
        'nome' => 'required',
        'descricao' => 'required',
        'valor_base' => 'required|numeric',
    ]);

    Servico::create($request->all());

    return redirect()->route('servicos.index')
                    ->with('success', 'Serviço criado com sucesso.');
}
public function show(Servico $servico)
{
    return view('servicos.show', compact('servico'));


}
public function edit(Servico $servico)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    return view('servicos.edit', compact('servico'));
}
public function update(Request $request, Servico $servico)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $request->validate([
        'nome' => 'required',
        'descricao' => 'required',
        'valor_base' => 'required|numeric',
    ]);

    $servico->update($request->all());
    return redirect()->route('servicos.index')
                    ->with('success', 'Serviço atualizado com sucesso.');
}
public function destroy(Servico $servico)
{
    abort_if(!auth()->user()->isAdmin(), 403);

    $servico->delete();
    return redirect()->route('servicos.index')
                    ->with('success', 'Serviço deletado com sucesso.');
}
}

