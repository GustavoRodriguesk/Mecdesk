<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WebhookLog;
use App\Models\Pagamento;
use App\Models\Assinatura;
use App\Services\MercadoPagoService;

class MercadoPagoWebhookController extends Controller
{
    protected $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    public function handle(Request $request)
    {
        $payload = $request->all();

        // Salvar log no banco
        $log = WebhookLog::create([
            'event_id'    => $payload['id'] ?? null,
            'action'      => $payload['action'] ?? $payload['type'] ?? 'unknown',
            'resource_id' => $payload['data']['id'] ?? null,
            'payload'     => $payload,
            'processed'   => false,
            'error'       => null,
        ]);

        try {
            $type = $payload['type'] ?? null;
            $action = $payload['action'] ?? null;

            if ($type === 'payment' || str_contains($action, 'payment')) {
                $this->processPayment($payload['data']['id'] ?? null);
            } elseif ($type === 'subscription' || str_contains($action, 'subscription') || str_contains($action, 'preapproval')) {
                $this->processSubscription($payload['data']['id'] ?? null);
            }

            $log->update(['processed' => true]);

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            $log->update(['error' => $e->getMessage()]);
            Log::error('Erro Webhook Mercado Pago: ' . $e->getMessage());
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function processPayment($paymentId)
    {
        if (!$paymentId) return;

        $mpPayment = $this->mpService->getPayment($paymentId);
        
        if ($mpPayment) {
            $pagamento = Pagamento::where('mp_payment_id', $paymentId)->first();
            
            if ($pagamento) {
                $pagamento->status = $mpPayment->status;
                $pagamento->status_detail = $mpPayment->status_detail;
                $pagamento->payload_resposta = $mpPayment->toArray();
                
                if ($mpPayment->status === 'approved') {
                    $pagamento->data_pagamento = now();
                }
                
                $pagamento->save();
            } else {
                // Tenta associar se vier um external reference
                if (isset($mpPayment->external_reference) && !empty($mpPayment->external_reference)) {
                    $assinatura = Assinatura::find($mpPayment->external_reference);
                    if ($assinatura) {
                        Pagamento::updateOrCreate(
                            ['mp_payment_id' => $paymentId],
                            [
                                'assinatura_id'    => $assinatura->id,
                                'empresa_id'       => $assinatura->empresa_id,
                                'metodo_pagamento' => ($mpPayment->payment_method_id === 'pix' ? 'pix' : 'cartao'),
                                'status'           => $mpPayment->status,
                                'status_detail'    => $mpPayment->status_detail,
                                'valor'            => $mpPayment->transaction_amount,
                                'data_pagamento'   => $mpPayment->status === 'approved' ? now() : null,
                                'payload_resposta' => $mpPayment->toArray(),
                            ]
                        );

                        if ($mpPayment->status === 'approved') {
                            $assinatura->update([
                                'status'             => 'authorized',
                                'data_inicio'        => $assinatura->data_inicio ?? now(),
                                'proximo_vencimento' => now()->addMonth(),
                                'valido_ate'         => now()->addMonth(),
                            ]);
                            
                            $empresa = $assinatura->empresa;
                            $empresa->ativo = true;
                            $empresa->save();
                        }
                    }
                }
            }
        }
    }

    protected function processSubscription($subscriptionId)
    {
        if (!$subscriptionId) return;

        $mpPreapproval = $this->mpService->getPreapproval($subscriptionId);
        
        if ($mpPreapproval) {
            $assinatura = Assinatura::where('mp_preapproval_id', $subscriptionId)
                                    ->orWhere('id', $mpPreapproval->external_reference)
                                    ->first();
            
            if ($assinatura) {
                $statusMapeado = match ($mpPreapproval->status) {
                    'authorized' => 'authorized',
                    'paused'     => 'paused',
                    'cancelled'  => 'cancelled',
                    default      => 'pending',
                };

                $assinatura->update([
                    'mp_preapproval_id' => $subscriptionId,
                    'status'            => $statusMapeado,
                ]);
                
                $empresa = $assinatura->empresa;

                if ($statusMapeado === 'authorized') {
                    $empresa->ativo = true;
                    $empresa->save();
                } elseif (in_array($statusMapeado, ['cancelled', 'paused'], true)) {
                    if (!$assinatura->valido_ate || $assinatura->valido_ate->isPast()) {
                        $empresa->ativo = false;
                        $empresa->save();
                    }
                }
            }
        }
    }
}
