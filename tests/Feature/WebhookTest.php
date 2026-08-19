<?php

use App\Events\PagamentoRecusado;
use App\Jobs\ProcessarWebhookMercadoPago;
use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\WebhookLog;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

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
        'nome_fantasia' => 'Oficina Webhook',
        'plano_id'      => $this->planoPro->id,
        'ativo'         => false,
    ]);

    $this->assinatura = Assinatura::create([
        'empresa_id'        => $this->empresa->id,
        'plano_id'          => $this->planoPro->id,
        'metodo_pagamento'  => 'cartao',
        'status'            => 'pending',
        'mp_preapproval_id' => 'preapp_wh_123',
        'preco_contratado'  => 99.90,
    ]);
});

test('webhook endpoint accepts subscription payload and logs notification', function () {
    Http::fake([
        'https://api.mercadopago.com/*' => Http::response([
            'id'                 => 'preapp_wh_123',
            'status'             => 'authorized',
            'external_reference' => (string) $this->empresa->id,
        ], 200),
    ]);

    $response = $this->postJson('/webhooks/mercadopago', [
        'action' => 'subscription_preapproval',
        'data'   => ['id' => 'preapp_wh_123'],
    ]);

    $response->assertStatus(200)
        ->assertJson(['status' => 'received']);

    $this->assertDatabaseHas('webhook_logs', [
        'action'      => 'subscription_preapproval',
        'resource_id' => 'preapp_wh_123',
    ]);
});

test('webhook job processes subscription_preapproval and activates subscription', function () {
    $log = WebhookLog::create([
        'action'      => 'subscription_preapproval',
        'resource_id' => 'preapp_wh_123',
        'payload'     => ['id' => 'preapp_wh_123'],
        'processed'   => false,
    ]);

    $mpServiceMock = Mockery::mock(MercadoPagoService::class);
    $mpServiceMock->shouldReceive('consultarAssinatura')
        ->with('preapp_wh_123')
        ->once()
        ->andReturn([
            'id'                 => 'preapp_wh_123',
            'status'             => 'authorized',
            'external_reference' => (string) $this->empresa->id,
        ]);

    $job = new ProcessarWebhookMercadoPago($log->id);
    $job->handle($mpServiceMock);

    $this->empresa->refresh();
    $this->assinatura->refresh();

    expect($this->empresa->ativo)->toBeTrue()
        ->and($this->assinatura->status)->toBe('authorized');
});

test('subscription_preapproval returning authorized does NOT revert local overdue status to authorized', function () {
    // Assinatura marcada como overdue por regra interna
    $this->assinatura->update([
        'status'     => 'overdue',
        'valido_ate' => now()->subDays(5),
    ]);
    $this->empresa->ativo = false;
    $this->empresa->save();

    $log = WebhookLog::create([
        'action'      => 'subscription_preapproval',
        'resource_id' => 'preapp_wh_123',
        'payload'     => ['id' => 'preapp_wh_123'],
        'processed'   => false,
    ]);

    $mpServiceMock = Mockery::mock(MercadoPagoService::class);
    $mpServiceMock->shouldReceive('consultarAssinatura')
        ->with('preapp_wh_123')
        ->once()
        ->andReturn([
            'id'                 => 'preapp_wh_123',
            'status'             => 'authorized', // MP envia authorized mesmo em falha de pagamento
            'external_reference' => (string) $this->empresa->id,
        ]);

    $job = new ProcessarWebhookMercadoPago($log->id);
    $job->handle($mpServiceMock);

    $this->empresa->refresh();
    $this->assinatura->refresh();

    // Garante que o status overdue não foi revertido e a empresa continuou inativa
    expect($this->empresa->ativo)->toBeFalse()
        ->and($this->assinatura->status)->toBe('overdue');
});

test('webhook job processes subscription_authorized_payment approved charge extending validity', function () {
    $log = WebhookLog::create([
        'action'      => 'subscription_authorized_payment',
        'resource_id' => 'auth_pay_999',
        'payload'     => ['id' => 'auth_pay_999'],
        'processed'   => false,
    ]);

    $mpServiceMock = Mockery::mock(MercadoPagoService::class);
    $mpServiceMock->shouldReceive('consultarPagamentoAutorizado')
        ->with('auth_pay_999')
        ->once()
        ->andReturn([
            'id'                 => 'auth_pay_999',
            'preapproval_id'     => 'preapp_wh_123',
            'transaction_amount' => 99.90,
            'payment'            => [
                'status'        => 'approved',
                'status_detail' => 'accredited',
            ],
        ]);

    $job = new ProcessarWebhookMercadoPago($log->id);
    $job->handle($mpServiceMock);

    $this->empresa->refresh();
    $this->assinatura->refresh();

    expect($this->empresa->ativo)->toBeTrue()
        ->and($this->assinatura->status)->toBe('authorized')
        ->and($this->assinatura->valido_ate)->not->toBeNull();

    $this->assertDatabaseHas('pagamentos', [
        'mp_authorized_payment_id' => 'auth_pay_999',
        'status'                   => 'approved',
        'valor'                    => 99.90,
    ]);
});

test('webhook job processes subscription_authorized_payment rejected charge and dispatches PagamentoRecusado event', function () {
    Event::fake([PagamentoRecusado::class]);

    $log = WebhookLog::create([
        'action'      => 'subscription_authorized_payment',
        'resource_id' => 'auth_pay_failed',
        'payload'     => ['id' => 'auth_pay_failed'],
        'processed'   => false,
    ]);

    $mpServiceMock = Mockery::mock(MercadoPagoService::class);
    $mpServiceMock->shouldReceive('consultarPagamentoAutorizado')
        ->with('auth_pay_failed')
        ->once()
        ->andReturn([
            'id'                 => 'auth_pay_failed',
            'preapproval_id'     => 'preapp_wh_123',
            'transaction_amount' => 99.90,
            'payment'            => [
                'status'        => 'rejected',
                'status_detail' => 'cc_rejected_insufficient_amount',
            ],
        ]);

    $job = new ProcessarWebhookMercadoPago($log->id);
    $job->handle($mpServiceMock);

    $this->assertDatabaseHas('pagamentos', [
        'mp_authorized_payment_id' => 'auth_pay_failed',
        'status'                   => 'rejected',
    ]);

    Event::assertDispatched(PagamentoRecusado::class);
});
