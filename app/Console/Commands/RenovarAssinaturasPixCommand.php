<?php

namespace App\Console\Commands;

use App\Models\Assinatura;
use App\Models\Pagamento;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenovarAssinaturasPixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mecdesk:renovar-assinaturas-pix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifica assinaturas PIX próximas do vencimento e gera automaticamente as cobranças do novo ciclo.';

    /**
     * Execute the console command.
     */
    public function handle(MercadoPagoService $mpService): int
    {
        $this->info('Iniciando verificação de renovação de assinaturas PIX...');

        // Busca assinaturas PIX ativas cuja vigência vence nos próximos 5 dias
        $assinaturas = Assinatura::where('metodo_pagamento', 'pix')
            ->where('status', 'authorized')
            ->whereNotNull('valido_ate')
            ->where('valido_ate', '<=', now()->addDays(5))
            ->get();

        $this->info("Encontradas {$assinaturas->count()} assinaturas PIX para renovação.");

        foreach ($assinaturas as $assinatura) {
            try {
                $user = $assinatura->empresa->users()->where('role', 'admin')->first();

                if (!$user) {
                    $this->error("Empresa #{$assinatura->empresa_id} não possui administrador cadastrado.");
                    continue;
                }

                $resultadoPix = $mpService->criarCobrancaPix($assinatura, $user);

                // Registra apenas os metadados em pagamentos
                Pagamento::create([
                    'assinatura_id'    => $assinatura->id,
                    'empresa_id'       => $assinatura->empresa_id,
                    'mp_payment_id'    => $resultadoPix['id'],
                    'metodo_pagamento' => 'pix',
                    'status'           => $resultadoPix['status'] ?? 'pending',
                    'status_detail'    => $resultadoPix['status_detail'] ?? null,
                    'valor'            => $resultadoPix['valor'],
                    'data_vencimento'  => $resultadoPix['data_vencimento'] ?? now()->addDays(3),
                ]);

                $assinatura->update([
                    'proximo_vencimento' => now()->addMonth(),
                ]);

                $this->info("Cobrança PIX gerada com sucesso para empresa: {$assinatura->empresa->nome_fantasia}");
            } catch (\Throwable $e) {
                Log::error("Erro ao renovar assinatura PIX #{$assinatura->id}: " . $e->getMessage());
                $this->error("Falha ao renovar assinatura #{$assinatura->id}: " . $e->getMessage());
            }
        }

        $this->info('Processamento de renovações PIX finalizado!');

        return Command::SUCCESS;
    }
}
