<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\OrdemServicoFoto;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use App\Services\OrdemServicoItemService;
use Illuminate\Support\Facades\DB;

class OrdemServicoController extends Controller
{
    public function __construct(
        protected OrdemServicoItemService $itemService
    ) {}

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
            $query->whereDate('created_at', '>=', $request->inicio);
        }

        // Data final
        if ($request->filled('fim')) {
            $query->whereDate('created_at', '<=', $request->fim);
        }

        // Ordenação
        match ($request->sort) {
            'antigas' => $query->oldest(),
            'valor_maior' => $query->orderByDesc('valor_total'),
            'valor_menor' => $query->orderBy('valor_total'),
            default => $query->latest(),
        };

        $ordens = $query->paginate(10)->withQueryString();
        $clientes = Cliente::orderBy('nome')->get();

        return view('ordens.index', compact('ordens', 'clientes'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('placa')->get();
        $servicos = Servico::orderBy('nome')->get();
        $pecas = Peca::orderBy('nome')->get();

        return view('ordens.create', compact(
            'clientes',
            'veiculos',
            'servicos',
            'pecas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
            ],
            'veiculo_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('veiculos', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
            ],
            'descricao_problema' => 'required|string',
            'problemas_previos'  => 'nullable|string',
            'observacoes'        => 'nullable|string',
            'fotos'              => 'nullable|array',
            'fotos.*'            => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'itens'              => 'nullable|array',
            'itens.*.tipo_item'  => 'nullable|in:servico,peca',
            'itens.*.servico_id' => 'nullable|integer',
            'itens.*.peca_id'    => 'nullable|integer',
            'itens.*.descricao'  => 'nullable|string|max:255',
            'itens.*.quantidade' => 'nullable|integer|min:1',
            'itens.*.valor_unitario' => 'nullable|numeric|min:0',
        ]);

        $empresaId = auth()->user()->empresa_id;

        $ordem = DB::transaction(function () use ($request, $empresaId) {
            // Buscar a última OS desta empresa para calcular o sequencial com lock
            $ultimoNumero = OrdemServico::where('empresa_id', $empresaId)
                ->lockForUpdate()
                ->latest('id')
                ->value('numero_os');

            $proximoNumero = 1;
            if ($ultimoNumero && preg_match('/OS-(\d+)/', $ultimoNumero, $matches)) {
                $proximoNumero = ((int)$matches[1]) + 1;
            }
            $numeroOs = 'OS-' . str_pad($proximoNumero, 4, '0', STR_PAD_LEFT);

            $ordem = OrdemServico::create([
                'empresa_id'         => $empresaId,
                'numero_os'          => $numeroOs,
                'cliente_id'         => $request->cliente_id,
                'veiculo_id'         => $request->veiculo_id,
                'user_id'            => Auth::id(),
                'status'             => 'aberta',
                'descricao_problema' => $request->descricao_problema,
                'problemas_previos'  => $request->problemas_previos,
                'observacoes'        => $request->observacoes,
                'valor_total'        => 0,
                'aprovado_cliente'   => false,
                'data_entrada'       => now(),
            ]);

            // Upload de fotos
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $fotoFile) {
                    if ($fotoFile->isValid()) {
                        $path = $fotoFile->store('os_fotos', 'public');
                        $ordem->fotos()->create([
                            'empresa_id'   => $empresaId,
                            'caminho_foto' => $path,
                        ]);
                    }
                }
            }

            // Criação e sincronização dos itens via Service
            if ($request->filled('itens') && is_array($request->itens)) {
                $this->itemService->sincronizarItensNaCriacao($ordem, $request->itens);
            }

            return $ordem;
        });

        return redirect()
            ->route('ordens.show', $ordem->id)
            ->with('success', 'Ordem de serviço criada com sucesso!');
    }

    public function show(OrdemServico $ordem)
    {
        $ordem->load([
            'cliente',
            'veiculo',
            'itens',
            'fotos',
            'historicos' => function ($query) {
                $query->latest();
            }
        ]);

        $servicos = Servico::orderBy('nome')->get();
        $pecas = Peca::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        $veiculos = Veiculo::orderBy('placa')->get();

        return view('ordens.show', compact(
            'ordem',
            'servicos',
            'pecas',
            'clientes',
            'veiculos'
        ));
    }

    public function edit(OrdemServico $ordem)
    {
        return redirect()->route('ordens.show', $ordem->id);
    }

    public function update(Request $request, OrdemServico $ordem)
    {
        $request->validate([
            'cliente_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('clientes', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
            ],
            'veiculo_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('veiculos', 'id')
                    ->where('empresa_id', auth()->user()->empresa_id)
            ],
            'descricao_problema' => 'required|string',
            'problemas_previos'  => 'nullable|string',
            'observacoes'        => 'nullable|string',
            'status'             => 'required',
            'fotos'              => 'nullable|array',
            'fotos.*'            => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        $statusAnterior = $ordem->status;

        $ordem->update($request->only([
            'cliente_id',
            'veiculo_id',
            'descricao_problema',
            'problemas_previos',
            'observacoes',
            'status',
        ]));

        if ($statusAnterior != $request->status) {
            $ordem->historicos()->create([
                'status' => $request->status
            ]);
        }

        // Upload de novas fotos na edição se enviadas
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $fotoFile) {
                if ($fotoFile->isValid()) {
                    $path = $fotoFile->store('os_fotos', 'public');
                    $ordem->fotos()->create([
                        'empresa_id'   => auth()->user()->empresa_id,
                        'caminho_foto' => $path,
                    ]);
                }
            }
        }

        return redirect()
            ->route('ordens.show', $ordem->id)
            ->with('success', 'Ordem de Serviço atualizada com sucesso!');
    }

    public function uploadFoto(Request $request, OrdemServico $ordem)
    {
        $request->validate([
            'fotos'   => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $fotoFile) {
                if ($fotoFile->isValid()) {
                    $path = $fotoFile->store('os_fotos', 'public');
                    $ordem->fotos()->create([
                        'empresa_id'   => auth()->user()->empresa_id,
                        'caminho_foto' => $path,
                    ]);
                }
            }
        }

        return redirect()
            ->route('ordens.show', $ordem->id)
            ->with('success', 'Foto(s) adicionada(s) à Ordem de Serviço com sucesso!');
    }

    public function destroyFoto(OrdemServicoFoto $foto)
    {
        abort_if(
            $foto->empresa_id !== auth()->user()->empresa_id,
            403,
            'Acesso não autorizado para esta foto.'
        );

        if (Storage::disk('public')->exists($foto->caminho_foto)) {
            Storage::disk('public')->delete($foto->caminho_foto);
        }

        $ordemId = $foto->ordem_servico_id;
        $foto->delete();

        return redirect()
            ->route('ordens.show', $ordemId)
            ->with('success', 'Foto removida com sucesso!');
    }

    public function destroy(OrdemServico $ordem)
    {
        abort_if(
            $ordem->empresa_id !== auth()->user()->empresa_id,
            403,
            'Acesso não autorizado para esta ordem de serviço.'
        );

        // Deletar fotos salvas fisicamente
        foreach ($ordem->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->caminho_foto)) {
                Storage::disk('public')->delete($foto->caminho_foto);
            }
        }

        $this->itemService->excluirOrdemComEstoque($ordem);

        return redirect()
            ->route('ordens.index')
            ->with('success', 'Ordem excluída com sucesso!');
    }

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
            'fotos',
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

    public function pdfVistoria(OrdemServico $ordem)
    {
        $ordem->load([
            'cliente',
            'veiculo',
            'fotos',
            'empresa'
        ]);

        $empresa = $ordem->empresa;

        $pdf = Pdf::loadView(
            'ordens.pdf_vistoria',
            compact('ordem', 'empresa')
        );

        $nomeArquivo = str($ordem->cliente->nome)->slug('-') . '-vistoria';

        return $pdf->download(
            $nomeArquivo . '.pdf'
        );
    }
}