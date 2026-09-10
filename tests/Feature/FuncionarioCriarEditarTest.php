<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Plano;
use App\Models\Assinatura;

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

    $this->funcionario = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'funcionario',
    ]);
});

test('funcionario consegue cadastrar e editar cliente', function () {
    $responseCreate = $this->actingAs($this->funcionario)
        ->post(route('clientes.store'), [
            'nome'     => 'Cliente Teste Funcionario',
            'telefone' => '11999999999',
        ]);

    $responseCreate->assertRedirect(route('clientes.index'));

    $cliente = Cliente::where('nome', 'Cliente Teste Funcionario')->first();
    expect($cliente)->not->toBeNull();

    // Visualizar e editar
    $responseEdit = $this->actingAs($this->funcionario)
        ->get(route('clientes.edit', $cliente->id));
    $responseEdit->assertStatus(200);

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('clientes.update', $cliente->id), [
            'nome'     => 'Cliente Teste Atualizado',
            'telefone' => '11988888888',
        ]);
    $responseUpdate->assertRedirect(route('clientes.index'));

    expect($cliente->fresh()->nome)->toBe('Cliente Teste Atualizado');
});

test('funcionario consegue cadastrar e editar peca', function () {
    $responseCreate = $this->actingAs($this->funcionario)
        ->post(route('pecas.store'), [
            'nome'           => 'Pastilha de Freio',
            'codigo'         => 'PF-100',
            'valor_unitario' => 120.00,
            'estoque'        => 5,
        ]);

    $responseCreate->assertRedirect(route('pecas.index'));

    $peca = Peca::where('codigo', 'PF-100')->first();
    expect($peca)->not->toBeNull();

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('pecas.update', $peca->id), [
            'nome'           => 'Pastilha de Freio Cerâmica',
            'codigo'         => 'PF-100',
            'valor_unitario' => 150.00,
            'estoque'        => 8,
        ]);
    $responseUpdate->assertRedirect(route('pecas.index'));

    expect($peca->fresh()->nome)->toBe('Pastilha de Freio Cerâmica');
});

test('funcionario consegue cadastrar e editar servico', function () {
    $responseCreate = $this->actingAs($this->funcionario)
        ->post(route('servicos.store'), [
            'nome'       => 'Alinhamento e Balanceamento',
            'descricao'  => 'Serviço completo 4 rodas',
            'valor_base' => 90.00,
        ]);

    $responseCreate->assertRedirect(route('servicos.index'));

    $servico = Servico::where('nome', 'Alinhamento e Balanceamento')->first();
    expect($servico)->not->toBeNull();

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('servicos.update', $servico->id), [
            'nome'       => 'Alinhamento 3D',
            'descricao'  => 'Serviço 3D computadorizado',
            'valor_base' => 110.00,
        ]);
    $responseUpdate->assertRedirect(route('servicos.index'));

    expect($servico->fresh()->nome)->toBe('Alinhamento 3D');
});

test('funcionario consegue cadastrar e editar veiculo', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Veículo',
        'telefone'   => '11999999999',
    ]);

    $responseCreate = $this->actingAs($this->funcionario)
        ->post(route('veiculos.store'), [
            'cliente_id'    => $cliente->id,
            'marca'         => 'Honda',
            'modelo'        => 'Civic',
            'ano'           => 2020,
            'quilometragem' => 50000,
            'placa'         => 'ABC1D23',
        ]);

    $responseCreate->assertRedirect(route('veiculos.index'));

    $veiculo = Veiculo::where('placa', 'ABC1D23')->first();
    expect($veiculo)->not->toBeNull();

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('veiculos.update', $veiculo->id), [
            'cliente_id'    => $cliente->id,
            'marca'         => 'Honda',
            'modelo'        => 'Civic Touring',
            'ano'           => 2020,
            'quilometragem' => 55000,
            'placa'         => 'ABC1D23',
        ]);
    $responseUpdate->assertRedirect(route('veiculos.index'));

    expect($veiculo->fresh()->modelo)->toBe('Civic Touring');
});

test('admin pode excluir veiculo sem ordens de servico', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Veiculo Delete',
        'telefone'   => '11999998888',
    ]);

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Fiat',
        'modelo'     => 'Uno',
        'ano'        => 2018,
        'placa'      => 'UNO1234',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('veiculos.destroy', $veiculo->id));
    $response->assertRedirect(route('veiculos.index'))
        ->assertSessionHas('success', 'Veículo excluído com sucesso!');

    expect(Veiculo::find($veiculo->id))->toBeNull()
        ->and(Veiculo::withTrashed()->find($veiculo->id))->not->toBeNull()
        ->and(Veiculo::withTrashed()->find($veiculo->id)->trashed())->toBeTrue();
});

test('servico pode ser cadastrado e editado com descricao nula', function () {
    $responseCreate = $this->actingAs($this->funcionario)
        ->post(route('servicos.store'), [
            'nome'       => 'Alinhamento Simples',
            'descricao'  => null,
            'valor_base' => 80.00,
        ]);

    $responseCreate->assertRedirect(route('servicos.index'));

    $servico = Servico::where('nome', 'Alinhamento Simples')->first();
    expect($servico)->not->toBeNull()
        ->and($servico->descricao)->toBeNull();

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('servicos.update', $servico->id), [
            'nome'       => 'Alinhamento Simples Modificado',
            'descricao'  => null,
            'valor_base' => 85.00,
        ]);

    $responseUpdate->assertRedirect(route('servicos.index'));
    expect($servico->fresh()->descricao)->toBeNull();
});

test('peca pode ser atualizada com codigo nulo', function () {
    $peca = Peca::create([
        'empresa_id'     => $this->empresa->id,
        'nome'           => 'Lâmpada H4',
        'codigo'         => 'LAMP-01',
        'estoque'        => 10,
        'valor_unitario' => 25.00,
    ]);

    $responseUpdate = $this->actingAs($this->funcionario)
        ->put(route('pecas.update', $peca->id), [
            'nome'           => 'Lâmpada H4 Super Branca',
            'codigo'         => null,
            'estoque'        => 12,
            'valor_unitario' => 30.00,
        ]);

    $responseUpdate->assertRedirect(route('pecas.index'));
    expect($peca->fresh()->codigo)->toBeNull();
});

test('impede exclusao de veiculo que possui ordens de servico vinculadas', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Veiculo Com OS',
        'telefone'   => '11999997777',
    ]);

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'VW',
        'modelo'     => 'Gol',
        'ano'        => 2019,
        'placa'      => 'GOL9999',
    ]);

    OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0002',
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'user_id'            => $this->admin->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Revisão geral',
        'valor_total'        => 250.00,
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($this->admin)->delete(route('veiculos.destroy', $veiculo->id));
    $response->assertRedirect(route('veiculos.index'))
        ->assertSessionHas('error', 'Não é possível excluir este veículo pois existem ordens de serviço vinculadas a ele.');

    expect(Veiculo::find($veiculo->id))->not->toBeNull();
});
