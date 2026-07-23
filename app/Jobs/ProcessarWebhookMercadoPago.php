<?php

namespace App\Jobs;

use App\Events\AssinaturaAtivada;
use App\Events\PagamentoRecebido;
use App\Models\Assinatura;
use App\Models\Pagamento;
use App\Models\WebhookLog;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessarWebhookMercadoPago implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    public function __construct(
        public int $webhookLogId
    ) {}

    public function handle(MercadoPagoService $mpService): void
    {
        $log = WebhookLog::find($this->webhookLogId);

        if (!$log || $log->processed) {
            return;
        }

        try {
            $action     = $log->action;
            $resourceId = $log->resource_id;

            // Determinar o tipo de notificação e consultar na API do Mercado Pago (Zero-Trust)
            if (str_contains($action, 'payment') || str_contains($action, 'pay')) {
                $this->processarPagamento($mpService, $resourceId, $log);
            } elseif (str_contains($action, 'subscription') || str_contains($action, 'preapproval')) {
                $this->processarAssinatura($mpService, $resourceId, $log);
            }

            $log->update([
                'processed' => true,
                'error'     => null,
            ]);
        } catch (\Throwable $e) {
            Log::error("Erro ao processar WebhookLog #{$this->webhookLogId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $log->update([
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function processarPagamento(MercadoPagoService $mpService, string $paymentId, WebhookLog $log): void
    {
        // 1. DUPLA CHECAGEM OBRIGATÓRIA NA API DO MERCADO PAGO
        $dadosPagamento = $mpService->consultarPagamento($paymentId);

        $status            = $dadosPagamento['status'] ?? 'pending';
        $statusDetail      = $dadosPagamento['status_detail'] ?? null;
        $valor             = $dadosPagamento['transaction_amount'] ?? 0;
        $externalReference = $dadosPagamento['external_reference'] ?? null;
        $metodoPagamento   = ($dadosPagamento['payment_method_id'] ?? '') === 'pix' ? 'pix' : 'cartao';

        if (!$externalReference) {
            Log::warning("Webhook de pagamento {$paymentId} recebido sem external_reference");
            return;
        }

        $assinatura = Assinatura::find($externalReference);

        if (!$assinatura) {
            Log::warning("Assinatura #{$externalReference} não encontrada para o pagamento {$paymentId}");
            return;
        }

        DB::transaction(function () use ($assinatura, $paymentId, $status, $statusDetail, $valor, $metodoPagamento, $dadosPagamento) {
            // Idempotência: Registrar ou Atualizar Pagamento
            $pagamento = Pagamento::updateOrCreate(
                ['mp_payment_id' => $paymentId],
                [
                    'assinatura_id'    => $assinatura->id,
                    'empresa_id'       => $assinatura->empresa_id,
                    'metodo_pagamento' => $metodoPagamento,
                    'status'           => $status,
                    'status_detail'    => $statusDetail,
                    'valor'            => $valor,
                    'data_pagamento'   => $status === 'approved' ? now() : null,
                    'payload_resposta' => $dadosPagamento,
                ]
            );

            // Regra de Ativação estrita: Apenas confirmação oficial de pagamento "approved" ativa o acesso
            if ($status === 'approved') {
                $assinatura->update([
                    'status'             => 'authorized',
                    'metodo_pagamento'   => $metodoPagamento,
                    'data_inicio'        => $assinatura->data_inicio ?? now(),
                    'proximo_vencimento' => now()->addMonth(),
                    'valido_ate'         => now()->addMonth(),
                ]);

                // Ativa a empresa
                $empresa = $assinatura->empresa;
                $empresa->ativo = true;
                $empresa->save();

                // Dispara eventos do sistema
                AssinaturaAtivada::dispatch($assinatura);
                PagamentoRecebido::dispatch($pagamento);
            } elseif (in_array($status, ['rejected', 'cancelled', 'refunded', 'charged_back'], true)) {
                // Se falhou o pagamento e a vigência atual já venceu, marca como overdue
                if (!$assinatura->valido_ate || $assinatura->valido_ate->isPast()) {
                    $assinatura->update(['status' => 'overdue']);
                }
            }
        });
    }

    protected function processarAssinatura(MercadoPagoService $mpService, string $preapprovalId, WebhookLog $log): void
    {
        // DUPLA CHECAGEM OBRIGATÓRIA NA API DE ASSINATURAS DO MERCADO PAGO
        $dadosAssinatura   = $mpService->consultarAssinatura($preapprovalId);
        $status            = $dadosAssinatura['status'] ?? 'pending';
        $externalReference = $dadosAssinatura['external_reference'] ?? null;

        if (!$externalReference) {
            return;
        }

        $assinatura = Assinatura::find($externalReference);

        if (!$assinatura) {
            return;
        }

        DB::transaction(function () use ($assinatura, $preapprovalId, $status) {
            $statusMapeado = match ($status) {
                'authorized' => 'authorized',
                'paused'     => 'paused',
                'cancelled'  => 'cancelled',
                default      => 'pending',
            };

            $assinatura->update([
                'mp_preapproval_id' => $preapprovalId,
                'status'            => $statusMapeado,
            ]);

            $empresa = $assinatura->empresa;

            if ($statusMapeado === 'authorized') {
                $empresa->ativo = true;
                $empresa->save();

                AssinaturaAtivada::dispatch($assinatura);
            } elseif (in_array($statusMapeado, ['cancelled', 'paused'], true)) {
                // Se a assinatura for cancelada ou pausada e a vigência expirou, inativa a empresa
                if (!$assinatura->valido_ate || $assinatura->valido_ate->isPast()) {
                    $empresa->ativo = false;
                    $empresa->save();
                }
            }
        });
    }
}
