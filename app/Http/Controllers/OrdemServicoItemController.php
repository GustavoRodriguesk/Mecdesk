<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Servico;
use App\Models\Peca;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;

class OrdemServicoItemController extends Controller
{
 public function store(Request $request, OrdemServico $ordem)
{
    $servico = Servico::findOrFail($request->servico_id);

    OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item' => 'servico',
        'servico_id' => $servico->id,
        'descricao' => $servico->nome,
        'quantidade' => $request->quantidade,
        'valor_unitario' => $servico->valor_base,
        'valor_total' => $request->quantidade * $servico->valor_base,
    ]);

    $ordem->update([
        'valor_total' => $ordem->itens()->sum('valor_total')
    ]);

    return back()->with('success', 'Serviço adicionado à ordem.');
}
    public function storePeca(Request $request, OrdemServico $ordem)
{
    $peca = Peca::findOrFail($request->peca_id);

    if ($request->quantidade > $peca->estoque) {

        return back()->with(
            'error',
            'Estoque insuficiente.'
        );
    }

    OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item' => 'peca',
        'peca_id' => $peca->id,
        'descricao' => $peca->nome,
        'quantidade' => $request->quantidade,
        'valor_unitario' => $peca->valor_unitario,
        'valor_total' =>
            $request->quantidade * $peca->valor_unitario,
    ]);

    $peca->decrement(
        'estoque',
        $request->quantidade
    );

    $ordem->update([
    'valor_total' => $ordem->itens()->sum('valor_total')
]);
    return back()->with(
        'success',
        'Peça adicionada à ordem.'
    );
}
    public function destroy(OrdemServicoItem $item)
    {
        if ($item->tipo_item === 'peca') {
            $peca = Peca::find($item->peca_id);
            if ($peca) {
                $peca->increment('estoque', $item->quantidade);
            }
        }

        $ordem = OrdemServico::find($item->ordem_servico_id);
        $item->delete();

        if ($ordem) {
            $ordem->update([
                'valor_total' => $ordem->itens()->sum('valor_total')
            ]);
        }

        return back()->with('success', 'Item removido da ordem.');
    }                               
}