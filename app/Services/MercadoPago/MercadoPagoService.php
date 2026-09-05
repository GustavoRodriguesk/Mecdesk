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
     * Mapeia códigos de erro do Mercado Pago para mensagens amigáveis ao usuário.
     */
    protected function mapearErroAmigavel(string $codigoErro): string
    {
        $mapeamento = [
            // Erros de rejeição de cartão
            'cc_rejected_other_reason' => 'Cartão inválido ou dados incorretos. Verifique o número, CVV e data de vencimento.',
            'cc_rejected_call_for_authorize' => 'Cartão recusado pelo seu banco. Contate seu banco para autorizar a transação.',
            'cc_rejected_insufficient_amount' => 'Saldo insuficiente no cartão.',
            'cc_rejected_insufficient_funds' => 'Fundos insuficientes no cartão.',
            'cc_rejected_bad_filled_security_code' => 'CVV (código de segurança) inválido.',
            'cc_rejected_bad_filled_form' => 'Dados do cartão preenchidos incorretamente.',
            'cc_rejected_bad_filled_date' => 'Data de vencimento do cartão inválida.',
            'cc_rejected_high_risk' => 'Transação bloqueada por motivos de segurança. Contate seu banco.',
            'cc_rejected_invalid_installments' => 'Número de parcelas inválido para este cartão.',
            'cc_rejected_3dsecure_mandatory' => 'Este cartão requer autenticação 3D Secure.',
            'cc_rejected_duplicated' => 'Esta transação foi detectada como duplicada.',
            'card_disabled' => 'Cartão desabilitado. Contate seu banco.',
            'card_not_supported' => 'Este cartão não é aceito para transações online.',

            // Erros de token
            'invalid_token' => 'Token de cartão inválido ou expirado. Tente novamente.',
            'token_invalid' => 'Token de cartão inválido.',
            'token_expired' => 'Sessão expirada. Tente novamente.',

            // Erros de validação
            'invalid_card_number' => 'Número do cartão inválido.',
            'invalid_security_code' => 'Código de segurança inválido.',
            'invalid_expiration_date' => 'Data de vencimento inválida.',
            'invalid_cardholder_name' => 'Nome do titular do cartão inválido.',

            // Erros de usuário/ambiente
            'payer_not_found' => 'Usuário não encontrado. Verifique seu e-mail.',
            'collector_not_found' => 'Erro de configuração. Contate o suporte.',
            'both_payer_and_collector_must_be_real_or_test_users' => 'Erro de configuração no sistema. Contate o suporte.',

            // Erros de assinatura
            'invalid_preapproval_id' => 'ID de assinatura inválido.',
            'preapproval_not_found' => 'Assinatura não encontrada.',
            'preapproval_already_cancelled' => 'Assinatura já foi cancelada.',

            // Erros gerais
            'resource_not_found' => 'Recurso não encontrado.',
            'too_many_requests' => 'Muitas requisições. Tente novamente em alguns segundos.',
            'internal_server_error' => 'Erro no servidor. Tente novamente mais tarde.',
            'service_unavailable' => 'Serviço indisponível. Tente novamente mais tarde.',
        ];

        return $mapeamento[$codigoErro] ?? 'Erro ao processar pagamento. Tente novamente ou use outro cartão.';
    }

    /**
     * Cria uma assinatura recorrente via Mercado Pago Subscriptions API (/preapproval).
     * Endpoint: POST /preapproval
     */
    public function criarAssinatura(Empresa $empresa, User $usuario, string $cardTokenId, string $idempotencyKey): array
    {
        $token = $this->getAccessToken();

        $planoPro = \App\Models\Plano::where('slug', 'pro')->where('ativo', true)->first();
        $valorMensal = $planoPro ? (float) $planoPro->preco_mensal : 89.90;

        $payload = [
            'reason' => 'Assinatura MecDesk - Plano Pro',
            'external_reference' => (string) $empresa->id,
            'payer_email' => $usuario->email,
            'card_token_id' => $cardTokenId,
            'auto_recurring' => [
                'frequency' => 1,
                'frequency_type' => 'months',
                'transaction_amount' => $valorMensal,
                'currency_id' => 'BRL',
            ],
            'back_url' => route('planos.callback'),
            'status' => 'authorized',
        ];

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Idempotency-Key' => $idempotencyKey,
            ])
            ->acceptJson()
            ->post("{$this->baseUrl}/preapproval", $payload);

        if ($response->failed()) {
            Log::error('Erro ao criar assinatura no Mercado Pago via /preapproval', [
                'status' => $response->status(),
                'body' => $response->json(),
                'payload' => $payload,
            ]);

            // Extrai o código de erro específico
            $codigoErro = $response->json('code') ?? 'unknown';
            $mensagemAmigavel = $this->mapearErroAmigavel($codigoErro);

            throw new \RuntimeException($mensagemAmigavel);
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
                'body' => $response->json(),
            ]);

            $codigoErro = $response->json('code') ?? 'unknown';
            $mensagemAmigavel = $this->mapearErroAmigavel($codigoErro);

            throw new \RuntimeException($mensagemAmigavel);
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
                'body' => $response->json(),
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
                'body' => $response->json(),
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
                'body' => $response->json(),
            ]);

            throw new \RuntimeException("Não foi possível consultar o pagamento autorizado {$authorizedPaymentId}");
        }

        return $response->json();
    }
}
