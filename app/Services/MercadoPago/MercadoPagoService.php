<?php

namespace App\Services\MercadoPago;

use App\Models\Assinatura;
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
     * Cria uma Assinatura Recorrente no Mercado Pago (Cartão de Crédito).
     * Endpoint: POST /preapproval
     */
    public function criarAssinaturaCartao(Assinatura $assinatura, User $usuario): array
    {
        $plano = $assinatura->plano;

        $payload = [
            'reason'             => "Assinatura MecDesk - Plano {$plano->nome}",
            'external_reference' => (string) $assinatura->id,
            'payer_email'        => $usuario->email,
            'back_url'           => config('mercadopago.back_url'),
            'auto_recurring'     => [
                'frequency'          => 1,
                'frequency_type'     => 'months',
                'transaction_amount' => (float) $assinatura->preco_contratado,
                'currency_id'        => 'BRL',
            ],
            'status'             => 'pending',
        ];

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->post("{$this->baseUrl}/preapproval", $payload);

        if ($response->failed()) {
            Log::error('Erro ao criar assinatura no Mercado Pago', [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'payload' => $payload,
            ]);

            throw new \RuntimeException('Falha na comunicação com o Mercado Pago: ' . ($response->json('message') ?? $response->body()));
        }

        $data = $response->json();

        return [
            'id'         => $data['id'] ?? null,
            'init_point' => $data['init_point'] ?? null,
            'status'     => $data['status'] ?? 'pending',
            'raw'        => $data,
        ];
    }

    /**
     * Cria uma Cobrança por Ciclo via PIX no Mercado Pago.
     * Endpoint: POST /v1/payments
     */
    public function criarCobrancaPix(Assinatura $assinatura, User $usuario): array
    {
        $plano = $assinatura->plano;
        $idempotencyKey = 'pix_sub_' . $assinatura->id . '_' . time();

        $payload = [
            'transaction_amount' => (float) $assinatura->preco_contratado,
            'description'        => "MecDesk - Mensalidade Plano {$plano->nome}",
            'payment_method_id'  => 'pix',
            'external_reference' => (string) $assinatura->id,
            'payer'              => [
                'email'      => $usuario->email,
                'first_name' => explode(' ', trim($usuario->name))[0],
                'last_name'  => implode(' ', array_slice(explode(' ', trim($usuario->name)), 1)) ?: 'Cliente',
            ],
        ];

        $response = Http::withToken($this->getAccessToken())
            ->withHeaders(['X-Idempotency-Key' => $idempotencyKey])
            ->acceptJson()
            ->post("{$this->baseUrl}/v1/payments", $payload);

        if ($response->failed()) {
            Log::error('Erro ao gerar cobrança PIX no Mercado Pago', [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'payload' => $payload,
            ]);

            throw new \RuntimeException('Falha ao gerar QR Code PIX: ' . ($response->json('message') ?? $response->body()));
        }

        $data = $response->json();
        $point = $data['point_of_interaction']['transaction_data'] ?? [];

        return [
            'id'              => (string) ($data['id'] ?? ''),
            'status'          => $data['status'] ?? 'pending',
            'status_detail'   => $data['status_detail'] ?? null,
            'valor'           => $data['transaction_amount'] ?? $assinatura->preco_contratado,
            'qr_code'         => $point['qr_code'] ?? null, // Retornado apenas para exibição imediata
            'qr_code_base64'  => $point['qr_code_base64'] ?? null, // Retornado apenas para exibição imediata
            'ticket_url'      => $point['ticket_url'] ?? null,
            'data_vencimento' => $data['date_of_expiration'] ?? null,
            'raw'             => $data,
        ];
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
