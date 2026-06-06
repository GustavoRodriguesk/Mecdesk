<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Cliente;
use App\Models\Veiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrdemServicoController extends Controller
{
    public function index()
    {
        $ordens = OrdemServico::with(['cliente', 'veiculo'])
            ->latest()
            ->paginate(10);

        return view('ordens.index', compact('ordens'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('placa')->get();


        return view('ordens.create', compact(
            'clientes',
            'veiculos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'descricao_problema' => 'required',
        ]);

        OrdemServico::create([
            'cliente_id' => $request->cliente_id,
            'veiculo_id' => $request->veiculo_id,
            'descricao_problema' => $request->descricao_problema,
            'status' => 'aberta',
        ]);

        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem de serviço criada com sucesso!');
    }

   public function show(OrdemServico $ordem)
{
    $ordem->load([
        'cliente',
        'veiculo',
        'itens'
    ]);

    $servicos = Servico::orderBy('nome')->get();
    $pecas = Peca::orderBy('nome')->get();

    return view('ordens.show', compact(
        'ordem',
        'servicos',
        'pecas'
    ));
}
    public function edit(OrdemServico $ordem)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('placa')->get();

        return view('ordens.edit', compact(
            'ordem',
            'clientes',
            'veiculos'
        ));
    }

    public function update(Request $request, OrdemServico $ordem)
    {
        $request->validate([
    'cliente_id' => 'required|exists:clientes,id',
    'veiculo_id' => 'required|exists:veiculos,id',
    'descricao_problema' => 'required',
    'status' => 'required',
]);

        $ordem->update($request->all());

        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem atualizada com sucesso!');
    }

    public function destroy(OrdemServico $ordem)
    {
        $ordem->delete();

        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem excluída com sucesso!');
    }
    public function budgetsIndex()
    {
        $approved = OrdemServico::where('status', 'concluida')
            ->with(['cliente', 'veiculo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $cancelled = OrdemServico::where('status', 'cancelada')
            ->with(['cliente', 'veiculo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pending = OrdemServico::where('status', 'aguardando_aprovacao')
            ->with(['cliente', 'veiculo'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('budgets.index', compact('approved', 'cancelled', 'pending'));
    }
    public function pdf(OrdemServico $ordem)
{
    $ordem->load([
        'cliente',
        'veiculo',
        'itens'
    ]);

    $pdf = Pdf::loadView(
        'ordens.pdf',
        compact('ordem')
    );

    return $pdf->download(
        'OS-' . $ordem->numero_os . '.pdf'
    );
}
}