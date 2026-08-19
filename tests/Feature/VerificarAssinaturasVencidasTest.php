<?php

use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->planoPro = Plano::updateOrCreate(
        ['slug' => 'pro'],
        [
            'nome'         => 'Pro',
            'preco_mensal' => 99.90,
            'ativo'        => true,
        ]
    );

    $this->empresa = Empresa::create([
        'nome_fantasia' => 'Oficina Cron Test',
        'plano_id'      => $this->planoPro->id,
    ]);
    $this->empresa->ativo = true;
    $this->empresa->save();
});

test('verificar assinaturas vencidas command transitions overdue subscriptions after 3 days of grace period', function () {
    Carbon::setTestNow('2026-08-14 12:00:00');

    // Assinatura vencida há 4 dias (valido_ate = 2026-08-10 12:00:00)
    $assinaturaVencida = Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'status'           => 'authorized',
        'preco_contratado' => 99.90,
        'valido_ate'       => Carbon::parse('2026-08-10 12:00:00'),
    ]);

    // Executa o comando artisan agendado
    $this->artisan('mecdesk:verificar-assinaturas-vencidas')
        ->assertSuccessful();

    $this->empresa->refresh();
    $assinaturaVencida->refresh();

    expect($assinaturaVencida->status)->toBe('overdue')
        ->and($this->empresa->ativo)->toBeFalse();
});

test('verificar assinaturas vencidas command does not affect subscriptions within grace period', function () {
    Carbon::setTestNow('2026-08-14 12:00:00');

    // Assinatura vencida há apenas 1 dia (valido_ate = 2026-08-13 12:00:00 - dentro da carência de 3 dias)
    $assinaturaEmCarencia = Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'status'           => 'authorized',
        'preco_contratado' => 99.90,
        'valido_ate'       => Carbon::parse('2026-08-13 12:00:00'),
    ]);

    $this->artisan('mecdesk:verificar-assinaturas-vencidas')
        ->assertSuccessful();

    $this->empresa->refresh();
    $assinaturaEmCarencia->refresh();

    expect($assinaturaEmCarencia->status)->toBe('authorized')
        ->and($this->empresa->ativo)->toBeTrue();
});
