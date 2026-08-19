<?php

namespace App\Console\Commands;

use App\Models\Assinatura;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerificarAssinaturasVencidasCommand extends Command
{
    /**
     * O nome e a assinatura do comando no console.
     */
    protected $signature = 'mecdesk:verificar-assinaturas-vencidas';

    /**
     * A descrição do comando.
     */
    protected $description = 'Verifica assinaturas ativas com vencimento + carência (3 dias) ultrapassados e as marca como overdue, desativando o acesso da empresa.';

    public function handle(): int
    {
        $this->info('Iniciando verificação de assinaturas vencidas...');

        // Filtra estritamente assinaturas ativas (status = authorized) cuja data valido_ate
        // com o acréscimo dos 3 dias de carência seja anterior ou igual ao momento atual.
        $assinaturasVencidas = Assinatura::where('status', 'authorized')
            ->whereNotNull('valido_ate')
            ->where('valido_ate', '<=', now()->subDays(3))
            ->get();

        $count = 0;

        foreach ($assinaturasVencidas as $assinatura) {
            $assinatura->update([
                'status' => 'overdue',
            ]);

            $empresa = $assinatura->empresa;
            if ($empresa) {
                $empresa->ativo = false;
                $empresa->save();
            }

            Log::info("Assinatura #{$assinatura->id} da Empresa #{$assinatura->empresa_id} marcada como overdue após prazo de carência de 3 dias.");
            $count++;
        }

        $this->info("Verificação concluída. {$count} assinatura(s) transicionada(s) para overdue.");

        return Command::SUCCESS;
    }
}
