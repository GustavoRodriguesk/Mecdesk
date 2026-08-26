<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MercadoPagoService $mpService
    ) {}

    /**
     * Exibe a tela de gerenciamento da assinatura atual da empresa autenticada.
     */
    public function index()
    {
        $user = auth()->user();
        $empresa = $user->empresa;
        $empresa?->refresh();

        // Prioridade: assinatura vigente (authorized ou pending); fallback para a última de qualquer status
        $assinatura = $empresa?->assinaturaVigente ?? $empresa?->assinaturas()->latest()->first();
        $plano = $empresa?->plano ?? Plano::where('slug', 'pro')->where('ativo', true)->first();

        return view('planos.minha-assinatura', [
            'empresa' => $empresa,
            'assinatura' => $assinatura,
            'plano' => $plano,
        ]);
    }

    /**
     * Cancela a assinatura recorrente da empresa autenticada.
     * Risco Zero de IDOR: Busca a assinatura estritamente da empresa do usuário logado.
     */
    public function cancelar(Request $request)
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        $assinatura = $empresa->assinaturas()
            ->whereIn('status', ['authorized', 'pending'])
            ->latest()
            ->first();

        if (! $assinatura || ! $assinatura->mp_preapproval_id) {
            return back()->with('error', 'Nenhuma assinatura ativa ou pendente foi encontrada para ser cancelada.');
        }

        try {
            // Invoca cancelamento na API do Mercado Pago (PUT /preapproval/{id} status=cancelled)
            $this->mpService->cancelarAssinatura($assinatura->mp_preapproval_id);

            // Atualiza status e data de cancelamento localmente
            $assinatura->update([
                'status' => 'cancelled',
                'data_cancelamento' => now(),
            ]);

            return back()->with('success', 'Sua assinatura foi cancelada com sucesso.');

        } catch (\Throwable $e) {
            Log::error("Erro ao cancelar assinatura da empresa #{$empresa->id}: ".$e->getMessage());

            return back()->with('error', 'Não foi possível cancelar a assinatura no momento: '.$e->getMessage());
        }
    }
}
