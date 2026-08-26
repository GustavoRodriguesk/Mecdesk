<?php

use App\Models\Assinatura;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
        'nome_fantasia' => 'Oficina Checkout',
        'plano_id'      => $this->planoPro->id,
        'ativo'         => false,
    ]);

    $this->user = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);
});

test('checkout show renders card payment brick page for pro plan', function () {
    $response = $this->actingAs($this->user)->get(route('checkout.show'));

    $response->assertStatus(200)
        ->assertSee('cardPaymentBrick_container')
        ->assertSee('sdk.mercadopago.com/js/v2');
});

test('checkout processar creates subscription via preapproval API with zero-trust price', function () {
    $idempotencyKey = (string) Str::uuid();

    Http::fake([
        'https://api.mercadopago.com/preapproval' => function ($request) use ($idempotencyKey) {
            $data = $request->data();
            $headers = $request->headers();

            if (
                ($headers['X-Idempotency-Key'][0] ?? '') === $idempotencyKey &&
                ($data['auto_recurring']['transaction_amount'] ?? 0) == 99.90 &&
                ($data['card_token_id'] ?? '') === 'TOKEN_CARD_MOCK'
            ) {
                return Http::response([
                    'id'                 => 'preapp_123456',
                    'status'             => 'authorized',
                    'reason'             => 'Assinatura MecDesk - Plano Pro',
                    'external_reference' => (string) $this->empresa->id,
                ], 201);
            }
            return Http::response(['message' => 'Invalid parameters'], 400);
        },
    ]);

    $payload = [
        'card_token_id'   => 'TOKEN_CARD_MOCK',
        'idempotency_key' => $idempotencyKey,
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 'preapp_123456',
            'status' => 'authorized',
        ]);

    $this->empresa->refresh();
    expect($this->empresa->ativo)->toBeTrue();

    $this->assertDatabaseHas('assinaturas', [
        'empresa_id'        => $this->empresa->id,
        'mp_preapproval_id' => 'preapp_123456',
        'status'            => 'authorized',
        'preco_contratado'  => 99.90,
    ]);
});

test('checkout processar handles non-uuid idempotency key gracefully by generating valid UUID', function () {
    Http::fake([
        'https://api.mercadopago.com/preapproval' => function ($request) {
            $headers = $request->headers();
            $key = $headers['X-Idempotency-Key'][0] ?? '';
            
            if (\Illuminate\Support\Str::isUuid($key)) {
                return Http::response([
                    'id'     => 'preapp_fallback_uuid',
                    'status' => 'authorized',
                ], 201);
            }
            return Http::response(['message' => 'Invalid header'], 400);
        },
    ]);

    $payload = [
        'card_token_id'   => 'TOKEN_CARD_MOCK',
        'idempotency_key' => 'legacy-non-uuid-key',
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 'preapp_fallback_uuid',
            'status' => 'authorized',
        ]);
});

test('checkout processar updates pending subscription via PUT /preapproval/{id} if pending mp_preapproval_id exists', function () {
    $assinaturaPendente = Assinatura::create([
        'empresa_id'        => $this->empresa->id,
        'plano_id'          => $this->planoPro->id,
        'status'            => 'pending',
        'mp_preapproval_id' => 'preapp_existente_999',
        'preco_contratado'  => 99.90,
    ]);

    Http::fake([
        'https://api.mercadopago.com/preapproval/preapp_existente_999' => function ($request) {
            $data = $request->data();
            if (($data['card_token_id'] ?? '') === 'NEW_CARD_TOKEN') {
                return Http::response([
                    'id'     => 'preapp_existente_999',
                    'status' => 'authorized',
                ], 200);
            }
            return Http::response(['message' => 'Bad request'], 400);
        },
    ]);

    $payload = [
        'card_token_id' => 'NEW_CARD_TOKEN',
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(200)
        ->assertJson([
            'id'     => 'preapp_existente_999',
            'status' => 'authorized',
        ]);
});

test('checkout processar returns HTTP 422 on synchronous card rejection', function () {
    Http::fake([
        'https://api.mercadopago.com/preapproval' => Http::response([
            'id'     => 'preapp_rejected_111',
            'status' => 'rejected',
        ], 200),
    ]);

    $payload = [
        'card_token_id' => 'CARD_TOKEN_NO_FUNDS',
    ];

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), $payload);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 'rejected',
            'error'  => 'Cartão recusado',
        ]);
});

