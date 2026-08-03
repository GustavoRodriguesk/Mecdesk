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
        'preco_unico'  => 950.00,
        'ativo'        => true,
    ]);

    $this->planoFree = Plano::create([
        'slug'         => 'free',
        'nome'         => 'Free',
        'preco_mensal' => 0.00,
        'preco_unico'  => 0.00,
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

test('monthly subscription forces installments=1 in Mercado Pago API request even if client requests 12', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => function ($request) {
            $data = $request->data();
            // Garante que o backend forçou a mensalidade para installments = 1
            if ($data['installments'] === 1 && $data['transaction_amount'] == 99.00) {
                return Http::response([
                    'id'                 => 111222333,
                    'status'             => 'approved',
                    'status_detail'      => 'accredited',
                    'payment_method_id'  => 'visa',
                    'transaction_amount' => 99.00,
                ], 201);
            }
            return Http::response(['message' => 'Invalid installments or amount'], 400);
        },
    ]);

    $payload = [
        'token'             => 'FF8080814C122709014C2A1E6589083F',
        'payment_method_id' => 'visa',
        'installments'      => 12, // Tentativa do cliente de parcelar mensalidade em 12x
        'tipo_pagamento'    => 'mensal',
        'payer'             => ['email' => 'cliente@testuser.com'],
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 111222333,
            'status' => 'approved',
        ]);

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '111222333',
        'status'        => 'approved',
        'valor'         => 99.00,
    ]);
});

test('single payment option uses server side preco_unico and allows installments', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => function ($request) {
            $data = $request->data();
            // Garante que o valor utilizado foi o preco_unico (950.00) e permitiu 6x
            if ($data['installments'] === 6 && $data['transaction_amount'] == 950.00) {
                return Http::response([
                    'id'                 => 444555666,
                    'status'             => 'approved',
                    'status_detail'      => 'accredited',
                    'payment_method_id'  => 'master',
                    'transaction_amount' => 950.00,
                ], 201);
            }
            return Http::response(['message' => 'Invalid parameters'], 400);
        },
    ]);

    $payload = [
        'token'             => 'FF8080814C122709014C2A1E6589083F',
        'payment_method_id' => 'master',
        'installments'      => 6,
        'tipo_pagamento'    => 'unico',
        'payer'             => ['email' => 'cliente@testuser.com'],
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 444555666,
            'status' => 'approved',
        ]);

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '444555666',
        'status'        => 'approved',
        'valor'         => 950.00,
    ]);
});

test('backend ignores client price tampering attempt', function () {
    Http::fake([
        'https://api.mercadopago.com/v1/payments' => function ($request) {
            $data = $request->data();
            // O valor enviado no corpo pelo atacante ($1.00) deve ser ignorado e mantido R$ 99.00
            if ($data['transaction_amount'] == 99.00) {
                return Http::response([
                    'id'                 => 777888999,
                    'status'             => 'approved',
                    'transaction_amount' => 99.00,
                ], 201);
            }
            return Http::response(['message' => 'Tampered amount'], 400);
        },
    ]);

    $payload = [
        'token'              => 'FF8080814C122709014C2A1E6589083F',
        'payment_method_id'  => 'visa',
        'transaction_amount' => 1.00, // Tentativa de fraude no valor
        'tipo_pagamento'     => 'mensal',
        'payer'              => ['email' => 'hacker@testuser.com'],
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson(['id' => 777888999]);

    $this->assertDatabaseHas('pagamentos', [
        'mp_payment_id' => '777888999',
        'valor'         => 99.00,
    ]);
});
