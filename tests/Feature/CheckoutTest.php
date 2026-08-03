<?php

use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\User;
use App\Services\MercadoPago\MercadoPagoService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->planoPro = Plano::create([
        'slug'         => 'pro',
        'nome'         => 'Pro',
        'preco_mensal' => 99.00,
        'ativo'        => true,
    ]);

    $this->planoFree = Plano::create([
        'slug'         => 'free',
        'nome'         => 'Free',
        'preco_mensal' => 0.00,
        'ativo'        => true,
    ]);

    $this->empresa = new Empresa([
        'nome_fantasia' => 'Oficina Checkout',
        'plano_id'      => $this->planoPro->id,
    ]);
    $this->empresa->ativo = false;
    $this->empresa->save();

    $this->assinatura = Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'pending',
        'preco_contratado' => 99.00,
    ]);

    $this->user = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);
});

test('selecting free plan activates company immediately', function () {
    $response = $this->actingAs($this->user)->get(route('checkout.show', 'free'));

    $response->assertRedirect(route('dashboard'))
        ->assertSessionHas('success');

    $this->empresa->refresh();
    expect($this->empresa->ativo)->toBeTrue();
});

test('checkout show renders payment brick page for paid plans', function () {
    $response = $this->actingAs($this->user)->get(route('checkout.show', 'pro'));

    $response->assertStatus(200)
        ->assertSee('paymentBrick_container')
        ->assertSee('sdk.mercadopago.com/js/v2');
});

test('processarPagamento endpoint creates payment using server side amount and returns json', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => Http::response([
            'id'                 => 555444333,
            'status'             => 'approved',
            'status_detail'      => 'accredited',
            'payment_method_id'  => 'visa',
            'transaction_amount' => 99.00,
        ], 201),
    ]);

    $payload = [
        'token'             => 'FF8080814C122709014C2A1E6589083F',
        'payment_method_id' => 'visa',
        'installments'      => 1,
        'issuer_id'         => '25',
        'payer'             => [
            'email' => 'cliente@testuser.com',
        ],
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 555444333,
            'status' => 'approved',
        ]);

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '555444333',
        'status'        => 'approved',
        'valor'         => 99.00,
    ]);

    $this->empresa->refresh();
    expect($this->empresa->ativo)->toBeTrue();
});

test('artisan command renovar assinaturas pix generates next cycle payments via bricks service', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => Http::response([
            'id'                 => 888777666,
            'status'             => 'pending',
            'transaction_amount' => 99.00,
        ], 201),
    ]);

    $assinatura = Assinatura::create([
        'empresa_id'         => $this->empresa->id,
        'plano_id'           => $this->planoPro->id,
        'metodo_pagamento'   => 'pix',
        'status'             => 'authorized',
        'preco_contratado'   => 99.00,
        'data_inicio'        => now()->subDays(25),
        'valido_ate'         => now()->addDays(2),
        'proximo_vencimento' => now()->addDays(2),
    ]);

    $this->artisan('mecdesk:renovar-assinaturas-pix')
        ->assertExitCode(0);

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '888777666',
        'status'        => 'pending',
        'valor'         => 99.00,
    ]);
});