test('checkout processar blocks duplicate subscription for company with active subscription', function () {
    Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'status'           => 'authorized',
        'preco_contratado' => 99.90,
    ]);
    $this->empresa->ativo = true;
    $this->empresa->save();

    $response = $this->actingAs($this->user)->postJson(route('checkout.processar'), [
        'card_token_id' => 'SOME_TOKEN',
    ]);

    $response->assertStatus(422)
        ->assertJson(['error' => 'Assinatura já ativa']);
});

test('subscription cancellation endpoint cancels subscription without IDOR risk', function () {
    $assinatura = Assinatura::create([
        'empresa_id'        => $this->empresa->id,
        'plano_id'          => $this->planoPro->id,
        'status'            => 'authorized',
        'mp_preapproval_id' => 'preapp_cancel_777',
        'preco_contratado'  => 99.90,
    ]);

    Http::fake([
        'https://api.mercadopago.com/preapproval/preapp_cancel_777' => function ($request) {
            if (($request->data()['status'] ?? '') === 'cancelled') {
                return Http::response(['id' => 'preapp_cancel_777', 'status' => 'cancelled'], 200);
            }
            return Http::response([], 400);
        },
    ]);

    $response = $this->actingAs($this->user)->post(route('assinatura.cancelar'));

    $response->assertRedirect()
        ->assertSessionHas('success');

    $assinatura->refresh();
    expect($assinatura->status)->toBe('cancelled')
        ->and($assinatura->data_cancelamento)->not->toBeNull();
});

test('assinatura sucesso screen can be rendered for active company', function () {
    $this->empresa->ativo = true;
    $this->empresa->save();

    Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'status'           => 'authorized',
        'preco_contratado' => 99.90,
    ]);

    $response = $this->actingAs($this->user)->get(route('assinatura.sucesso'));

    $response->assertStatus(200)
        ->assertSee('Parabéns! Sua compra foi concluída')
        ->assertSee('MecDesk Pro');
});

test('contratar screen can be rendered for guest with step 1', function () {
    $response = $this->get(route('planos.contratar'));

    $response->assertStatus(200)
        ->assertSee('Contrate o MecDesk')
        ->assertSee('1. Seus dados')
        ->assertSee('2. Pagamento')
        ->assertSee('3. Confirmação')
        ->assertSee('formCadastro');
});

test('contratar screen for authenticated pending user shows step 2', function () {
    $response = $this->actingAs($this->user)->get(route('planos.contratar'));

    $response->assertStatus(200)
        ->assertSee('cardPaymentBrick_container')
        ->assertSee('Oficina Checkout');
});

test('contratar screen redirects active subscriber to dashboard', function () {
    $this->empresa->ativo = true;
    $this->empresa->save();

    Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $this->planoPro->id,
        'status'           => 'authorized',
        'preco_contratado' => 99.90,
    ]);

    $response = $this->actingAs($this->user)->get(route('planos.contratar'));

    $response->assertRedirect(route('dashboard'));
});

test('contratar criar conta endpoint validates and creates account in step 1', function () {
    $payload = [
        'empresa'               => 'Oficina Top Auto',
        'name'                  => 'Carlos Mecanico',
        'email'                 => 'carlos@topauto.com.br',
        'telefone'              => '11999998888',
        'password'              => 'senhaSegura2026',
        'password_confirmation' => 'senhaSegura2026',
    ];

    $response = $this->postJson(route('contratar.criar-conta'), $payload);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'user'    => [
                'name'  => 'Carlos Mecanico',
                'email' => 'carlos@topauto.com.br',
            ],
            'empresa' => [
                'nome_fantasia' => 'Oficina Top Auto',
            ],
        ]);

    $this->assertAuthenticated();

    $this->assertDatabaseHas('empresas', [
        'nome_fantasia' => 'Oficina Top Auto',
        'email'         => 'carlos@topauto.com.br',
        'ativo'         => false,
    ]);

    $this->assertDatabaseHas('users', [
        'name'  => 'Carlos Mecanico',
        'email' => 'carlos@topauto.com.br',
        'role'  => 'admin',
    ]);

    $this->assertDatabaseHas('assinaturas', [
        'status'           => 'pending',
        'preco_contratado' => 99.90,
    ]);
});

test('contratar criar conta validates required fields', function () {
    $response = $this->postJson(route('contratar.criar-conta'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['empresa', 'name', 'email', 'password']);
});

test('assinar route redirects to contratar route', function () {
    $response = $this->get(route('planos.assinar'));

    $response->assertRedirect(route('planos.contratar'));
});

