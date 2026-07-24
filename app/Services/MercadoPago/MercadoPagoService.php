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

    protected function getPayerEmail(User $usuario): string
    {
        $token = $this->getAccessToken();
        
        // Se o token for de Teste (TEST-) ou se o e-mail for o mesmo do vendedor
        if (str_starts_with($token, 'TEST-') || str_contains(strtolower($usuario->email), 'gustavorodrig780')) {
            return 'test_user_10000000@testuser.com';
        }

        return $usuario->email;
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
            'payer_email'        => $this->getPayerEmail($usuario),
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
     * Cria uma Cobrança PIX via Checkout Básico (Preference).
     * Funciona com chaves APP_USR sem necessidade de homologação.
     * Endpoint: POST /checkout/preferences
     */
    public function criarCobrancaPix(Assinatura $assinatura, User $usuario): array
    {
        $plano = $assinatura->plano;
        $token = $this->getAccessToken();
        $isSandbox = str_starts_with($token, 'TEST-');

        $payload = [
            'items' => [
                [
                    'title'       => "MecDesk - Mensalidade Plano {$plano->nome}",
                    'quantity'    => 1,
                    'unit_price'  => (float) $assinatura->preco_contratado,
                    'currency_id' => 'BRL',
                ]
            ],
            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                    ['id' => 'prepaid_card'],
                    ['id' => 'ticket'],
                    ['id' => 'atm'],
                ],
            ],
            'payer' => [
                'email' => $this->getPayerEmail($usuario),
            ],
            'back_urls' => [
                'success' => config('mercadopago.back_url'),
                'pending' => config('mercadopago.back_url'),
                'failure' => config('mercadopago.back_url'),
            ],
            'external_reference' => (string) $assinatura->id,
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->post("{$this->baseUrl}/checkout/preferences", $payload);

        if ($response->failed()) {
            Log::error('Erro ao gerar preferência PIX no Mercado Pago', [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'payload' => $payload,
            ]);

            throw new \RuntimeException('Falha ao gerar QR Code PIX: ' . ($response->json('message') ?? $response->body()));
        }

        $data = $response->json();

        // Em sandbox usa sandbox_init_point; em produção usa init_point
        $checkoutUrl = $isSandbox
            ? ($data['sandbox_init_point'] ?? $data['init_point'])
            : ($data['init_point'] ?? null);

        return [
            'id'              => $data['id'] ?? '',
            'status'          => 'pending',
            'status_detail'   => null,
            'valor'           => $assinatura->preco_contratado,
            'qr_code'         => null,
            'qr_code_base64'  => null,
            'ticket_url'      => $checkoutUrl,
            'checkout_url'    => $checkoutUrl,
            'data_vencimento' => null,
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
