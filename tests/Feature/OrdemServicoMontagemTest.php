<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\Peca;
use App\Models\Plano;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;

beforeEach(function () {
    $plano = Plano::create([
        'slug' => 'pro',
        'nome' => 'Pro',
        'preco_mensal' => 99.00,
        'ativo' => true,
    ]);

    $this->empresa = new Empresa([
        'nome_fantasia' => 'Oficina Mecânica Teste',
        'razao_social'  => 'Oficina Teste Ltda',
        'cnpj'          => '12.345.678/0001-90',
        'plano_id'      => $plano->id,
    ]);
    $this->empresa->ativo = true;
    $this->empresa->save();

    Assinatura::create([
        'empresa_id'       => $this->empresa->id,
        'plano_id'         => $plano->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'authorized',
        'preco_contratado' => 99.00,
        'data_inicio'      => now(),
        'valido_ate'       => now()->addMonth(),
    ]);

    $this->user = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);

    $this->cliente = Cliente::factory()->create([
        'empresa_id' => $this->empresa->id,
    ]);

    $this->veiculo = Veiculo::factory()->create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $this->cliente->id,
    ]);

    $this->servico = Servico::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Troca de Óleo',
        'descricao'  => 'Troca de óleo do motor e filtro',
        'valor_base' => 150.00,
    ]);

    $this->peca = Peca::create([
        'empresa_id'     => $this->empresa->id,
        'nome'           => 'Filtro de Óleo',
        'codigo'         => 'FO-1234',
        'estoque'        => 10,
        'valor_unitario' => 45.00,
    ]);
});

test('pode criar uma OS completa montada com servicos e pecas do catalogo', function () {
    $payload = [
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'descricao_problema' => 'Revisão periódica de 10.000 km',
        'observacoes'        => 'Cliente aguardando no local',
        'itens'              => [
            [
                'tipo_item'      => 'servico',
                'servico_id'     => $this->servico->id,
                'descricao'      => 'Troca de Óleo',
                'quantidade'     => 1,
                'valor_unitario' => 150.00,
            ],
            [
                'tipo_item'      => 'peca',
                'peca_id'        => $this->peca->id,
                'descricao'      => 'Filtro de Óleo',
                'quantidade'     => 2,
                'valor_unitario' => 45.00,
            ],
        ],
    ];

    $response = $this->actingAs($this->user)->post(route('ordens.store'), $payload);

    $ordem = OrdemServico::latest('id')->first();

    $response->assertRedirect(route('ordens.show', $ordem->id));
    $response->assertSessionHas('success', 'Ordem de serviço criada com sucesso!');

    expect($ordem->descricao_problema)->toBe('Revisão periódica de 10.000 km')
        ->and((float)$ordem->valor_total)->toBe(240.00) // 150 + (2 * 45) = 240
        ->and($ordem->itens()->count())->toBe(2);

    // Verificar baixa de estoque da peça do catálogo (10 - 2 = 8)
    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(8);
});

test('pode criar OS com servico e peca personalizados sem afetar catalogo', function () {
    $payload = [
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'descricao_problema' => 'Adaptação de suporte customizado',
        'itens'              => [
            [
                'tipo_item'      => 'servico',
                'servico_id'     => null,
                'descricao'      => 'Adaptação especial de suporte',
                'quantidade'     => 1,
                'valor_unitario' => 200.00,
            ],
            [
                'tipo_item'      => 'peca',
                'peca_id'        => null,
                'descricao'      => 'Chapa metálica sob medida',
                'quantidade'     => 3,
                'valor_unitario' => 30.00,
            ],
        ],
    ];

    $response = $this->actingAs($this->user)->post(route('ordens.store'), $payload);

    $ordem = OrdemServico::latest('id')->first();
    $response->assertRedirect(route('ordens.show', $ordem->id));

    // Valor total: 200 + (3 * 30) = 290
    expect((float)$ordem->valor_total)->toBe(290.00);

    $itens = $ordem->itens;
    expect($itens[0]->servico_id)->toBeNull()
        ->and($itens[0]->descricao)->toBe('Adaptação especial de suporte')
        ->and($itens[1]->peca_id)->toBeNull()
        ->and($itens[1]->descricao)->toBe('Chapa metálica sob medida');

    // Catálogo não deve ter novos registros
    expect(Servico::where('nome', 'Adaptação especial de suporte')->exists())->toBeFalse()
        ->and(Peca::where('nome', 'Chapa metálica sob medida')->exists())->toBeFalse();
});

test('alterar preco na OS nao altera o preco padrao do catalogo (snapshot)', function () {
    $payload = [
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'descricao_problema' => 'Desconto especial',
        'itens'              => [
            [
                'tipo_item'      => 'servico',
                'servico_id'     => $this->servico->id,
                'descricao'      => 'Troca de Óleo',
                'quantidade'     => 1,
                'valor_unitario' => 120.00, // Preço original no catálogo é 150.00
            ],
        ],
    ];

    $this->actingAs($this->user)->post(route('ordens.store'), $payload);

    $ordem = OrdemServico::latest('id')->first();
    expect((float)$ordem->valor_total)->toBe(120.00);

    // O serviço no catálogo continua com valor_base 150.00
    $this->servico->refresh();
    expect((float)$this->servico->valor_base)->toBe(150.00);
});

