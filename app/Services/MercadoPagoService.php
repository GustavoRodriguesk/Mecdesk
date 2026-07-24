<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Preapproval;
use Exception;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function __construct()
    {
        $token = config('mercadopago.access_token');
        if ($token) {
            SDK::setAccessToken($token);
        }
    }

    /**
     * Cria uma intenção de pagamento avulso (ex: fatura de Ordem de Serviço)
     */
    public function createPreference(array $data)
    {
        try {
            $preference = new Preference();

            // Item
            $item = new Item();
            $item->title = $data['title'];
            $item->quantity = $data['quantity'] ?? 1;
            $item->unit_price = (float) $data['price'];
            
            $preference->items = array($item);

            // Payer
            $payer = new Payer();
            $payer->email = $data['payer_email'];
            $preference->payer = $payer;

            // Back URLs
            $preference->back_urls = array(
                "success" => route('planos.callback'),
                "failure" => route('planos.callback'),
                "pending" => route('planos.callback')
            );
            $preference->auto_return = "approved";

            // External Reference (ID do pagamento local)
            $preference->external_reference = $data['external_reference'] ?? null;

            $preference->save();

            return $preference; // $preference->init_point tem a URL de checkout
        } catch (Exception $e) {
            Log::error('Erro ao criar Preference Mercado Pago: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cria um link de assinatura (Preapproval)
     */
    public function createPreapproval(array $data)
    {
        try {
            $preapproval = new Preapproval();
            
            $preapproval->reason = $data['reason'];
            $preapproval->external_reference = $data['external_reference']; // ID da Assinatura
            $preapproval->payer_email = $data['payer_email'];
            $preapproval->back_url = config('mercadopago.back_url') ?: config('app.url');

            $preapproval->auto_recurring = array(
                "frequency" => 1,
                "frequency_type" => "months",
                "transaction_amount" => (float) $data['transaction_amount'],
                "currency_id" => "BRL",
                "start_date" => (new \DateTime())->format('Y-m-d\TH:i:s.000P')
            );

            $preapproval->save();

            return $preapproval; // $preapproval->init_point tem a URL da assinatura
        } catch (Exception $e) {
            Log::error('Erro ao criar Preapproval Mercado Pago: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Busca um pagamento pelo ID
     */
    public function getPayment($paymentId)
    {
        try {
            return \MercadoPago\Payment::find_by_id($paymentId);
        } catch (Exception $e) {
            Log::error('Erro ao buscar Payment no Mercado Pago: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca uma assinatura pelo ID
     */
    public function getPreapproval($preapprovalId)
    {
        try {
            return Preapproval::find_by_id($preapprovalId);
        } catch (Exception $e) {
            Log::error('Erro ao buscar Preapproval no Mercado Pago: ' . $e->getMessage());
            return null;
        }
    }
}
