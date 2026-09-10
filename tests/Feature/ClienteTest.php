<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Plano;
use App\Models\Assinatura;
use App\Models\OrdemServico;
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

    $this->admin = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'admin',
    ]);
});

test('exibe lista de clientes com telefone formatado e coluna cpf/cnpj', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Carlos Silva',
        'telefone'   => '11006331818',
        'cpf_cnpj'   => '11144477735',
        'email'      => 'carlos@teste.com',
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.index'));

    $response->assertStatus(200);
    $response->assertSee('Carlos Silva');
    $response->assertSee('(11) 00633-1818');
    $response->assertSee('111.444.777-35');
    $response->assertSee('CPF/CNPJ');
});

test('filtra clientes por nome', function () {
    $c1 = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Carlos Silva',
        'telefone'   => '11981112233',
    ]);
    $c2 = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Mariana Costa',
        'telefone'   => '11982223344',
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.index', ['search' => 'Carlos']));

    $response->assertStatus(200);
    $response->assertSee('Carlos Silva');
    $response->assertDontSee('Mariana Costa');
});

test('filtra clientes por cpf/cnpj formatado ou numerico', function () {
    $c1 = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Carlos Silva',
        'telefone'   => '11981112233',
        'cpf_cnpj'   => '11144477735',
    ]);
    $c2 = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Transportes Veloz LTDA',
        'telefone'   => '1133445566',
        'cpf_cnpj'   => '12345678000190',
    ]);

    // Busca por CNPJ com pontuação
    $response = $this->actingAs($this->admin)->get(route('clientes.index', ['search' => '12.345.678/0001-90']));
    $response->assertStatus(200);
    $response->assertSee('Transportes Veloz LTDA');
    $response->assertDontSee('Carlos Silva');

    // Busca por CPF apenas números
    $response2 = $this->actingAs($this->admin)->get(route('clientes.index', ['search' => '11144477735']));
    $response2->assertStatus(200);
    $response2->assertSee('Carlos Silva');
    $response2->assertDontSee('Transportes Veloz LTDA');
});

test('permite excluir cliente sem ordens de servico usando soft delete e cascata em veiculos', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Sem OS',
        'telefone'   => '11999990000',
    ]);

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Fiat',
        'modelo'     => 'Palio',
        'ano'        => 2015,
        'placa'      => 'PAL1010',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('clientes.destroy', $cliente->id));
    $response->assertRedirect(route('clientes.index'))
        ->assertSessionHas('success', 'Cliente excluído com sucesso!');

    expect(Cliente::find($cliente->id))->toBeNull()
        ->and(Cliente::withTrashed()->find($cliente->id))->not->toBeNull()
        ->and(Cliente::withTrashed()->find($cliente->id)->trashed())->toBeTrue()
        ->and(Veiculo::find($veiculo->id))->toBeNull()
        ->and(Veiculo::withTrashed()->find($veiculo->id)->trashed())->toBeTrue();
});

test('impede exclusao de cliente que possui ordens de servico vinculadas', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Com OS',
        'telefone'   => '11999991111',
    ]);

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Toyota',
        'modelo'     => 'Corolla',
        'ano'        => 2022,
        'placa'      => 'ABC1234',
    ]);

    OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0001',
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'user_id'            => $this->admin->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Barulho no motor',
        'valor_total'        => 100.00,
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($this->admin)->delete(route('clientes.destroy', $cliente->id));
    $response->assertRedirect(route('clientes.index'))
        ->assertSessionHas('error', 'Não é possível excluir este cliente pois existem ordens de serviço vinculadas a ele.');

    expect(Cliente::find($cliente->id))->not->toBeNull();
});
