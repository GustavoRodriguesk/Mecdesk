<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\PerfilAcesso;
use App\Models\Permissao;
use App\Models\Plano;
use App\Models\User;
use App\Models\Veiculo;
use App\Support\Permissoes;

function criarPerfil(int $empresaId, string $nome, array $permissoes = []): PerfilAcesso
{
    $perfil = new PerfilAcesso(['nome' => $nome]);
    $perfil->empresa_id = $empresaId;
    $perfil->save();

    if (! empty($permissoes)) {
        $ids = Permissao::whereIn('slug', $permissoes)->pluck('id');
        $perfil->permissoes()->sync($ids);
    }

    return $perfil;
}

beforeEach(function () {
    Permissoes::sincronizar();

    $this->plano = Plano::firstOrCreate(
        ['slug' => 'pro'],
        [
            'nome' => 'Pro',
            'preco_mensal' => 99.00,
            'max_usuarios' => 10,
            'ativo' => true,
        ]
    );

    // Empresa A
    $this->empresaA = new Empresa([
        'nome_fantasia' => 'Oficina Alpha',
        'razao_social'  => 'Oficina Alpha Ltda',
        'cnpj'          => '11.111.111/0001-11',
        'plano_id'      => $this->plano->id,
    ]);
    $this->empresaA->ativo = true;
    $this->empresaA->save();

    Assinatura::create([
        'empresa_id'       => $this->empresaA->id,
        'plano_id'         => $this->plano->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'authorized',
        'preco_contratado' => 99.00,
        'data_inicio'      => now(),
        'valido_ate'       => now()->addMonth(),
    ]);

    $this->adminA = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'admin',
        'ativo'      => true,
    ]);

    // Empresa B (Tenant concorrente)
    $this->empresaB = new Empresa([
        'nome_fantasia' => 'Oficina Beta',
        'razao_social'  => 'Oficina Beta Ltda',
        'cnpj'          => '22.222.222/0001-22',
        'plano_id'      => $this->plano->id,
    ]);
    $this->empresaB->ativo = true;
    $this->empresaB->save();

    Assinatura::create([
        'empresa_id'       => $this->empresaB->id,
        'plano_id'         => $this->plano->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'authorized',
        'preco_contratado' => 99.00,
        'data_inicio'      => now(),
        'valido_ate'       => now()->addMonth(),
    ]);

    $this->adminB = User::factory()->create([
        'empresa_id' => $this->empresaB->id,
        'role'       => 'admin',
        'ativo'      => true,
    ]);
});

/*
|--------------------------------------------------------------------------
| 1. Proteção de Rotas Soltas & Autorização (/clientes/{cliente}/veiculos)
|--------------------------------------------------------------------------
*/
test('bloqueia rota /clientes/{cliente}/veiculos para usuario sem permissao clientes.visualizar', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente Alpha',
        'telefone'   => '11999990001',
    ]);

    Veiculo::create([
        'empresa_id' => $this->empresaA->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Fiat',
        'modelo'     => 'Uno',
        'ano'        => 2020,
        'placa'      => 'ABC1D23',
    ]);

    // Perfil SEM clientes.visualizar
    $perfil = criarPerfil($this->empresaA->id, 'Apenas OS', ['ordens.visualizar']);

    $funcionario = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
        'ativo'            => true,
    ]);

    $response = $this->actingAs($funcionario)->get("/clientes/{$cliente->id}/veiculos");
    $response->assertStatus(403);
});

test('permite rota /clientes/{cliente}/veiculos para usuario com permissao clientes.visualizar', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente Alpha 2',
        'telefone'   => '11999990002',
    ]);

    Veiculo::create([
        'empresa_id' => $this->empresaA->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'VW',
        'modelo'     => 'Gol',
        'ano'        => 2021,
        'placa'      => 'GOL1A23',
    ]);

    $perfil = criarPerfil($this->empresaA->id, 'Ver Clientes', ['clientes.visualizar']);

    $funcionario = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
        'ativo'            => true,
    ]);

    $response = $this->actingAs($funcionario)->get("/clientes/{$cliente->id}/veiculos");
    $response->assertStatus(200);
    $response->assertJsonFragment(['placa' => 'GOL1A23']);
});

