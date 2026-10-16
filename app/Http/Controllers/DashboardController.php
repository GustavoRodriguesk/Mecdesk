<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\Veiculo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $canViewOperacional = $user->temPermissao('dashboard.operacional.visualizar');
        $canViewFinanceiro = $user->temPermissao('dashboard.financeiro.visualizar');

        abort_if(! $canViewOperacional && ! $canViewFinanceiro, 403, 'Acesso não autorizado ao painel.');

        $empresaId = $user->empresa_id;

        $dadosOperacionais = [
            'clientes'             => 0,
            'veiculos'             => 0,
            'ordens'               => 0,
            'servicos'             => 0,
            'pecasBaixas'          => collect(),
            'osAbertas'            => 0,
            'osAndamento'          => 0,
            'osAguardandoAprovacao' => 0,
            'osConcluidas'         => 0,
            'osCanceladas'         => 0,
            'statusChart'          => collect(),
            'servicosChart'        => collect(),
            'pecasChart'           => collect(),
        ];

        if ($canViewOperacional) {
            $labels = [
                'aberta'               => 'Aberta',
                'em_andamento'         => 'Em Andamento',
                'aguardando_aprovacao' => 'Aguardando Aprovação',
                'aprovada'             => 'Aprovada',
                'reprovada'            => 'Reprovada',
                'concluida'            => 'Concluída',
                'entregue'             => 'Entregue',
                'cancelada'            => 'Cancelada',
            ];

            $dadosOperacionais = [
                'clientes' => Cliente::count(),
                'veiculos' => Veiculo::count(),
                'ordens'   => OrdemServico::count(),
                'servicos' => Servico::count(),
                'pecasBaixas' => Peca::where('estoque', '<=', 5)
                    ->orderBy('estoque')
                    ->take(5)
                    ->get(),
                'osAbertas'            => OrdemServico::where('status', 'aberta')->count(),
                'osAndamento'          => OrdemServico::where('status', 'em_andamento')->count(),
                'osAguardandoAprovacao' => OrdemServico::where('status', 'aguardando_aprovacao')->count(),
                'osConcluidas'         => OrdemServico::where('status', 'concluida')->count(),
                'osCanceladas'         => OrdemServico::where('status', 'cancelada')->count(),
                'statusChart'          => OrdemServico::selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->get()
                    ->map(function ($item) use ($labels) {
                        $item->status_label = $labels[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status));
                        return $item;
                    }),
                'servicosChart' => DB::table('ordem_servico_itens')
                    ->join('ordem_servicos', 'ordem_servico_itens.ordem_servico_id', '=', 'ordem_servicos.id')
                    ->join('servicos', 'ordem_servico_itens.servico_id', '=', 'servicos.id')
                    ->where('ordem_servico_itens.tipo_item', 'servico')
                    ->where('ordem_servicos.empresa_id', $empresaId)
                    ->whereNull('ordem_servicos.deleted_at')
                    ->whereNull('servicos.deleted_at')
                    ->selectRaw('servicos.nome as nome, COUNT(*) as total')
                    ->groupBy('servicos.id', 'servicos.nome')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get(),
                'pecasChart' => DB::table('ordem_servico_itens')
                    ->join('ordem_servicos', 'ordem_servico_itens.ordem_servico_id', '=', 'ordem_servicos.id')
                    ->join('pecas', 'ordem_servico_itens.peca_id', '=', 'pecas.id')
                    ->where('ordem_servico_itens.tipo_item', 'peca')
                    ->where('ordem_servicos.empresa_id', $empresaId)
                    ->whereNull('ordem_servicos.deleted_at')
                    ->whereNull('pecas.deleted_at')
                    ->selectRaw('pecas.nome as nome, SUM(ordem_servico_itens.quantidade) as total')
                    ->groupBy('pecas.id', 'pecas.nome')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get(),
            ];
        }

        $dadosFinanceiros = [
            'faturamentoTotal' => 0.0,
            'faturamentoMes'   => 0.0,
            'faturamentoChart' => collect(),
        ];

        if ($canViewFinanceiro) {
            $meses = [
                1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez',
            ];

            $faturamentoChartRaw = (DB::connection()->getDriverName() === 'sqlite'
                ? OrdemServico::selectRaw(
                    "strftime('%m', created_at) as mes_num,
                     strftime('%m', created_at) as mes,
                     SUM(valor_total) as total"
                )
                : OrdemServico::selectRaw(
                    'MONTH(created_at) as mes_num,
                     MONTHNAME(created_at) as mes,
                     SUM(valor_total) as total'
                )
            )
            ->where('status', 'concluida')
            ->groupBy('mes_num', 'mes')
            ->orderBy('mes_num')
            ->get()
            ->map(function ($item) use ($meses) {
                $item->mes_pt = $meses[(int) $item->mes_num] ?? $item->mes;
                return $item;
            });

            $dadosFinanceiros = [
                'faturamentoTotal' => (float) OrdemServico::where('status', 'concluida')->sum('valor_total'),
                'faturamentoMes'   => (float) OrdemServico::where('status', 'concluida')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('valor_total'),
                'faturamentoChart' => $faturamentoChartRaw,
            ];
        }

        return view('dashboard', array_merge($dadosOperacionais, $dadosFinanceiros));
    }
}