test('rejeita criacao de OS se quantidade de peca do catalogo for maior que o estoque', function () {
    $payload = [
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'descricao_problema' => 'Tentativa de pegar mais que o estoque',
        'itens'              => [
            [
                'tipo_item'      => 'peca',
                'peca_id'        => $this->peca->id,
                'quantidade'     => 15, // Estoque é apenas 10
                'valor_unitario' => 45.00,
            ],
        ],
    ];

    $response = $this->actingAs($this->user)->post(route('ordens.store'), $payload);
    $response->assertSessionHasErrors('quantidade');

    // Nenhuma OS deve ter sido criada e o estoque deve permanecer intacto
    expect(OrdemServico::count())->toBe(0);
    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(10);
});

test('permite criar servico rapidamente via JSON no catalogo sem sair da tela', function () {
    $response = $this->actingAs($this->user)->postJson(route('servicos.store'), [
        'nome'       => 'Alinhamento 3D',
        'descricao'  => 'Alinhamento a laser computadorizado',
        'valor_base' => 180.00,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'servico' => [
                'nome'       => 'Alinhamento 3D',
                'valor_base' => 180.00,
            ],
        ]);

    expect(Servico::where('nome', 'Alinhamento 3D')->exists())->toBeTrue();
});

test('permite criar peca rapidamente via JSON no catalogo sem sair da tela', function () {
    $response = $this->actingAs($this->user)->postJson(route('pecas.store'), [
        'nome'           => 'Vela de Ignição Iridium',
        'codigo'         => 'VEL-999',
        'estoque'        => 8,
        'valor_unitario' => 65.00,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'peca'    => [
                'nome'   => 'Vela de Ignição Iridium',
                'codigo' => 'VEL-999',
            ],
        ]);

    expect(Peca::where('codigo', 'VEL-999')->exists())->toBeTrue();
});

test('permite atualizar quantidade e valor de item com movimentacao proporcional de estoque', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0001',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Teste update item',
        'valor_total'        => 90.00,
        'data_entrada'       => now(),
    ]);

    // Item inicial: 2 peças (estoque da peça baixou de 10 para 8)
    $this->peca->decrement('estoque', 2);

    $item = OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item'        => 'peca',
        'peca_id'          => $this->peca->id,
        'descricao'        => $this->peca->nome,
        'quantidade'       => 2,
        'valor_unitario'   => 45.00,
        'valor_total'      => 90.00,
    ]);

    // Aumentar de 2 para 3: delta +1, estoque deve ir de 8 para 7
    $response = $this->actingAs($this->user)->put(route('ordens.itens.update', $item->id), [
        'descricao'      => 'Filtro de Óleo Atualizado',
        'quantidade'     => 3,
        'valor_unitario' => 50.00,
    ]);

    $response->assertRedirect();
    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(7);

    $item->refresh();
    expect($item->quantidade)->toBe(3)
        ->and((float)$item->valor_unitario)->toBe(50.00)
        ->and((float)$item->valor_total)->toBe(150.00);

    $ordem->refresh();
    expect((float)$ordem->valor_total)->toBe(150.00);

    // Diminuir de 3 para 1: delta -2, estoque deve voltar de 7 para 9
    $this->actingAs($this->user)->put(route('ordens.itens.update', $item->id), [
        'quantidade'     => 1,
        'valor_unitario' => 50.00,
    ]);

    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(9);
});

test('remover item da OS restaura o estoque da peca do catalogo', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0001',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Teste remover item',
        'valor_total'        => 90.00,
        'data_entrada'       => now(),
    ]);

    $this->peca->decrement('estoque', 2); // Estoque foi para 8

    $item = OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item'        => 'peca',
        'peca_id'          => $this->peca->id,
        'descricao'        => $this->peca->nome,
        'quantidade'       => 2,
        'valor_unitario'   => 45.00,
        'valor_total'      => 90.00,
    ]);

    $response = $this->actingAs($this->user)->delete(route('ordens.itens.destroy', $item->id));

    $response->assertRedirect();
    expect(OrdemServicoItem::count())->toBe(0);

    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(10); // Restaurou para 10

    $ordem->refresh();
    expect((float)$ordem->valor_total)->toBe(0.00);
});

test('exclusao da OS devolve o estoque de todas as pecas vinculadas', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0001',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Teste exclusao OS',
        'valor_total'        => 135.00,
        'data_entrada'       => now(),
    ]);

    $this->peca->decrement('estoque', 3); // Estoque ficou 7

    OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item'        => 'peca',
        'peca_id'          => $this->peca->id,
        'descricao'        => $this->peca->nome,
        'quantidade'       => 3,
        'valor_unitario'   => 45.00,
        'valor_total'      => 135.00,
    ]);

    $response = $this->actingAs($this->user)->delete(route('ordens.destroy', $ordem->id));
    $response->assertRedirect(route('ordens.index'));

    expect(OrdemServico::count())->toBe(0)
        ->and(OrdemServicoItem::count())->toBe(0);

    $this->peca->refresh();
    expect($this->peca->estoque)->toBe(10); // Restaurou os 3
});
