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
                '%'.$request->search.'%'
            );
        }

        if ($request->filled('nome')) {

            $query->where(
                'nome',
                'like',
                '%'.$request->nome.'%'
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
        return view('servicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
            'valor_base' => 'required|numeric',
        ]);

        Servico::create($request->except('empresa_id'));

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço criado com sucesso.');
    }

    public function show(Servico $servico)
    {
        return redirect()->route('servicos.edit', $servico->id);
    }

    public function edit(Servico $servico)
    {
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
            'valor_base' => 'required|numeric',
        ]);

        $servico->update($request->except('empresa_id'));

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Servico $servico)
    {
        if ($servico->ordemServicoItens()->exists()) {
            return redirect()
                ->route('servicos.index')
                ->with('error', 'Não é possível excluir este serviço pois ele já está vinculado a uma ou mais ordens de serviço.');
        }

        $servico->delete();

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço deletado com sucesso.');
    }
}
