<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrdemServicoController extends Controller
{
   public function index(Request $request)
{
    $query = OrdemServico::with(['cliente', 'veiculo']);

    // Busca geral
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('numero_os', 'like', "%{$search}%")

              ->orWhere('status', 'like', "%{$search}%")

              ->orWhereHas('cliente', function ($cliente) use ($search) {

                  $cliente->where('nome', 'like', "%{$search}%");

              })

              ->orWhereHas('veiculo', function ($veiculo) use ($search) {

                  $veiculo->where('placa', 'like', "%{$search}%")
                           ->orWhere('marca', 'like', "%{$search}%")
                           ->orWhere('modelo', 'like', "%{$search}%");

              });

        });
    }

    // Status
    if ($request->filled('status')) {

        $query->where('status', $request->status);

    }

    // Cliente
    if ($request->filled('cliente_id')) {

        $query->where('cliente_id', $request->cliente_id);

    }

    // Data inicial
    if ($request->filled('inicio')) {

        $query->whereDate(
            'created_at',
            '>=',
            $request->inicio
        );

    }

    // Data final
    if ($request->filled('fim')) {

        $query->whereDate(
            'created_at',
            '<=',
            $request->fim
        );

    }

    // Ordenação
    match ($request->sort) {

        'antigas' =>
            $query->oldest(),

        'valor_maior' =>
            $query->orderByDesc('valor_total'),

        'valor_menor' =>
            $query->orderBy('valor_total'),

        default =>
            $query->latest(),
    };

    $ordens = $query
        ->paginate(10)
        ->withQueryString();

    $clientes = Cliente::orderBy('nome')->get();

    return view(
        'ordens.index',
        compact('ordens', 'clientes')
    );
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
$ordem = OrdemServico::create([
    'numero_os' => 'OS-' . rand(1000, 9999),

    'cliente_id' => $request->cliente_id,
    'veiculo_id' => $request->veiculo_id,

    'user_id' => Auth::id(),

    'status' => 'aberta',

    'descricao_problema' => $request->descricao_problema,

    'valor_total' => 0,

    'aprovado_cliente' => false,

    'data_entrada' => now(),
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
        'itens',
        'historicos' => function ($query) {
        $query->latest();
    }
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
$statusAnterior = $ordem->status;

$ordem->update($request->all());

if ($statusAnterior != $request->status) {

    $ordem->historicos()->create([
        'status' => $request->status
    ]);
}


        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem atualizada com sucesso!');
    }

    public function destroy(OrdemServico $ordem)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $ordem->delete();

        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem excluída com sucesso!');
    }
    /**
     * Solicitar aprovação do cliente (gera token e altera status).
     */
    public function solicitarAprovacao(OrdemServico $ordem)
    {
        $statusAnterior = $ordem->status;

        $ordem->generateApprovalToken();

        if ($statusAnterior !== 'aguardando_aprovacao') {
            $ordem->historicos()->create([
                'status' => 'aguardando_aprovacao',
            ]);
        }

        return redirect()
            ->route('ordens.show', $ordem->id)
            ->with('success', 'Solicitação de aprovação enviada! Utilize o botão do WhatsApp para enviar ao cliente.');
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
            'itens',
            'empresa'
        ]);

        $empresa = $ordem->empresa;

        $pdf = Pdf::loadView(
            'ordens.pdf',
            compact('ordem', 'empresa')
        );

        $nomeArquivo = str($ordem->cliente->nome)->slug('-');

        return $pdf->download(
            $nomeArquivo . '-orcamento.pdf'
        );
    }
}