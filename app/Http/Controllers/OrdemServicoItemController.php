<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Services\OrdemServicoItemService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrdemServicoItemController extends Controller
{
    public function __construct(
        protected OrdemServicoItemService $itemService
    ) {}

    /**
     * Adiciona um serviço (do catálogo ou personalizado) à OS.
     */
    public function store(Request $request, OrdemServico $ordem)
    {
        $this->autorizarOrdem($ordem);

        $request->validate([
            'servico_id'     => 'nullable|integer',
            'descricao'      => 'nullable|string|max:255',
            'quantidade'     => 'nullable|integer|min:1',
            'valor_unitario' => 'nullable|numeric|min:0',
        ]);

        try {
            $item = $this->itemService->adicionarServico($ordem, $request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Serviço adicionado com sucesso.',
                    'item'        => $item,
                    'valor_total' => $ordem->fresh()->valor_total,
                ], 201);
            }

            return back()->with('success', 'Serviço adicionado à ordem.');
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $e->errors(),
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Adiciona uma peça (do catálogo ou personalizada) à OS.
     */
    public function storePeca(Request $request, OrdemServico $ordem)
    {
        $this->autorizarOrdem($ordem);

        $request->validate([
            'peca_id'        => 'nullable|integer',
            'descricao'      => 'nullable|string|max:255',
            'quantidade'     => 'nullable|integer|min:1',
            'valor_unitario' => 'nullable|numeric|min:0',
        ]);

        try {
            $item = $this->itemService->adicionarPeca($ordem, $request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Peça adicionada com sucesso.',
                    'item'        => $item,
                    'valor_total' => $ordem->fresh()->valor_total,
                ], 201);
            }

            return back()->with('success', 'Peça adicionada à ordem.');
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $e->errors(),
                ], 422);
            }
            return back()->with('error', $e->getMessage())->withErrors($e->errors());
        }
    }

    /**
     * Atualiza a quantidade, valor unitário ou descrição de um item existente.
     */
    public function update(Request $request, OrdemServicoItem $item)
    {
        $this->autorizarItem($item);

        $request->validate([
            'descricao'      => 'nullable|string|max:255',
            'quantidade'     => 'required|integer|min:1',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

        try {
            $itemAtualizado = $this->itemService->atualizarItem($item, $request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Item atualizado com sucesso.',
                    'item'        => $itemAtualizado,
                    'valor_total' => $item->ordem->fresh()->valor_total,
                ]);
            }

            return back()->with('success', 'Item atualizado com sucesso.');
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $e->errors(),
                ], 422);
            }
            return back()->with('error', $e->getMessage())->withErrors($e->errors());
        }
    }

    /**
     * Remove um item da Ordem de Serviço, devolvendo o estoque quando peça de catálogo.
     */
    public function destroy(Request $request, OrdemServicoItem $item)
    {
        $this->autorizarItem($item);

        $this->itemService->removerItem($item);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removido com sucesso.',
            ]);
        }

        return back()->with('success', 'Item removido da ordem.');
    }

    /**
     * Validação de segurança multitenant para Ordem de Serviço.
     */
    protected function autorizarOrdem(OrdemServico $ordem): void
    {
        abort_if(
            $ordem->empresa_id !== auth()->user()->empresa_id,
            403,
            'Acesso não autorizado para esta ordem de serviço.'
        );
    }

    /**
     * Validação de segurança multitenant para Item da Ordem.
     */
    protected function autorizarItem(OrdemServicoItem $item): void
    {
        abort_if(
            !$item->ordem || $item->ordem->empresa_id !== auth()->user()->empresa_id,
            403,
            'Acesso não autorizado para este item.'
        );
    }
}