test('impede acesso cruzado de tenants em /clientes/{cliente}/veiculos', function () {
    $clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente da Oficina Beta',
        'telefone'   => '11999990003',
    ]);

    // Admin da empresa A tentando acessar clientes da empresa B
    $response = $this->actingAs($this->adminA)->get("/clientes/{$clienteB->id}/veiculos");
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 2. Prevenção de Elevação de Privilégios no Cadastro de Usuários
|--------------------------------------------------------------------------
*/
test('rejeita tentativa de cadastrar usuario com role admin via POST /usuarios', function () {
    $response = $this->actingAs($this->adminA)->post(route('usuarios.store'), [
        'name'                  => 'Rogue Admin',
        'email'                 => 'rogue_admin@alpha.com',
        'password'              => 'P@ssw0rdOficina#9',
        'password_confirmation' => 'P@ssw0rdOficina#9',
        'role'                  => 'admin',
    ]);

    $response->assertSessionHasErrors(['role']);
    expect(User::where('email', 'rogue_admin@alpha.com')->exists())->toBeFalse();
});

test('impede associar perfil_acesso de outro tenant no cadastro de funcionario', function () {
    $perfilBeta = criarPerfil($this->empresaB->id, 'Perfil Confidencial Beta');

    $response = $this->actingAs($this->adminA)->post(route('usuarios.store'), [
        'name'                  => 'Funcionario Alpha',
        'email'                 => 'func_cross@alpha.com',
        'password'              => 'P@ssw0rdOficina#9',
        'password_confirmation' => 'P@ssw0rdOficina#9',
        'perfil_acesso_id'      => $perfilBeta->id,
    ]);

    $response->assertSessionHasErrors(['perfil_acesso_id']);
    expect(User::where('email', 'func_cross@alpha.com')->exists())->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| 3. Auto-Edição de Perfil (ProfileController): Impossibilidade de Mass Assignment
|--------------------------------------------------------------------------
*/
test('usuario comum nao consegue alterar seu proprio papel ou perfil via PATCH /profile', function () {
    $perfil = criarPerfil($this->empresaA->id, 'Perfil Simples');

    $funcionario = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
        'ativo'            => true,
    ]);

    $response = $this->actingAs($funcionario)->patch(route('profile.update'), [
        'name'             => 'Funcionario Alterado',
        'email'            => $funcionario->email,
        'role'             => 'admin',
        'perfil_acesso_id' => null,
        'empresa_id'       => $this->empresaB->id,
    ]);

    $response->assertSessionHasNoErrors();
    $funcionario->refresh();

    expect($funcionario->name)->toBe('Funcionario Alterado')
        ->and($funcionario->role)->toBe('funcionario')
        ->and($funcionario->perfil_acesso_id)->toBe($perfil->id)
        ->and($funcionario->empresa_id)->toBe($this->empresaA->id);
});

/*
|--------------------------------------------------------------------------
| 4. Desativação Imediata de Sessão para Usuários Inativos
|--------------------------------------------------------------------------
*/
test('usuario desativado tem sessao invalidada imediatamente ao acessar qualquer rota operacional', function () {
    $funcionario = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'funcionario',
        'ativo'      => false,
    ]);

    $response = $this->actingAs($funcionario)->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error');
    $this->assertGuest();
});

test('usuario inativo tem temPermissao sempre falso mesmo para admin', function () {
    $adminInativo = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'admin',
        'ativo'      => false,
    ]);

    expect($adminInativo->temPermissao('clientes.visualizar'))->toBeFalse()
        ->and($adminInativo->temPermissao('empresa.visualizar'))->toBeFalse()
        ->and($adminInativo->canManageCompany())->toBeFalse()
        ->and($adminInativo->canManageSubscription())->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| 5. Bloqueio de Permissões Reservadas e Isolamento na Gestão de Perfis
|--------------------------------------------------------------------------
*/
test('impede inclusao de permissoes de empresa e assinatura em perfis customizados', function () {
    $response = $this->actingAs($this->adminA)->post(route('perfis.store'), [
        'nome'       => 'Perfil Tentativa Escalar',
        'permissoes' => ['clientes.visualizar', 'empresa.visualizar', 'assinatura.gerenciar'],
    ]);

    $response->assertSessionHasErrors(['permissoes']);
    expect(PerfilAcesso::where('nome', 'Perfil Tentativa Escalar')->exists())->toBeFalse();
});

