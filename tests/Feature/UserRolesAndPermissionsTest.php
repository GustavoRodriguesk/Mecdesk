<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Plano;
use App\Models\Assinatura;
use App\Models\Veiculo;
use App\Models\Peca;
use App\Models\Servico;

beforeEach(function () {
    $plano = Plano::create([
        'slug' => 'pro',
        'nome' => 'Pro',
        'preco_mensal' => 99.00,
        'max_usuarios' => 5,
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

    $this->gerente = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'gerente',
    ]);

    $this->funcionario = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role'       => 'funcionario',
    ]);
});

test('admin tem acesso completo a empresa, assinatura e exclusao', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Admin',
        'telefone'   => '11999999999',
    ]);

    $this->actingAs($this->admin)->get(route('empresa.edit'))->assertStatus(200);
    $this->actingAs($this->admin)->get(route('assinatura.minha'))->assertStatus(200);
    $this->actingAs($this->admin)->get(route('usuarios.create'))->assertStatus(200);

    $this->actingAs($this->admin)->delete(route('clientes.destroy', $cliente->id))->assertRedirect(route('clientes.index'));
    expect(Cliente::find($cliente->id))->toBeNull();
});

test('gerente pode excluir registros operacionais mas nao acessa empresa ou assinatura', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Gerente',
        'telefone'   => '11888888888',
    ]);

    $this->actingAs($this->gerente)->get(route('empresa.edit'))->assertStatus(403);
    $this->actingAs($this->gerente)->get(route('assinatura.minha'))->assertStatus(403);
    $this->actingAs($this->gerente)->get(route('usuarios.create'))->assertStatus(403);

    $this->actingAs($this->gerente)->delete(route('clientes.destroy', $cliente->id))->assertRedirect(route('clientes.index'));
    expect(Cliente::find($cliente->id))->toBeNull();
});

test('funcionario nao pode excluir registros nem acessar empresa ou assinatura', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Cliente Funcionario',
        'telefone'   => '11777777777',
    ]);

    $this->actingAs($this->funcionario)->get(route('empresa.edit'))->assertStatus(403);
    $this->actingAs($this->funcionario)->get(route('assinatura.minha'))->assertStatus(403);
    $this->actingAs($this->funcionario)->get(route('usuarios.create'))->assertStatus(403);

    $this->actingAs($this->funcionario)->delete(route('clientes.destroy', $cliente->id))->assertStatus(403);
});

test('admin pode cadastrar novo usuario com papel de gerente', function () {
    $email = 'gerente_novo_' . uniqid() . '@oficina.com';

    $response = $this->actingAs($this->admin)->post(route('usuarios.store'), [
        'name'                  => 'Novo Gerente',
        'email'                 => $email,
        'password'              => 'P@ssw0rdOficina#9',
        'password_confirmation' => 'P@ssw0rdOficina#9',
        'role'                  => 'gerente',
    ]);

    $response->assertRedirect(route('empresa.edit'));

    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->isGerente())->toBeTrue();
    expect($user->canDelete())->toBeTrue();
    expect($user->canManageCompany())->toBeFalse();
});
