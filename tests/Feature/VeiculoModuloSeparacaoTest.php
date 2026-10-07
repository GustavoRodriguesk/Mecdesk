<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\Plano;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Assinatura;

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
        'nome'       => 'Carlos Silva',
        'telefone'   => '11988887777',
    ]);

    $this->veiculo = Veiculo::create([
        'empresa_id'    => $this->empresa->id,
        'cliente_id'    => $this->cliente->id,
        'marca'         => 'Fiat',
        'modelo'        => 'Palio Fire',
        'ano'           => 2014,
        'placa'         => 'ABC1234',
        'cor'           => 'Prata',
        'quilometragem' => 85000,
    ]);
});

test('rota veiculos.show renderiza a view de detalhes e historico de servicos', function () {
    // Cria uma ordem de serviço vinculada ao veículo
    $os = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-9901',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->admin->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Troca de pastilhas de freio',
        'valor_total'        => 350.00,
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($this->admin)->get(route('veiculos.show', $this->veiculo->id));

    $response->assertStatus(200);
    $response->assertViewIs('veiculos.show');
    $response->assertSee('Detalhes do Veículo');
    $response->assertSee('Palio Fire');
    $response->assertSee('ABC1234');
    $response->assertSee('Carlos Silva');
    $response->assertSee('Histórico de Ordens de Serviço');
    $response->assertSee('#OS-9901');
    $response->assertSee('R$ 350,00');
    $response->assertSee(route('veiculos.edit', $this->veiculo->id));
});

test('rota veiculos.edit renderiza o modulo exclusivo de edicao do veiculo', function () {
    $response = $this->actingAs($this->admin)->get(route('veiculos.edit', $this->veiculo->id));

    $response->assertStatus(200);
    $response->assertViewIs('veiculos.edit');
    $response->assertSee('Editar Veículo');
    $response->assertSee('Atualizar Veículo');
    $response->assertSee('name="modelo"', false);
    $response->assertSee('Palio Fire');
    $response->assertSee('name="placa"', false);
    $response->assertSee('ABC1234');
    $response->assertSee(route('veiculos.show', $this->veiculo->id));

    // Garante que o histórico não está duplicado dentro da tela de edição
    $response->assertDontSee('Histórico de Ordens de Serviço');
});

test('rota veiculos.index exibe botoes separados de detalhes e edicao', function () {
    $response = $this->actingAs($this->admin)->get(route('veiculos.index'));

    $response->assertStatus(200);
    $response->assertSee(route('veiculos.show', $this->veiculo->id));
    $response->assertSee(route('veiculos.edit', $this->veiculo->id));
});

test('atualizacao do veiculo persiste alteracoes com sucesso', function () {
    $response = $this->actingAs($this->admin)->put(route('veiculos.update', $this->veiculo->id), [
        'cliente_id'    => $this->cliente->id,
        'marca'         => 'Fiat',
        'modelo'        => 'Palio Way Atualizado',
        'ano'           => 2015,
        'placa'         => 'ABC1234',
        'cor'           => 'Vermelho',
        'quilometragem' => 90000,
    ]);

    $response->assertRedirect(route('veiculos.index'));
    $response->assertSessionHas('success', 'Veículo atualizado com sucesso!');

    $this->veiculo->refresh();
    expect($this->veiculo->modelo)->toBe('Palio Way Atualizado')
        ->and($this->veiculo->cor)->toBe('Vermelho')
        ->and($this->veiculo->ano)->toBe(2015)
        ->and($this->veiculo->quilometragem)->toBe(90000);
});
