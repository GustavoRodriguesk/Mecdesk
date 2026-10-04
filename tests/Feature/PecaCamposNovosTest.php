<?php

use App\Models\Empresa;
use App\Models\Peca;
use App\Models\Plano;
use App\Models\Assinatura;
use App\Models\User;

beforeEach(function () {
    $plano = Plano::create([
        'slug' => 'pro-teste',
        'nome' => 'Pro Teste',
        'preco_mensal' => 99.00,
        'ativo' => true,
    ]);

    $this->empresa = new Empresa([
        'nome_fantasia' => 'Oficina Mecânica Peças Teste',
        'razao_social'  => 'Oficina Teste Peças Ltda',
        'cnpj'          => '12.345.678/0001-99',
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

    $this->admin = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);
});

test('consegue cadastrar peca com todos os novos campos', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('pecas.store'), [
            'nome' => 'Pastilha de Freio Dianteira',
            'marca' => 'Bosch',
            'codigo' => 'PST-101',
            'codigo_barras' => '7891234567890',
            'preco_custo' => '85.50',
            'preco_venda' => '130.00',
            'estoque' => 15,
        ]);

    $response->assertRedirect(route('pecas.index'));

    $peca = Peca::where('codigo', 'PST-101')->first();
    expect($peca)->not->toBeNull()
        ->and($peca->nome)->toBe('Pastilha de Freio Dianteira')
        ->and($peca->marca)->toBe('Bosch')
        ->and($peca->codigo_barras)->toBe('7891234567890')
        ->and((float) $peca->preco_custo)->toBe(85.50)
        ->and((float) $peca->preco_venda)->toBe(130.00)
        ->and((float) $peca->valor_unitario)->toBe(130.00)
        ->and($peca->estoque)->toBe(15);
});

test('consegue cadastrar peca preenchendo apenas nome, preco_custo e preco_venda', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('pecas.store'), [
            'nome' => 'Filtro de Combustível',
            'marca' => null,
            'codigo' => null,
            'codigo_barras' => null,
            'preco_custo' => '25.00',
            'preco_venda' => '45.00',
            'estoque' => null,
        ]);

    $response->assertRedirect(route('pecas.index'));

    $peca = Peca::where('nome', 'Filtro de Combustível')->first();
    expect($peca)->not->toBeNull()
        ->and($peca->marca)->toBeNull()
        ->and($peca->codigo)->toBeNull()
        ->and($peca->codigo_barras)->toBeNull()
        ->and((float) $peca->preco_custo)->toBe(25.00)
        ->and((float) $peca->preco_venda)->toBe(45.00)
        ->and($peca->estoque)->toBe(0);
});

test('valida campos obrigatorios: nome, preco_custo e preco_venda', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('pecas.store'), [
            'nome' => '',
            'preco_custo' => '',
            'preco_venda' => '',
            'marca' => 'Marca Opcional',
        ]);

    $response->assertSessionHasErrors(['nome', 'preco_custo', 'preco_venda']);
});

test('consegue atualizar peca incluindo marca, codigo_barras e precos', function () {
    $peca = Peca::create([
        'empresa_id' => $this->empresa->id,
        'nome' => 'Amortecedor Dianteiro',
        'preco_custo' => 100.00,
        'preco_venda' => 180.00,
        'valor_unitario' => 180.00,
        'estoque' => 4,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('pecas.update', $peca->id), [
            'nome' => 'Amortecedor Dianteiro Cofap',
            'marca' => 'Cofap',
            'codigo' => 'AM-99',
            'codigo_barras' => '7899998888777',
            'preco_custo' => '110.00',
            'preco_venda' => '210.00',
            'estoque' => 6,
        ]);

    $response->assertRedirect(route('pecas.index'));

    $peca->refresh();
    expect($peca->nome)->toBe('Amortecedor Dianteiro Cofap')
        ->and($peca->marca)->toBe('Cofap')
        ->and($peca->codigo)->toBe('AM-99')
        ->and($peca->codigo_barras)->toBe('7899998888777')
        ->and((float) $peca->preco_custo)->toBe(110.00)
        ->and((float) $peca->preco_venda)->toBe(210.00)
        ->and($peca->estoque)->toBe(6);
});

test('busca rapida encontra peca por marca e codigo de barras', function () {
    Peca::create([
        'empresa_id' => $this->empresa->id,
        'nome' => 'Vela de Ignição',
        'marca' => 'NGK Special',
        'codigo' => 'V-10',
        'codigo_barras' => '1234567890128',
        'preco_custo' => 15.00,
        'preco_venda' => 30.00,
        'estoque' => 20,
    ]);

    $responsePorMarca = $this->actingAs($this->admin)
        ->get(route('pecas.index', ['search' => 'NGK Special']));
    $responsePorMarca->assertOk();
    $responsePorMarca->assertSee('Vela de Ignição');
    $responsePorMarca->assertSee('NGK Special');

    $responsePorBarras = $this->actingAs($this->admin)
        ->get(route('pecas.index', ['search' => '1234567890128']));
    $responsePorBarras->assertOk();
    $responsePorBarras->assertSee('Vela de Ignição');
    $responsePorBarras->assertSee('1234567890128');
});
