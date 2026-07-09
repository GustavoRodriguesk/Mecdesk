<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Veiculo;

class DashboardController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->empresa_id;

        return view('dashboard', [

            'clientes' => Cliente::count(),

            'veiculos' => Veiculo::count(),

            'ordens' => OrdemServico::count(),

            'servicos' => Servico::count(),

            'pecasBaixas' => Peca::where('estoque', '<=', 5)
                ->orderBy('estoque')
                ->take(5)
                ->get(),

            'osAbertas' => OrdemServico::where('status', 'aberta')->count(),

            'osAndamento' => OrdemServico::where('status', 'em_andamento')->count(),

            'osConcluidas' => OrdemServico::where('status', 'concluida')->count(),

            'osCanceladas' => OrdemServico::where('status', 'cancelada')->count(),

            'faturamentoTotal' => OrdemServico::where(
                'status',
                'concluida'
            )->sum('valor_total'),

            'faturamentoMes' => OrdemServico::where(
                'status',
                'concluida'
            )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('valor_total'),

            'faturamentoChart' => OrdemServico::selectRaw(
                'MONTH(created_at) as mes_num,
                 MONTHNAME(created_at) as mes,
                 SUM(valor_total) as total'
            )
            ->where('status', 'concluida')
            ->groupBy('mes_num', 'mes')
            ->orderBy('mes_num')
            ->get(),

            'statusChart' => OrdemServico::selectRaw(
                'status,
                 COUNT(*) as total'
            )
            ->groupBy('status')
            ->get(),

            'servicosChart' => DB::table('ordem_servico_itens')
                ->join(
                    'ordem_servicos',
                    'ordem_servico_itens.ordem_servico_id',
                    '=',
                    'ordem_servicos.id'
                )
                ->join(
                    'servicos',
                    'ordem_servico_itens.servico_id',
                    '=',
                    'servicos.id'
                )
                ->where('ordem_servico_itens.tipo_item', 'servico')
                ->where('ordem_servicos.empresa_id', $empresaId)
                ->selectRaw('servicos.nome as nome, COUNT(*) as total')
                ->groupBy('servicos.id', 'servicos.nome')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),

            'pecasChart' => DB::table('ordem_servico_itens')
                ->join(
                    'ordem_servicos',
                    'ordem_servico_itens.ordem_servico_id',
                    '=',
                    'ordem_servicos.id'
                )
                ->join(
                    'pecas',
                    'ordem_servico_itens.peca_id',
                    '=',
                    'pecas.id'
                )
                ->where('ordem_servico_itens.tipo_item', 'peca')
                ->where('ordem_servicos.empresa_id', $empresaId)
                ->selectRaw('pecas.nome as nome, SUM(ordem_servico_itens.quantidade) as total')
                ->groupBy('pecas.id', 'pecas.nome')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),

        ]);
    }
}