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
use App\Services\OrdemServicoItemService;

beforeEach(function () {
    $plano = Plano::create([
        'slug' => 'pro',
        'nome' => 'Pro',
        'preco_mensal' => 99.00,
        'ativo' => true,
    ]);

    $this->empresa = new Empresa([
        'nome_fantasia'    => 'Oficina Teste',
        'razao_social'     => 'Oficina Teste Ltda',
        'cnpj'             => '12.345.678/0001-90',
        'plano_id'         => $plano->id,
        'controle_estoque' => true,
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

    $this->peca = Peca::create([
        'empresa_id'     => $this->empresa->id,
        'nome'           => 'Pastilha de Freio',
        'codigo'         => 'PF-100',
        'estoque'        => 5,
        'valor_unitario' => 120.00,
    ]);

    $this->itemService = app(OrdemServicoItemService::class);
});

test('padrao para novas empresas é controle de estoque ativado', function () {
    $novaEmpresa = Empresa::create([
        'nome_fantasia' => 'Nova Oficina Padrão',
    ]);

    expect($novaEmpresa->hasControleEstoque())->toBeTrue();
    expect($novaEmpresa->controle_estoque)->toBeTrue();
});

test('quando estoque esta ATIVADO: adicionar peca valida e baixa estoque', function () {
    $this->actingAs($this->user);

    $ordem = OrdemServico::create([
        'empresa_id' => $this->empresa->id,
        'user_id'    => $this->user->id,
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'numero_os'          => 'OS-001',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    // Adiciona 2 unidades (estoque inicial: 5)
    $response = $this->postJson("/ordens/{$ordem->id}/itens/peca", [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 2,
        'valor_unitario' => 120.00,
    ]);

    $response->assertStatus(201);
    expect($this->peca->fresh()->estoque)->toBe(3);

    // Tentar adicionar quantidade maior que o disponível (disponível: 3, pedindo 4)
    $responseErro = $this->postJson("/ordens/{$ordem->id}/itens/peca", [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 4,
        'valor_unitario' => 120.00,
    ]);

    $responseErro->assertStatus(422)
        ->assertJsonValidationErrors(['quantidade']);

    expect($this->peca->fresh()->estoque)->toBe(3);
});

test('quando estoque esta ATIVADO: alterar e excluir item movimenta estoque', function () {
    $this->actingAs($this->user);

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'user_id'            => $this->user->id,
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'numero_os'          => 'OS-002',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    $item = $this->itemService->adicionarPeca($ordem, [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 2,
        'valor_unitario' => 120.00,
    ]);

    expect($this->peca->fresh()->estoque)->toBe(3);

    // Aumentar de 2 para 4 (delta +2)
    $this->putJson("/ordens/itens/{$item->id}", [
        'descricao'      => 'Pastilha de Freio',
        'quantidade'     => 4,
        'valor_unitario' => 120.00,
    ])->assertStatus(200);

    expect($this->peca->fresh()->estoque)->toBe(1);

    // Diminuir de 4 para 1 (delta -3, devolve 3)
    $this->putJson("/ordens/itens/{$item->id}", [
        'descricao'      => 'Pastilha de Freio',
        'quantidade'     => 1,
        'valor_unitario' => 120.00,
    ])->assertStatus(200);

    expect($this->peca->fresh()->estoque)->toBe(4);

    // Excluir item (devolve 1)
    $this->deleteJson("/ordens/itens/{$item->id}")->assertStatus(200);

    expect($this->peca->fresh()->estoque)->toBe(5);
});

test('quando estoque esta ATIVADO: excluir OS devolve estoque das pecas', function () {
    $this->actingAs($this->user);

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'user_id'            => $this->user->id,
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'numero_os'          => 'OS-003',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    $this->itemService->adicionarPeca($ordem, [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 3,
        'valor_unitario' => 120.00,
    ]);

    expect($this->peca->fresh()->estoque)->toBe(2);

    $this->delete("/ordens/{$ordem->id}")->assertRedirect(route('ordens.index'));

    expect($this->peca->fresh()->estoque)->toBe(5);
});

test('quando estoque esta DESATIVADO: permite adicionar peca sem validar e sem baixar estoque', function () {
    $this->empresa->update(['controle_estoque' => false]);
    $this->actingAs($this->user);

    // Cria peça com estoque zerado
    $pecaSemEstoque = Peca::create([
        'empresa_id'     => $this->empresa->id,
        'nome'           => 'Amortecedor Especial',
        'codigo'         => 'AM-999',
        'estoque'        => 0,
        'valor_unitario' => 350.00,
    ]);

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'user_id'            => $this->user->id,
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'numero_os'          => 'OS-004',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    // Adiciona 10 unidades da peça que possui estoque 0
    $response = $this->postJson("/ordens/{$ordem->id}/itens/peca", [
        'peca_id'        => $pecaSemEstoque->id,
        'quantidade'     => 10,
        'valor_unitario' => 350.00,
    ]);

    $response->assertStatus(201);

    // O estoque cadastrado permanece 0 intacto
    expect($pecaSemEstoque->fresh()->estoque)->toBe(0);

    // O item foi criado na OS normalmente
    $item = OrdemServicoItem::where('ordem_servico_id', $ordem->id)->first();
    expect($item)->not->toBeNull();
    expect($item->quantidade)->toBe(10);
    expect((float) $item->valor_total)->toEqual(3500.00);
});

test('quando estoque esta DESATIVADO: alterar e excluir item da OS nao altera estoque cadastrado', function () {
    $this->empresa->update(['controle_estoque' => false]);
    $this->actingAs($this->user);

    // Peça com saldo 5
    expect($this->peca->fresh()->estoque)->toBe(5);

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'user_id'            => $this->user->id,
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'numero_os'          => 'OS-005',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    $item = $this->itemService->adicionarPeca($ordem, [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 3,
        'valor_unitario' => 120.00,
    ]);

    expect($this->peca->fresh()->estoque)->toBe(5);

    // Alterar quantidade para 20
    $this->putJson("/ordens/itens/{$item->id}", [
        'descricao'      => 'Pastilha de Freio',
        'quantidade'     => 20,
        'valor_unitario' => 120.00,
    ])->assertStatus(200);

    expect($this->peca->fresh()->estoque)->toBe(5);
    expect($item->fresh()->quantidade)->toBe(20);

    // Excluir item
    $this->deleteJson("/ordens/itens/{$item->id}")->assertStatus(200);

    expect($this->peca->fresh()->estoque)->toBe(5);
    expect(OrdemServicoItem::find($item->id))->toBeNull();
});

test('quando estoque esta DESATIVADO: excluir OS nao altera estoque cadastrado', function () {
    $this->empresa->update(['controle_estoque' => false]);
    $this->actingAs($this->user);

    expect($this->peca->fresh()->estoque)->toBe(5);

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'user_id'            => $this->user->id,
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'numero_os'          => 'OS-006',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
        'status'             => 'aberta',
    ]);

    $this->itemService->adicionarPeca($ordem, [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 4,
        'valor_unitario' => 120.00,
    ]);

    expect($this->peca->fresh()->estoque)->toBe(5);

    $this->delete("/ordens/{$ordem->id}")->assertRedirect(route('ordens.index'));

    expect($this->peca->fresh()->estoque)->toBe(5);
});

test('empresa pode atualizar configuracao de controle de estoque', function () {
    $this->actingAs($this->user);

    expect($this->empresa->fresh()->hasControleEstoque())->toBeTrue();

    $response = $this->put(route('empresa.update'), [
        'nome_fantasia'    => 'Oficina Atualizada',
        'controle_estoque' => '0',
    ]);

    $response->assertSessionHasNoErrors();
    expect($this->empresa->fresh()->hasControleEstoque())->toBeFalse();

    // Reativar estoque
    $responseReativar = $this->put(route('empresa.update'), [
        'nome_fantasia'    => 'Oficina Atualizada',
        'controle_estoque' => '1',
    ]);

    $responseReativar->assertSessionHasNoErrors();
    expect($this->empresa->fresh()->hasControleEstoque())->toBeTrue();
});

test('dashboard e tela de pecas ocultam informacoes de estoque quando controle_estoque esta desativado', function () {
    $this->empresa->update(['controle_estoque' => false]);
    $this->actingAs($this->user);

    // Dashboard não deve mostrar o card de Estoque Baixo
    $responseDash = $this->get(route('dashboard'));
    $responseDash->assertStatus(200)
        ->assertDontSee('Estoque Baixo')
        ->assertDontSee('Nenhuma peça com estoque baixo');

    // Tela de peças não deve mostrar a coluna Estoque nem filtro Estoque Min
    $responsePecas = $this->get(route('pecas.index'));
    $responsePecas->assertStatus(200)
        ->assertDontSee('<th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Estoque</th>', false)
        ->assertDontSee('Estoque Min');
});

test('dashboard e tela de pecas exibem informacoes de estoque quando controle_estoque esta ativado', function () {
    $this->empresa->update(['controle_estoque' => true]);
    $this->actingAs($this->user);

    // Dashboard deve mostrar o card de Estoque Baixo
    $responseDash = $this->get(route('dashboard'));
    $responseDash->assertStatus(200)
        ->assertSee('Estoque Baixo');

    // Tela de peças deve mostrar a coluna Estoque e filtro Estoque Min
    $responsePecas = $this->get(route('pecas.index'));
    $responsePecas->assertStatus(200)
        ->assertSee('Estoque')
        ->assertSee('Estoque Min');
});
