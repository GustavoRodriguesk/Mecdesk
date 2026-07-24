<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Pagamento;
use App\Models\Plano;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        protected MercadoPagoService $mpService
    ) {}

    /**
     * Exibe a tela de checkout para a empresa contratar/ativar um plano.
     */
    public function show(Plano $plano)
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        // Se o plano for free, ativa a empresa imediatamente sem checkout
        if ($plano->slug === 'free') {
            $empresa->update(['plano_id' => $plano->id]);

            $assinatura = $empresa->assinaturas()->firstOrCreate(
                ['plano_id' => $plano->id],
                [
                    'metodo_pagamento' => 'free',
                    'status'           => 'authorized',
                    'preco_contratado' => 0.00,
                    'data_inicio'      => now(),
                ]
            );

            $assinatura->update([
                'status'           => 'authorized',
                'metodo_pagamento' => 'free',
            ]);

            $empresa->ativo = true;
            $empresa->save();

            return redirect()->route('dashboard')->with('success', 'Plano Free ativado com sucesso!');
        }

        // Obtém ou cria a assinatura pendente para a empresa
        $assinatura = Assinatura::firstOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'plano_id'         => $plano->id,
                'metodo_pagamento' => 'cartao',
                'status'           => 'pending',
                'preco_contratado' => $plano->preco_mensal,
            ]
        );

        // Atualiza o plano e o preço contratado na assinatura
        $assinatura->update([
            'plano_id'         => $plano->id,
            'preco_contratado' => $plano->preco_mensal,
        ]);
        $empresa->update(['plano_id' => $plano->id]);

        return view('planos.checkout', compact('plano', 'assinatura'));
    }

    /**
     * Inicia o fluxo de Assinatura Recorrente em Cartão de Crédito no Mercado Pago.
     */
    public function assinarCartao(Request $request)
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        $assinatura = $empresa->assinaturaAtiva ?? $empresa->assinaturas()->latest()->first();

        if (!$assinatura) {
            return redirect()->route('planos.upgrade')->with('error', 'Assinatura não encontrada.');
        }

        try {
            $resultado = $this->mpService->criarAssinaturaCartao($assinatura, $user);

            if (!empty($resultado['id'])) {
                $assinatura->update([
                    'mp_preapproval_id' => $resultado['id'],
                    'metodo_pagamento'   => 'cartao',
                ]);
            }

            if (!empty($resultado['init_point'])) {
                return redirect()->away($resultado['init_point']);
            }

            return redirect()->back()->with('error', 'Não foi possível gerar a URL de pagamento do Mercado Pago.');
        } catch (\Throwable $e) {
            Log::error('Erro ao processar assinatura em cartão: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao conectar ao Mercado Pago: ' . $e->getMessage());
        }
    }

    /**
     * Gera a cobrança por ciclo via PIX (obtendo QR Code na hora, sem salvar no banco).
     */
    public function gerarPix(Request $request)
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        $assinatura = $empresa->assinaturaAtiva ?? $empresa->assinaturas()->latest()->first();

        if (!$assinatura) {
            return redirect()->route('planos.upgrade')->with('error', 'Assinatura não encontrada.');
        }

        try {
            $resultadoPix = $this->mpService->criarCobrancaPix($assinatura, $user);

            // Grava os metadados da preferência gerada
            Pagamento::updateOrCreate(
                ['mp_payment_id' => $resultadoPix['id']],
                [
                    'assinatura_id'    => $assinatura->id,
                    'empresa_id'       => $empresa->id,
                    'metodo_pagamento' => 'pix',
                    'status'           => 'pending',
                    'status_detail'    => null,
                    'valor'            => $resultadoPix['valor'],
                    'data_vencimento'  => now()->addDays(3),
                ]
            );

            $assinatura->update(['metodo_pagamento' => 'pix']);

            // Redireciona direto para o checkout do Mercado Pago (onde o usuário escolhe PIX)
            $checkoutUrl = $resultadoPix['checkout_url'] ?? $resultadoPix['ticket_url'];

            if ($checkoutUrl) {
                return redirect()->away($checkoutUrl);
            }

            return redirect()->back()->with('error', 'Não foi possível gerar o link de pagamento PIX.');
        } catch (\Throwable $e) {
            Log::error('Erro ao gerar PIX: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar cobrança PIX: ' . $e->getMessage());
        }
    }

    /**
     * Callback de retorno após o pagamento no checkout do Mercado Pago.
     */
    public function callback(Request $request)
    {
        return redirect()->route('assinatura.pendente')->with(
            'success',
            'Pagamento em processamento! Assim que o Mercado Pago confirmar a transação, seu acesso será liberado automaticamente.'
        );
    }
}
