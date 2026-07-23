<?php

use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\WebhookLog;
use App\Jobs\ProcessarWebhookMercadoPago;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Support\Facades\Http;

test('webhook endpoint accepts payload and logs notification', function () {
    Http::fake([
        'https://api.mercadopago.com/*' => Http::response([
            'id'                 => '123456789',
            'status'             => 'pending',
            'transaction_amount' => 99.00,
        ], 200),
    ]);

    $response = $this->postJson('/webhooks/mercadopago', [
        'action' => 'payment.created',
        'data'   => ['id' => '123456789'],
    ]);

    $response->assertStatus(200)
        ->assertJson(['status' => 'received']);

    $this->assertDatabaseHas('webhook_logs', [
        'action'      => 'payment.created',
        'resource_id' => '123456789',
    ]);
});

test('processar webhook job updates subscription and activates company on approved payment', function () {
    $plano = Plano::create([
        'slug'         => 'pro',
        'nome'         => 'Pro',
        'preco_mensal' => 99.00,
        'ativo'        => true,
    ]);

    $empresa = new Empresa([
        'nome_fantasia' => 'Oficina Webhook',
        'plano_id'      => $plano->id,
    ]);
    $empresa->ativo = false;
    $empresa->save();

    $assinatura = Assinatura::create([
        'empresa_id'       => $empresa->id,
        'plano_id'         => $plano->id,
        'metodo_pagamento' => 'pix',
        'status'           => 'pending',
        'preco_contratado' => 99.00,
    ]);

    $log = WebhookLog::create([
        'action'      => 'payment.updated',
        'resource_id' => '999888777',
        'payload'     => ['id' => '999888777'],
        'processed'   => false,
    ]);

    $mpServiceMock = Mockery::mock(MercadoPagoService::class);
    $mpServiceMock->shouldReceive('consultarPagamento')
        ->with('999888777')
        ->once()
        ->andReturn([
            'id'                 => '999888777',
            'status'             => 'approved',
            'status_detail'      => 'accredited',
            'transaction_amount' => 99.00,
            'external_reference' => (string) $assinatura->id,
            'payment_method_id'  => 'pix',
        ]);

    $job = new ProcessarWebhookMercadoPago($log->id);
    $job->handle($mpServiceMock);

    $empresa->refresh();
    $assinatura->refresh();
    $log->refresh();

    expect($empresa->ativo)->toBeTrue()
        ->and($assinatura->status)->toBe('authorized')
        ->and($assinatura->valido_ate)->not->toBeNull()
        ->and($log->processed)->toBeTrue();

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '999888777',
        'status'        => 'approved',
        'valor'         => 99.00,
    ]);
});
