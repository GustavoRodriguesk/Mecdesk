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

test('formulario de criacao de cliente exibe checkbox possui whatsapp marcado por padrao', function () {
    $response = $this->actingAs($this->admin)->get(route('clientes.create'));

    $response->assertStatus(200);
    $response->assertSee('Possui WhatsApp?');
    $response->assertSee('name="possui_whatsapp"', false);
    $response->assertSee('checked', false);
});

test('cadastra cliente com whatsapp ativo por padrao e permite desativar', function () {
    // 1. Cadastro com possui_whatsapp ativo
    $response1 = $this->actingAs($this->admin)->post(route('clientes.store'), [
        'nome'            => 'Cliente Com Whats',
        'telefone'        => '(11) 98888-7777',
        'possui_whatsapp' => '1',
    ]);

    $response1->assertRedirect(route('clientes.index'));

    $cliente1 = Cliente::where('nome', 'Cliente Com Whats')->first();
    expect($cliente1)->not->toBeNull()
        ->and($cliente1->possui_whatsapp)->toBeTrue()
        ->and($cliente1->whatsapp_link)->toBe('https://wa.me/5511988887777');

    // 2. Cadastro com possui_whatsapp desativado
    $response2 = $this->actingAs($this->admin)->post(route('clientes.store'), [
        'nome'            => 'Cliente Sem Whats',
        'telefone'        => '(11) 3333-2222',
        'possui_whatsapp' => '0',
    ]);

    $response2->assertRedirect(route('clientes.index'));

    $cliente2 = Cliente::where('nome', 'Cliente Sem Whats')->first();
    expect($cliente2)->not->toBeNull()
        ->and($cliente2->possui_whatsapp)->toBeFalse();
});

test('atualiza opcao de possui whatsapp ao editar cliente', function () {
    $cliente = Cliente::create([
        'empresa_id'      => $this->empresa->id,
        'nome'            => 'Cliente Teste Update',
        'telefone'        => '11977776666',
        'possui_whatsapp' => true,
    ]);

    // Desativa whatsapp
    $response = $this->actingAs($this->admin)->put(route('clientes.update', $cliente->id), [
        'nome'            => 'Cliente Teste Update',
        'telefone'        => '(11) 97777-6666',
        'possui_whatsapp' => '0',
    ]);

    $response->assertRedirect(route('clientes.index'));
    expect($cliente->fresh()->possui_whatsapp)->toBeFalse();

    // Reativa whatsapp
    $responseReativa = $this->actingAs($this->admin)->put(route('clientes.update', $cliente->id), [
        'nome'            => 'Cliente Teste Update',
        'telefone'        => '(11) 97777-6666',
        'possui_whatsapp' => '1',
    ]);

    $responseReativa->assertRedirect(route('clientes.index'));
    expect($cliente->fresh()->possui_whatsapp)->toBeTrue();
});

