<?php

namespace App\Services\MercadoPago;

use App\Models\Assinatura;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
     * Processa um pagamento via Checkout Bricks (Cartão, PIX, Boleto, etc.).
     * Endpoint: POST /v1/payments
     */
    public function criarPagamento(array $formData, Assinatura $assinatura, User $usuario, ?float $valorCalculado = null): array
    {
        $plano = $assinatura->plano;
        $token = $this->getAccessToken();

        $tipoPagamento = $formData['tipo_pagamento'] ?? 'mensal';
        $amount        = $valorCalculado ?? (float) $assinatura->preco_contratado;

        // Regra Estrita de Segurança e Negócio:
        // Assinaturas mensais PERMITEM APENAS 1 PARCELA (installments = 1).
        // Qualquer valor enviado pelo frontend para assinatura mensal é ignorado.
        $installments = ($tipoPagamento === 'mensal') ? 1 : (int) ($formData['installments'] ?? 1);

        $payload = [
            'transaction_amount' => $amount,
            'description'        => ($tipoPagamento === 'unico' ? 'Pagamento Único Anual' : 'Assinatura Mensal') . " MecDesk - Plano {$plano->nome}",
            'payment_method_id'  => $formData['payment_method_id'] ?? null,
            'external_reference' => (string) $assinatura->id,
            'installments'       => $installments,
            'payer'              => [
                'email' => $formData['payer']['email'] ?? $usuario->email,
            ],
            'notification_url'   => route('webhooks.mercadopago'),
        ];

        if (!empty($formData['token'])) {
            $payload['token'] = $formData['token'];
        }

        if (!empty($formData['issuer_id'])) {
            $payload['issuer_id'] = $formData['issuer_id'];
        }

        if (!empty($formData['payer']['identification']['type']) && !empty($formData['payer']['identification']['number'])) {
            $payload['payer']['identification'] = [
                'type'   => $formData['payer']['identification']['type'],
                'number' => $formData['payer']['identification']['number'],
            ];
        }

        if (!empty($formData['payer']['first_name'])) {
            $payload['payer']['first_name'] = $formData['payer']['first_name'];
        }

        if (!empty($formData['payer']['last_name'])) {
            $payload['payer']['last_name'] = $formData['payer']['last_name'];
        }

        // Chave de Idempotência para prevenir cobranças duplicadas em retentativas
        $idempotencyKey = (string) Str::uuid();

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Idempotency-Key' => $idempotencyKey,
            ])
            ->acceptJson()
            ->post("{$this->baseUrl}/v1/payments", $payload);

        if ($response->failed()) {
            Log::error('Erro ao criar pagamento no Mercado Pago via Bricks', [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'payload' => $payload,
            ]);

            throw new \RuntimeException('Falha no processamento do pagamento: ' . ($response->json('message') ?? $response->body()));
        }

        return $response->json();
    }

    /**
     * Consulta um Pagamento individual (Dupla Checagem Obrigatória de Segurança).
     * Endpoint: GET /v1/payments/{id}
     */
    public function consultarPagamento(string $paymentId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->get("{$this->baseUrl}/v1/payments/{$paymentId}");

        if ($response->failed()) {
            Log::error("Erro ao consultar pagamento {$paymentId} no Mercado Pago", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException("Não foi possível consultar o pagamento {$paymentId}");
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
}