test('impede visualizar ou editar perfis de outra empresa', function () {
    $perfilBeta = criarPerfil($this->empresaB->id, 'Perfil Beta Confidencial');

    // Admin da empresa A tentando acessar tela de edição do perfil da empresa B
    $response = $this->actingAs($this->adminA)->get(route('perfis.edit', $perfilBeta->id));
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();

    // Admin da empresa A tentando atualizar o perfil da empresa B
    $responseUpdate = $this->actingAs($this->adminA)->put(route('perfis.update', $perfilBeta->id), [
        'nome'       => 'Perfil Beta Hackeado',
        'permissoes' => ['clientes.visualizar'],
    ]);
    expect(in_array($responseUpdate->status(), [403, 404], true))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 6. Proteção de Documentos e PDFs por Tenant
|--------------------------------------------------------------------------
*/
test('impede gerar PDF de ordem de servico pertencente a outro tenant', function () {
    $clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente Beta OS',
        'telefone'   => '11988880000',
    ]);

    $veiculoB = Veiculo::create([
        'empresa_id' => $this->empresaB->id,
        'cliente_id' => $clienteB->id,
        'marca'      => 'VW',
        'modelo'     => 'Fox',
        'ano'        => 2019,
        'placa'      => 'FOX1234',
    ]);

    $ordemB = OrdemServico::create([
        'empresa_id'         => $this->empresaB->id,
        'numero_os'          => 'OS-B001',
        'cliente_id'         => $clienteB->id,
        'veiculo_id'         => $veiculoB->id,
        'user_id'            => $this->adminB->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Problema Beta',
        'valor_total'        => 500.00,
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($this->adminA)->get(route('ordens.pdf', $ordemB->id));
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 7. Validação de UUIDs Públicos (Opção B)
|--------------------------------------------------------------------------
*/
test('entidades geram UUID automaticamente e preservam ID inteiro interno', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente UUID Teste',
        'telefone'   => '11999991234',
    ]);

    expect($cliente->id)->toBeInt()
        ->and($cliente->uuid)->not->toBeNull()
        ->and(Illuminate\Support\Str::isUuid($cliente->uuid))->toBeTrue();

    $veiculo = Veiculo::create([
        'empresa_id' => $this->empresaA->id,
        'cliente_id' => $cliente->id,
        'marca'      => 'Fiat',
        'modelo'     => 'Pulse',
        'ano'        => 2023,
        'placa'      => 'PUL1234',
    ]);

    expect($veiculo->uuid)->not->toBeNull()
        ->and(Illuminate\Support\Str::isUuid($veiculo->uuid))->toBeTrue();

    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresaA->id,
        'numero_os'          => 'OS-UUID-01',
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'user_id'            => $this->adminA->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Teste UUID',
        'valor_total'        => 100.00,
        'data_entrada'       => now(),
    ]);

    expect($ordem->uuid)->not->toBeNull()
        ->and(Illuminate\Support\Str::isUuid($ordem->uuid))->toBeTrue();
});

test('rotas geradas com o model utilizam UUID publico na URL em vez do id sequencial', function () {
    $cliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente URL UUID',
        'telefone'   => '11999995678',
    ]);

    $urlShow = route('clientes.show', $cliente);
    $urlEdit = route('clientes.edit', $cliente);

    expect($urlShow)->toContain('/clientes/' . $cliente->uuid)
        ->and($urlShow)->not->toContain('/clientes/' . $cliente->id)
        ->and($urlEdit)->toContain('/clientes/' . $cliente->uuid . '/edit');
});

test('resolve route model binding com sucesso via UUID e bloqueia UUID de outro tenant', function () {
    $clienteA = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente Alpha UUID',
        'telefone'   => '11999990001',
    ]);

    $clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente Beta UUID',
        'telefone'   => '11999990002',
    ]);

    // Acesso legítimo ao próprio cliente usando UUID
    $resA = $this->actingAs($this->adminA)->get(route('clientes.show', $clienteA));
    $resA->assertStatus(200);
    $resA->assertSee('Cliente Alpha UUID');

    // Tentativa de acessar cliente de outro tenant usando o UUID do concorrente
    $resCross = $this->actingAs($this->adminA)->get('/clientes/' . $clienteB->uuid);
    $resCross->assertStatus(404);

    // Tentativa de acessar com UUID inexistente
    $resFake = $this->actingAs($this->adminA)->get('/clientes/' . Illuminate\Support\Str::uuid());
    $resFake->assertStatus(404);
});