test('exibe botao do whatsapp na listagem apenas para clientes com a funcao ativada', function () {
    $clienteComWhats = Cliente::create([
        'empresa_id'      => $this->empresa->id,
        'nome'            => 'Cliente Whatsapp Ativo',
        'telefone'        => '11999998888',
        'possui_whatsapp' => true,
    ]);

    $clienteSemWhats = Cliente::create([
        'empresa_id'      => $this->empresa->id,
        'nome'            => 'Cliente Whatsapp Desativado',
        'telefone'        => '1133334444',
        'possui_whatsapp' => false,
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.index'));

    $response->assertStatus(200);

    // O link do WhatsApp deve estar presente para o cliente com WhatsApp ativo
    $response->assertSee('https://wa.me/5511999998888', false);
    $response->assertSee('bi-whatsapp', false);

    // O link do WhatsApp NÃO deve estar presente para o cliente sem WhatsApp
    $response->assertDontSee('https://wa.me/551133334444', false);
});

test('formulario de criacao exibe topicos de informacoes pessoais, contato e endereco com cep', function () {
    $response = $this->actingAs($this->admin)->get(route('clientes.create'));

    $response->assertStatus(200);
    $response->assertSee('Informações Pessoais');
    $response->assertSee('Contato');
    $response->assertSee('Endereço');
    $response->assertSee('name="telefone_2"', false);
    $response->assertSee('name="telefone_3"', false);
    $response->assertSee('name="instagram"', false);
    $response->assertSee('name="cep"', false);
    $response->assertSee('name="rua"', false);
    $response->assertSee('name="numero"', false);
    $response->assertSee('name="bairro"', false);
    $response->assertSee('name="cidade"', false);
    $response->assertSee('name="estado"', false);
    $response->assertSee('btn-buscar-cep', false);
});

test('apenas nome e telefone sao obrigatorios no cadastro', function () {
    // 1. Falha sem nome
    $resSemNome = $this->actingAs($this->admin)->post(route('clientes.store'), [
        'telefone' => '(11) 98888-7777',
    ]);
    $resSemNome->assertSessionHasErrors(['nome']);

    // 2. Falha sem telefone
    $resSemTel = $this->actingAs($this->admin)->post(route('clientes.store'), [
        'nome' => 'José da Silva',
    ]);
    $resSemTel->assertSessionHasErrors(['telefone']);

    // 3. Sucesso informando apenas nome e telefone
    $resSucesso = $this->actingAs($this->admin)->post(route('clientes.store'), [
        'nome'     => 'Cliente Minimo',
        'telefone' => '(11) 98888-7777',
    ]);
    $resSucesso->assertRedirect(route('clientes.index'));

    $cliente = Cliente::where('nome', 'Cliente Minimo')->first();
    expect($cliente)->not->toBeNull()
        ->and($cliente->telefone)->toBe('11988887777')
        ->and($cliente->telefone_2)->toBeNull()
        ->and($cliente->cep)->toBeNull()
        ->and($cliente->rua)->toBeNull();
});

test('cadastra e atualiza cliente com ate 3 telefones, instagram e endereco completo', function () {
    $dados = [
        'nome'            => 'Fernanda Oliveira',
        'cpf_cnpj'        => '111.444.777-35',
        'telefone'        => '(11) 98888-1111',
        'telefone_2'      => '(11) 3333-2222',
        'telefone_3'      => '(11) 97777-3333',
        'possui_whatsapp' => '1',
        'email'           => 'fernanda@teste.com',
        'instagram'       => 'fernanda.oliveira',
        'cep'             => '08900-000',
        'rua'             => 'Rua das Flores',
        'numero'          => '123',
        'complemento'     => 'Apto 45',
        'bairro'          => 'Centro',
        'cidade'          => 'Guararema',
        'estado'          => 'sp',
    ];

    $response = $this->actingAs($this->admin)->post(route('clientes.store'), $dados);
    $response->assertRedirect(route('clientes.index'));

    $cliente = Cliente::where('nome', 'Fernanda Oliveira')->first();
    expect($cliente)->not->toBeNull()
        ->and($cliente->telefone)->toBe('11988881111')
        ->and($cliente->telefone_2)->toBe('1133332222')
        ->and($cliente->telefone_3)->toBe('11977773333')
        ->and($cliente->instagram)->toBe('fernanda.oliveira')
        ->and($cliente->cep)->toBe('08900000')
        ->and($cliente->rua)->toBe('Rua das Flores')
        ->and($cliente->numero)->toBe('123')
        ->and($cliente->complemento)->toBe('Apto 45')
        ->and($cliente->bairro)->toBe('Centro')
        ->and($cliente->cidade)->toBe('Guararema')
        ->and($cliente->estado)->toBe('SP')
        ->and($cliente->endereco_completo)->toContain('Rua das Flores, 123');

    // Atualização
    $responseUpdate = $this->actingAs($this->admin)->put(route('clientes.update', $cliente->id), [
        'nome'       => 'Fernanda O. Silva',
        'telefone'   => '(11) 98888-1111',
        'telefone_2' => '(11) 9999-8888',
        'rua'        => 'Av. Brasil',
        'numero'     => '500',
        'cidade'     => 'São Paulo',
        'estado'     => 'SP',
    ]);
    $responseUpdate->assertRedirect(route('clientes.index'));

    $cliente->refresh();
    expect($cliente->nome)->toBe('Fernanda O. Silva')
        ->and($cliente->telefone_2)->toBe('1199998888')
        ->and($cliente->rua)->toBe('Av. Brasil')
        ->and($cliente->numero)->toBe('500')
        ->and($cliente->cidade)->toBe('São Paulo');
});

test('exibe acoes separadas de consultar informacoes e editar na listagem de clientes', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Roberto Albuquerque',
        'telefone'   => '11988884444',
        'cpf_cnpj'   => '11144477735',
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.index'));

    $response->assertStatus(200);
    $response->assertSee(route('clientes.show', $cliente->id), false);
    $response->assertSee(route('clientes.edit', $cliente->id), false);
    $response->assertSee('Consultar Informações');
    $response->assertSee('Editar');
});

test('exibe tela de consulta de informacoes do cliente com abas e dados completos', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Luciana Fernandes',
        'telefone'   => '11977771111',
        'cpf_cnpj'   => '11144477735',
        'email'      => 'luciana@teste.com',
        'rua'        => 'Rua das Palmeiras',
        'numero'     => '45',
        'bairro'     => 'Jardins',
        'cidade'     => 'São Paulo',
        'estado'     => 'SP',
    ]);

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Honda',
        'modelo'     => 'Civic',
        'ano'        => 2021,
        'placa'      => 'CIV1234',
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.show', $cliente->id));

    $response->assertStatus(200);
    $response->assertSee('Consultar Informações');
    $response->assertSee('Editar Cliente');
    $response->assertSee('Luciana Fernandes');
    $response->assertSee('luciana@teste.com');
    $response->assertSee('Honda Civic');
    $response->assertSee('CIV1234');
    $response->assertSee(route('clientes.edit', $cliente->id), false);
});

test('tela de edicao do cliente possui aba para consultar informacoes', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Marcos Vinicius',
        'telefone'   => '11966665555',
    ]);

    $response = $this->actingAs($this->admin)->get(route('clientes.edit', $cliente->id));

    $response->assertStatus(200);
    $response->assertSee('Consultar Informações');
    $response->assertSee('Editar Cliente');
    $response->assertSee(route('clientes.show', $cliente->id), false);
});

