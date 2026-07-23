<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessarWebhookMercadoPago;
use App\Models\WebhookLog;
use App\Services\MercadoPago\WebhookValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Endpoint oficial para recebimento de Webhooks do Mercado Pago.
     * POST /api/webhooks/mercadopago ou /webhooks/mercadopago
     */
    public function handle(Request $request): JsonResponse
    {
        $payload    = $request->all();
        $action     = $request->input('action') ?? $request->input('type') ?? 'unknown';
        $resourceId = (string) ($request->input('data.id') ?? $request->input('id') ?? '');

        if (empty($resourceId)) {
            return response()->json(['message' => 'Evento sem ID de recurso ignorado'], 200);
        }

        // 1. Registra o Webhook bruto para auditoria antes de processar
        $webhookLog = WebhookLog::create([
            'event_id'    => $request->input('id'),
            'action'      => $action,
            'resource_id' => $resourceId,
            'payload'     => $payload,
            'signature'   => $request->header('x-signature'),
            'processed'   => false,
        ]);

        // 2. Valida a autenticidade HMAC SHA-256 do webhook (Prevenção de Falsificação)
        if (!WebhookValidator::validate($request)) {
            Log::warning("Webhook retido por falha na assinatura HMAC (IP: {$request->ip()})", [
                'webhook_log_id' => $webhookLog->id,
                'resource_id'    => $resourceId,
            ]);

            $webhookLog->update([
                'error' => 'Assinatura x-signature inválida ou não autorizada',
            ]);

            if (!app()->environment(['local', 'testing'])) {
                return response()->json(['message' => 'Assinatura inválida'], 401);
            }
        }

        // 3. Despacha Job Assíncrono para processamento com tolerância a falhas
        ProcessarWebhookMercadoPago::dispatch($webhookLog->id);

        // 4. Retorna HTTP 200 OK imediatamente para o Mercado Pago não expirar a requisição
        return response()->json([
            'status'  => 'received',
            'log_id' => $webhookLog->id,
        ], 200);
    }
}
