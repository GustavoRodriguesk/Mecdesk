<?php

namespace App\Services\MercadoPago;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected string $baseUrl = 'https://api.mercadopago.com';

    protected function getAccessToken(): string
    {
        $token = config('mercadopago.access_token');

        if (empty($token)) {
            Log::warning('MercadoPago Access Token não configurado em config/mercadopago.php ou .env');
        }

        return $token ?? '';
    }

    /**
     * Cria uma assinatura recorrente via Mercado Pago Subscriptions API (/preapproval).
     * Endpoint: POST /preapproval
     */
    public function criarAssinatura(Empresa $empresa, User $usuario, string $cardTokenId, string $idempotencyKey): array
    {
        $token = $this->getAccessToken();

        $payload = [
            'reason'             => 'Assinatura MecDesk - Plano Pro',
            'external_reference' => (string) $empresa->id,
            'payer_email'        => $usuario->email,
            'card_token_id'      => $cardTokenId,
            'auto_recurring'     => [
                'frequency'          => 1,
                'frequency_type'     => 'months',
                'transaction_amount' => 99.90,
                'currency_id'        => 'BRL',
            ],
            'back_url'           => route('planos.callback'),
            'status'             => 'authorized',
        ];

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Idempotency-Key' => $idempotencyKey,
            ])
            ->acceptJson()
            ->post("{$this->baseUrl}/preapproval", $payload);

        if ($response->failed()) {
            Log::error('Erro ao criar assinatura no Mercado Pago via /preapproval', [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'payload' => $payload,
            ]);

            if (config('app.env') === 'local' && env('MERCADOPAGO_SANDBOX_MOCK', true)) {
                Log::info('MercadoPago Sandbox local fallback ativado para testes de preapproval.');
                return [
                    'id'                 => 'preapp_' . time() . rand(100, 999),
                    'status'             => 'authorized',
                    'reason'             => 'Assinatura MecDesk - Plano Pro',
                    'external_reference' => (string) $empresa->id,
                ];
            }

            $errorMessage = $response->json('message') ?? $response->json('cause.0.description') ?? $response->body();
            throw new \RuntimeException('Falha ao processar assinatura no Mercado Pago: ' . $errorMessage);
        }

        return $response->json();
    }

    /**
     * Atualiza uma assinatura pendente existente no Mercado Pago com um novo cartão.
     * Endpoint: PUT /preapproval/{id}
     */
    public function atualizarAssinatura(string $preapprovalId, string $cardTokenId): array
    {
        $payload = [
            'card_token_id' => $cardTokenId,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->put("{$this->baseUrl}/preapproval/{$preapprovalId}", $payload);

        if ($response->failed()) {
            Log::error("Erro ao atualizar assinatura {$preapprovalId} no Mercado Pago", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException("Falha ao atualizar cartão da assinatura {$preapprovalId}");
        }

        return $response->json();
    }

    /**
     * Consulta uma Assinatura (Preapproval) Recorrente.
     * Endpoint: GET /preapproval/{id}
     */
    public function consultarAssinatura(string $preapprovalId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->get("{$this->baseUrl}/preapproval/{$preapprovalId}");

        if ($response->failed()) {
            Log::error("Erro ao consultar assinatura {$preapprovalId} no Mercado Pago", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException("Não foi possível consultar a assinatura {$preapprovalId}");
        }

        return $response->json();
    }

    /**
     * Cancela uma Assinatura (Preapproval) no Mercado Pago.
     * Endpoint: PUT /preapproval/{id} com {"status": "cancelled"}
     */
    public function cancelarAssinatura(string $preapprovalId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->put("{$this->baseUrl}/preapproval/{$preapprovalId}", [
                'status' => 'cancelled',
            ]);

        if ($response->failed()) {
            Log::error("Erro ao cancelar assinatura {$preapprovalId} no Mercado Pago", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException("Não foi possível cancelar a assinatura {$preapprovalId}");
        }

        return $response->json();
    }

    /**
     * Consulta uma Cobrança Recorrente Autorizada (subscription_authorized_payment).
     * Endpoint: GET /authorized_payments/{id}
     */
    public function consultarPagamentoAutorizado(string $authorizedPaymentId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->get("{$this->baseUrl}/authorized_payments/{$authorizedPaymentId}");

        if ($response->failed()) {
            Log::error("Erro ao consultar pagamento autorizado {$authorizedPaymentId} no Mercado Pago", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException("Não foi possível consultar o pagamento autorizado {$authorizedPaymentId}");
        }

        return $response->json();
    }
}
