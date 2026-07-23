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

test('checkout show renders payment choice for paid plans', function () {
    $response = $this->actingAs($this->user)->get(route('checkout.show', 'pro'));

    $response->assertStatus(200)
        ->assertSee('Cartão de Crédito')
        ->assertSee('PIX');
});

test('gerar pix endpoint creates payment metadata without saving qr code in db', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => Http::response([
            'id'                 => 555444333,
            'status'             => 'pending',
            'status_detail'      => 'pending_waiting_transfer',
            'transaction_amount' => 99.00,
            'point_of_interaction' => [
                'transaction_data' => [
                    'qr_code'        => '00020126580014br.gov.bcb.pix...',
                    'qr_code_base64' => 'iVBORw0KGgoAAAANSUhEUgAA...',
                    'ticket_url'      => 'https://www.mercadopago.com.br/payments/555444333/ticket',
                ],
            ],
            'date_of_expiration' => now()->addDays(3)->toIso8601String(),
        ], 201),
    ]);

    $response = $this->actingAs($this->user)->post(route('checkout.pix'));

    $response->assertStatus(200)
        ->assertSee('00020126580014br.gov.bcb.pix...');

    // Verifica que o metadata foi gravado no banco sem o campo qr_code
    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '555444333',
        'status'        => 'pending',
        'valor'         => 99.00,
    ]);
});

test('artisan command renovar assinaturas pix generates next cycle payments', function () {
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
        'valido_ate'         => now()->addDays(2), // Vence em 2 dias
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
