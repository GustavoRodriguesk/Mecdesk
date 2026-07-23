<?php

namespace App\Services\MercadoPago;

use Illuminate\Http\Request;

class WebhookValidator
{
    /**
     * Valida a assinatura do Webhook recebido do Mercado Pago (Header x-signature).
     */
    public static function validate(Request $request): bool
    {
        $secret = config('mercadopago.webhook_secret');

        // Se nenhuma chave secreta estiver configurada em desenvolvimento/testes, valida se estiver em ambiente local ou testing
        if (empty($secret)) {
            return app()->environment(['local', 'testing']);
        }

        $signatureHeader = $request->header('x-signature');
        $requestId = $request->header('x-request-id');

        if (!$signatureHeader || !$requestId) {
            return false;
        }

        $ts = null;
        $v1 = null;

        $parts = explode(',', $signatureHeader);
        foreach ($parts as $part) {
            $keyValue = explode('=', trim($part), 2);
            if (count($keyValue) === 2) {
                if ($keyValue[0] === 'ts') {
                    $ts = $keyValue[1];
                } elseif ($keyValue[0] === 'v1') {
                    $v1 = $keyValue[1];
                }
            }
        }

        if (!$ts || !$v1) {
            return false;
        }

        $dataId = $request->input('data.id') ?? $request->input('id');

        if (!$dataId) {
            return false;
        }

        $manifest = "id:{$dataId};request-id:{$requestId};ts:{$ts};";
        $expectedHash = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($expectedHash, $v1);
    }
}
