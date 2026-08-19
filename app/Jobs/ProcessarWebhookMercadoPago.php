<?php

namespace App\Jobs;

use App\Events\AssinaturaAtivada;
use App\Events\PagamentoRecebido;
use App\Events\PagamentoRecusado;
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

    public int $tries = 3;

    public function __construct(
        public int $webhookLogId
    ) {}

    public function handle(MercadoPagoService $mpService): void
    {
        // Trava real contra concorrência utilizando DB transaction + lockForUpdate
        DB::transaction(function () use ($mpService) {
            $log = WebhookLog::where('id', $this->webhookLogId)
                ->lockForUpdate()
                ->first();

            if (!$log || $log->processed) {
                return;
            }

            try {
                $action     = $log->action;
                $resourceId = $log->resource_id;

                if (str_contains($action, 'preapproval') || $action === 'subscription_preapproval') {
                    $this->processarPreapproval($mpService, $resourceId, $log);
                } else {
                    $this->processarPagamentoAutorizado($mpService, $resourceId, $log);
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
        });
    }

    /**
     * Processa atualizações no contrato de assinatura (subscription_preapproval).
     */
    protected function processarPreapproval(MercadoPagoService $mpService, string $preapprovalId, WebhookLog $log): void
    {
        if (empty($preapprovalId)) {
            return;
        }

        // Dupla checagem Zero-Trust na API do Mercado Pago
        $dados = $mpService->consultarAssinatura($preapprovalId);
        $status = $dados['status'] ?? 'pending';
        $externalReference = $dados['external_reference'] ?? null;

        $assinatura = Assinatura::where('mp_preapproval_id', $preapprovalId)
            ->orWhere('id', $externalReference)
            ->first();

        if (!$assinatura) {
            Log::warning("Assinatura com preapproval_id {$preapprovalId} não encontrada para webhook.");
            return;
        }

        $empresa = $assinatura->empresa;

        if (in_array($status, ['paused', 'cancelled'], true)) {
            $assinatura->update([
                'status'            => $status,
                'data_cancelamento' => $status === 'cancelled' ? now() : $assinatura->data_cancelamento,
            ]);

            if ($empresa) {
                $empresa->ativo = false;
                $empresa->save();
            }
        } elseif ($status === 'authorized') {
            // REGRA CRÍTICA DE NEGÓCIO: Se a assinatura local já estiver em 'overdue',
            // a notificação de preapproval "authorized" NÃO deve reverter a inadimplência automaticamente.
            // A reativação só ocorre mediante recebimento de um pagamento confirmado (subscription_authorized_payment).
            if ($assinatura->status !== 'overdue') {
                $assinatura->update(['status' => 'authorized']);

                if ($empresa) {
                    $empresa->plano_id = $assinatura->plano_id;
                    $empresa->ativo = true;
                    $empresa->save();
                }
            }
        }
    }

    /**
     * Processa cobranças recorrentes individuais (subscription_authorized_payment).
     */
    protected function processarPagamentoAutorizado(MercadoPagoService $mpService, string $resourceId, WebhookLog $log): void
    {
        if (empty($resourceId)) {
            return;
        }

        // Dupla checagem Zero-Trust na API do Mercado Pago
        $dados = $mpService->consultarPagamentoAutorizado($resourceId);

        $status          = $dados['payment']['status'] ?? $dados['status'] ?? 'pending';
        $statusDetail    = $dados['payment']['status_detail'] ?? $dados['status_detail'] ?? null;
        $valor           = $dados['transaction_amount'] ?? $dados['payment']['transaction_amount'] ?? 99.90;
        $preapprovalId   = $dados['preapproval_id'] ?? null;
        $externalRef     = $dados['external_reference'] ?? null;

        $assinatura = null;
        if ($preapprovalId) {
            $assinatura = Assinatura::where('mp_preapproval_id', $preapprovalId)->first();
        }
        if (!$assinatura && $externalRef) {
            $assinatura = Assinatura::find($externalRef);
        }

        if (!$assinatura) {
            Log::warning("Assinatura não localizada para o pagamento autorizado {$resourceId}.");
            return;
        }

        $empresa = $assinatura->empresa;

        // Idempotência: Cria ou atualiza o registro do pagamento individual
        $pagamento = Pagamento::updateOrCreate(
            ['mp_authorized_payment_id' => $resourceId],
            [
                'assinatura_id'    => $assinatura->id,
                'empresa_id'       => $assinatura->empresa_id,
                'metodo_pagamento' => 'cartao',
                'status'           => $status,
                'status_detail'    => $statusDetail,
                'valor'            => $valor,
                'data_pagamento'   => $status === 'approved' ? now() : null,
                'payload_resposta' => $dados,
            ]
        );

        if ($status === 'approved') {
            $assinatura->update([
                'status'             => 'authorized',
                'data_inicio'        => $assinatura->data_inicio ?? now(),
                'proximo_vencimento' => now()->addMonth(),
                'valido_ate'         => now()->addMonth(),
            ]);

            if ($empresa) {
                $empresa->plano_id = $assinatura->plano_id;
                $empresa->ativo = true;
                $empresa->save();
            }

            AssinaturaAtivada::dispatch($assinatura);
            PagamentoRecebido::dispatch($pagamento);

        } elseif ($status === 'rejected') {
            // Dispara evento de notificação para alertar o cliente durante os 3 dias de carência
            PagamentoRecusado::dispatch($pagamento);
        }
    }
}
