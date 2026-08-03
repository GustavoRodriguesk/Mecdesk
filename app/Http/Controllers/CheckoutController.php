<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Pagamento;
use App\Models\Plano;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        protected MercadoPagoService $mpService
    ) {}

    /**
     * Exibe a tela de checkout com o Checkout Bricks para a empresa contratar/ativar um plano.
     */
    public function show(Plano $plano, Request $request)
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

        $tipoPagamento = in_array($request->query('tipo'), ['mensal', 'unico'], true)
            ? $request->query('tipo')
            : 'mensal';

        $precoMensal = (float) $plano->preco_mensal;
        $precoUnico  = (float) $plano->getPrecoForTipo('unico');
        $amount      = $plano->getPrecoForTipo($tipoPagamento);

        // Obtém ou cria a assinatura pendente para a empresa
        $assinatura = Assinatura::firstOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'plano_id'         => $plano->id,
                'metodo_pagamento' => 'cartao',
                'status'           => 'pending',
                'preco_contratado' => $amount,
            ]
        );

        // Atualiza o plano e o preço contratado na assinatura
        $assinatura->update([
            'plano_id'         => $plano->id,
            'preco_contratado' => $amount,
        ]);
        $empresa->update(['plano_id' => $plano->id]);

        $publicKey = config('mercadopago.public_key');

        return view('planos.checkout', compact('plano', 'assinatura', 'publicKey', 'amount', 'precoMensal', 'precoUnico', 'tipoPagamento'));
    }

    /**
     * Processa a transação enviada pelo Checkout Bricks (POST AJAX).
     */
    public function processarPagamento(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'tipo_pagamento'    => 'nullable|string|in:mensal,unico',
        ]);

        $user = auth()->user();
        $empresa = $user->empresa;

        $assinatura = $empresa->assinaturaAtiva ?? $empresa->assinaturas()->latest()->first();

        if (!$assinatura) {
            return response()->json([
                'error'   => 'Assinatura não encontrada',
                'message' => 'Nenhuma assinatura cadastrada para esta empresa.',
            ], 404);
        }

        try {
            $plano = $assinatura->plano;
            $tipoPagamento = $request->input('tipo_pagamento', 'mensal');

            // CÁLCULO ESTRITO DE VALOR NO SERVIDOR (Zero-Trust)
            $valorCalculado = $plano->getPrecoForTipo($tipoPagamento);

            // Atualiza o preço contratado na assinatura
            $assinatura->update([
                'preco_contratado' => $valorCalculado,
            ]);

            $formData = $request->all();
            $formData['tipo_pagamento'] = $tipoPagamento;

            // Cria o pagamento no Mercado Pago utilizando valor estritamente do servidor
            $resultado = $this->mpService->criarPagamento($formData, $assinatura, $user, $valorCalculado);

            $mpPaymentId   = (string) ($resultado['id'] ?? '');
            $status        = $resultado['status'] ?? 'pending';
            $statusDetail  = $resultado['status_detail'] ?? null;
            $paymentMethod = ($resultado['payment_method_id'] ?? '') === 'pix' ? 'pix' : 'cartao';

            if (!empty($mpPaymentId)) {
                Pagamento::updateOrCreate(
                    ['mp_payment_id' => $mpPaymentId],
                    [
                        'assinatura_id'    => $assinatura->id,
                        'empresa_id'       => $empresa->id,
                        'metodo_pagamento' => $paymentMethod,
                        'status'           => $status,
                        'status_detail'    => $statusDetail,
                        'valor'            => $valorCalculado,
                        'data_pagamento'   => $status === 'approved' ? now() : null,
                        'payload_resposta' => $resultado,
                    ]
                );
            }

            // Ativação prévia se o pagamento for aprovado imediatamente (ex: Cartão)
            if ($status === 'approved') {
                $duracaoMeses = ($tipoPagamento === 'unico') ? 12 : 1;

                $assinatura->update([
                    'status'             => 'authorized',
                    'metodo_pagamento'   => $paymentMethod,
                    'data_inicio'        => $assinatura->data_inicio ?? now(),
                    'proximo_vencimento' => now()->addMonths($duracaoMeses),
                    'valido_ate'         => now()->addMonths($duracaoMeses),
                ]);

                $empresa->ativo = true;
                $empresa->save();
            }

            return response()->json($resultado, 200);

        } catch (\Throwable $e) {
            Log::error('Erro ao processar pagamento via Bricks: ' . $e->getMessage(), [
                'user_id'    => $user->id,
                'empresa_id' => $empresa->id,
            ]);

            return response()->json([
                'error'   => 'Falha no processamento do pagamento',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Callback de retorno após a conclusão do fluxo no checkout.
     */
    public function callback(Request $request)
    {
        return redirect()->route('assinatura.pendente')->with(
            'success',
            'Pagamento em processamento! Assim que o Mercado Pago confirmar a transação, seu acesso será liberado automaticamente.'
        );
    }
}
