<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\Plano;
use App\Models\User;
use App\Models\Veiculo;

beforeEach(function () {
    $plano = Plano::create([
        'slug'         => 'pro',
        'nome'         => 'Pro',
        'preco_mensal' => 99.00,
        'ativo'        => true,
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

    $this->admin = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);

    $this->cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Roberto Santos',
        'telefone'   => '11988887777',
    ]);

    $this->veiculo = Veiculo::create([
        'empresa_id'    => $this->empresa->id,
        'cliente_id'    => $this->cliente->id,
        'marca'         => 'Volkswagen',
        'modelo'        => 'Golf TSI',
        'ano'           => 2018,
        'placa'         => 'GLF2018',
        'cor'           => 'Preto',
        'quilometragem' => 50000,
    ]);

    $this->ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-8001',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->admin->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Barulho na suspensão dianteira ao passar em lombadas',
        'problemas_previos'  => 'Pequeno arranhão no para-choque dianteiro',
        'valor_total'        => 450.00,
        'data_entrada'       => now(),
    ]);
});

test('rota ordens.show renderiza a visualizacao das informacoes da OS como exclusivamente somente leitura', function () {
    $response = $this->actingAs($this->admin)->get(route('ordens.show', $this->ordem->id));

    $response->assertStatus(200);
    $response->assertViewIs('ordens.show');
    $response->assertSee('OS-8001');
    $response->assertSee('Roberto Santos');
    $response->assertSee('Golf TSI');
    $response->assertSee('GLF2018');
    $response->assertSee('Barulho na suspensão dianteira ao passar em lombadas');
    $response->assertSee('Pequeno arranhão no para-choque dianteiro');
    $response->assertSee(route('ordens.edit', $this->ordem->id));

    // A página de visualização não deve permitir edição de itens, fotos ou descontos
    $response->assertDontSee('+ Adicionar Serviço');
    $response->assertDontSee('+ Adicionar Peça');
    $response->assertDontSee('+ Adicionar Fotos');
    $response->assertDontSee('name="fotos[]"', false);
    $response->assertDontSee('name="desconto_valor"', false);
});

test('rota ordens.edit renderiza o modulo exclusivo de edicao da OS com gestao de itens, desconto e fotos', function () {
    $response = $this->actingAs($this->admin)->get(route('ordens.edit', $this->ordem->id));

    $response->assertStatus(200);
    $response->assertViewIs('ordens.edit');
    $response->assertSee('Editar Ordem de Serviço #OS-8001');
    $response->assertSee('name="cliente_id"', false);
    $response->assertSee('name="veiculo_id"', false);
    $response->assertSee('name="descricao_problema"', false);
    $response->assertSee('Barulho na suspensão dianteira ao passar em lombadas');
    $response->assertSee('name="status"', false);
    $response->assertSee('+ Adicionar Serviço');
    $response->assertSee('+ Adicionar Peça');
    $response->assertSee('+ Adicionar Fotos');
    $response->assertSee('name="fotos[]"', false);
    $response->assertSee(route('ordens.show', $this->ordem->id));
    $response->assertSee(route('ordens.index'));
});

test('rota ordens.index exibe botoes separados de visualizacao e edicao da OS', function () {
    $response = $this->actingAs($this->admin)->get(route('ordens.index'));

    $response->assertStatus(200);
    $response->assertSee(route('ordens.show', $this->ordem->id));
    $response->assertSee(route('ordens.edit', $this->ordem->id));
    $response->assertSee('Visualizar OS');
    $response->assertSee('Editar OS');
});

test('atualizacao da OS via ordens.update redireciona para a visualizacao ordens.show', function () {
    $response = $this->actingAs($this->admin)->put(route('ordens.update', $this->ordem->id), [
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'descricao_problema' => 'Barulho corrigido, realizada troca de buchas',
        'problemas_previos'  => 'Sem outras avarias',
        'status'             => 'em_andamento',
    ]);

    $response->assertRedirect(route('ordens.show', $this->ordem->id));
    $response->assertSessionHas('success', 'Ordem de Serviço atualizada com sucesso!');

    $this->ordem->refresh();
    expect($this->ordem->descricao_problema)->toBe('Barulho corrigido, realizada troca de buchas')
        ->and($this->ordem->status)->toBe('em_andamento');
});
