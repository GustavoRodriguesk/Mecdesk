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

        // Se o plano mudou no checkout, atualiza a assinatura pendente
        if ($assinatura->plano_id !== $plano->id) {
            $assinatura->update([
                'plano_id'         => $plano->id,
                'preco_contratado' => $plano->preco_mensal,
                'status'           => 'pending',
            ]);
            $empresa->update(['plano_id' => $plano->id]);
        }

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

            // Grava apenas os metadados do pagamento (Regra: NÃO grava QR Code no banco!)
            Pagamento::updateOrCreate(
                ['mp_payment_id' => $resultadoPix['id']],
                [
                    'assinatura_id'    => $assinatura->id,
                    'empresa_id'       => $empresa->id,
                    'metodo_pagamento' => 'pix',
                    'status'           => $resultadoPix['status'] ?? 'pending',
                    'status_detail'    => $resultadoPix['status_detail'] ?? null,
                    'valor'            => $resultadoPix['valor'],
                    'data_vencimento'  => $resultadoPix['data_vencimento'] ?? now()->addDays(3),
                ]
            );

            $assinatura->update(['metodo_pagamento' => 'pix']);

            $plano = $assinatura->plano;

            // Retorna a view com os dados do PIX passados temporariamente para exibição
            return view('planos.pix', [
                'plano'          => $plano,
                'assinatura'     => $assinatura,
                'qr_code'        => $resultadoPix['qr_code'],
                'qr_code_base64' => $resultadoPix['qr_code_base64'],
                'ticket_url'     => $resultadoPix['ticket_url'],
                'valor'          => $resultadoPix['valor'],
            ]);
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
