<?php

namespace App\Services;

use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\Peca;
use App\Models\Servico;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrdemServicoItemService
{
    /**
     * Adiciona um serviço (do catálogo ou personalizado) à Ordem de Serviço.
     */
    public function adicionarServico(OrdemServico $ordem, array $dados): OrdemServicoItem
    {
        $empresaId = $ordem->empresa_id;
        $servicoId = !empty($dados['servico_id']) ? (int) $dados['servico_id'] : null;
        $quantidade = max(1, (int) ($dados['quantidade'] ?? 1));

        if ($servicoId) {
            $servico = Servico::where('id', $servicoId)
                ->where('empresa_id', $empresaId)
                ->firstOrFail();

            $descricao = !empty($dados['descricao']) ? trim($dados['descricao']) : $servico->nome;
            $valorUnitario = isset($dados['valor_unitario']) && $dados['valor_unitario'] !== ''
                ? (float) $this->converterParaFloat($dados['valor_unitario'])
                : (float) $servico->valor_base;
        } else {
            if (empty($dados['descricao'])) {
                throw ValidationException::withMessages([
                    'descricao' => 'A descrição do serviço personalizado é obrigatória.'
                ]);
            }
            $descricao = trim($dados['descricao']);
            $valorUnitario = (float) $this->converterParaFloat($dados['valor_unitario'] ?? 0);
        }

        $valorTotal = round($quantidade * $valorUnitario, 2);

        $item = OrdemServicoItem::create([
            'ordem_servico_id' => $ordem->id,
            'tipo_item'        => 'servico',
            'servico_id'       => $servicoId,
            'peca_id'          => null,
            'descricao'        => $descricao,
            'quantidade'       => $quantidade,
            'valor_unitario'   => $valorUnitario,
            'valor_total'      => $valorTotal,
        ]);

        $this->recalcularTotalOrdem($ordem);

        return $item;
    }

    /**
     * Adiciona uma peça (do catálogo ou personalizada) à Ordem de Serviço.
     */
    public function adicionarPeca(OrdemServico $ordem, array $dados): OrdemServicoItem
    {
        $empresaId = $ordem->empresa_id;
        $pecaId = !empty($dados['peca_id']) ? (int) $dados['peca_id'] : null;
        $quantidade = max(1, (int) ($dados['quantidade'] ?? 1));
        $controlaEstoque = $ordem->empresa ? $ordem->empresa->hasControleEstoque() : true;

        if ($pecaId) {
            $query = Peca::where('id', $pecaId)->where('empresa_id', $empresaId);

            if ($controlaEstoque) {
                // Lock for update para prevenir condição de corrida no estoque
                $peca = $query->lockForUpdate()->firstOrFail();

                if ($quantidade > $peca->estoque) {
                    throw ValidationException::withMessages([
                        'quantidade' => "Estoque insuficiente para a peça \"{$peca->nome}\". Disponível: {$peca->estoque}."
                    ]);
                }
            } else {
                $peca = $query->firstOrFail();
            }

            $descricao = !empty($dados['descricao']) ? trim($dados['descricao']) : $peca->nome;
            $valorUnitario = isset($dados['valor_unitario']) && $dados['valor_unitario'] !== ''
                ? (float) $this->converterParaFloat($dados['valor_unitario'])
                : (float) $peca->valor_unitario;

            if ($controlaEstoque) {
                $peca->decrement('estoque', $quantidade);
            }
        } else {
            if (empty($dados['descricao'])) {
                throw ValidationException::withMessages([
                    'descricao' => 'A descrição da peça personalizada é obrigatória.'
                ]);
            }
            $descricao = trim($dados['descricao']);
            $valorUnitario = (float) $this->converterParaFloat($dados['valor_unitario'] ?? 0);
        }

        $valorTotal = round($quantidade * $valorUnitario, 2);

        $item = OrdemServicoItem::create([
            'ordem_servico_id' => $ordem->id,
            'tipo_item'        => 'peca',
            'peca_id'          => $pecaId,
            'servico_id'       => null,
            'descricao'        => $descricao,
            'quantidade'       => $quantidade,
            'valor_unitario'   => $valorUnitario,
            'valor_total'      => $valorTotal,
        ]);

        $this->recalcularTotalOrdem($ordem);

        return $item;
    }

    /**
     * Atualiza um item existente na OS (quantidade, valor unitário, descrição).
     * Trata o estoque proporcionalmente (delta) se o controle de estoque estiver ativado.
     */
    public function atualizarItem(OrdemServicoItem $item, array $dados): OrdemServicoItem
    {
        return DB::transaction(function () use ($item, $dados) {
            $ordem = $item->ordem;
            $controlaEstoque = $ordem->empresa ? $ordem->empresa->hasControleEstoque() : true;
            $novaQuantidade = max(1, (int) ($dados['quantidade'] ?? $item->quantidade));
            $novoValorUnitario = isset($dados['valor_unitario']) && $dados['valor_unitario'] !== ''
                ? (float) $this->converterParaFloat($dados['valor_unitario'])
                : (float) $item->valor_unitario;
            $novaDescricao = !empty($dados['descricao']) ? trim($dados['descricao']) : $item->descricao;

            // Se for peça do catálogo e controle de estoque estiver ativo, tratar movimentação proporcional
            if ($item->tipo_item === 'peca' && $item->peca_id && $controlaEstoque) {
                $peca = Peca::where('id', $item->peca_id)
                    ->where('empresa_id', $ordem->empresa_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $delta = $novaQuantidade - $item->quantidade;

                if ($delta > 0) {
                    if ($delta > $peca->estoque) {
                        throw ValidationException::withMessages([
                            'quantidade' => "Estoque insuficiente para adicionar mais {$delta} un. de \"{$peca->nome}\". Disponível em estoque: {$peca->estoque}."
                        ]);
                    }
                    $peca->decrement('estoque', $delta);
                } elseif ($delta < 0) {
                    $peca->increment('estoque', abs($delta));
                }
            }

            $valorTotal = round($novaQuantidade * $novoValorUnitario, 2);

            $item->update([
                'descricao'      => $novaDescricao,
                'quantidade'     => $novaQuantidade,
                'valor_unitario' => $novoValorUnitario,
                'valor_total'    => $valorTotal,
            ]);

            $this->recalcularTotalOrdem($ordem);

            return $item;
        });
    }

    /**
     * Remove um item da Ordem de Serviço e devolve o estoque se for peça do catálogo e controle ativo.
     */
    public function removerItem(OrdemServicoItem $item): void
    {
        DB::transaction(function () use ($item) {
            $ordem = $item->ordem;
            $controlaEstoque = $ordem->empresa ? $ordem->empresa->hasControleEstoque() : true;

            if ($item->tipo_item === 'peca' && $item->peca_id && $controlaEstoque) {
                $peca = Peca::where('id', $item->peca_id)
                    ->where('empresa_id', $ordem->empresa_id)
                    ->lockForUpdate()
                    ->first();

                if ($peca) {
                    $peca->increment('estoque', $item->quantidade);
                }
            }

            $item->delete();

            $this->recalcularTotalOrdem($ordem);
        });
    }

    /**
     * Processa e insere todos os itens durante a criação da OS dentro da transação.
     */
    public function sincronizarItensNaCriacao(OrdemServico $ordem, array $itens): void
    {
        foreach ($itens as $itemDado) {
            $tipo = $itemDado['tipo_item'] ?? ($itemDado['tipo'] ?? null);

            if ($tipo === 'servico') {
                $this->adicionarServico($ordem, $itemDado);
            } elseif ($tipo === 'peca') {
                $this->adicionarPeca($ordem, $itemDado);
            }
        }

        $this->recalcularTotalOrdem($ordem);
    }

    /**
     * Exclui a OS de forma segura devolvendo o estoque de todas as peças de catálogo quando controle ativo.
     */
    public function excluirOrdemComEstoque(OrdemServico $ordem): void
    {
        DB::transaction(function () use ($ordem) {
            $controlaEstoque = $ordem->empresa ? $ordem->empresa->hasControleEstoque() : true;

            if ($controlaEstoque) {
                $itensPecas = $ordem->itens()
                    ->where('tipo_item', 'peca')
                    ->whereNotNull('peca_id')
                    ->get();

                foreach ($itensPecas as $item) {
                    $peca = Peca::where('id', $item->peca_id)
                        ->where('empresa_id', $ordem->empresa_id)
                        ->lockForUpdate()
                        ->first();

                    if ($peca) {
                        $peca->increment('estoque', $item->quantidade);
                    }
                }
            }

            $ordem->itens()->delete();
            $ordem->historicos()->delete();
            $ordem->delete();
        });
    }

    /**
     * Recalcula e atualiza o valor_total da Ordem de Serviço no banco de dados.
     */
    public function recalcularTotalOrdem(OrdemServico $ordem): float
    {
        $total = (float) $ordem->itens()->sum('valor_total');

        $ordem->update([
            'valor_total' => $total,
        ]);

        return $total;
    }

    /**
     * Converte valores numéricos formatados em padrão BR (e.g. "1.250,50") ou US ("1250.50") para float puro.
     */
    private function converterParaFloat(mixed $valor): float
    {
        if (is_numeric($valor)) {
            return (float) $valor;
        }

        if (!is_string($valor)) {
            return 0.0;
        }

        $valor = trim($valor);
        // Se tiver vírgula como decimal e pontos como milhares
        if (str_contains($valor, ',') && str_contains($valor, '.')) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } elseif (str_contains($valor, ',')) {
            $valor = str_replace(',', '.', $valor);
        }

        return (float) preg_replace('/[^\d.]/', '', $valor);
    }
}
