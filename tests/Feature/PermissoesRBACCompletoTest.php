<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\PerfilAcesso;
use App\Models\Permissao;
use App\Models\Plano;
use App\Models\Servico;
use App\Models\User;
use App\Support\Permissoes;
use Illuminate\Support\Facades\DB;

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
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'admin',
        'perfil_acesso_id' => null,
    ]);

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
        'empresa_id'       => $this->empresaB->id,
        'role'             => 'admin',
        'perfil_acesso_id' => null,
    ]);

    // Helper para criar perfil customizado
    $this->criarPerfil = function (Empresa $empresa, string $nome, array $permissoes) {
        $perfil = new PerfilAcesso(['nome' => $nome, 'descricao' => 'Perfil de teste']);
        $perfil->empresa_id = $empresa->id;
        $perfil->save();

        if (! empty($permissoes)) {
            $ids = Permissao::whereIn('slug', $permissoes)->pluck('id');
            $perfil->permissoes()->sync($ids);
        }

        return $perfil;
    };
});

/*
|--------------------------------------------------------------------------
| Grupo 1: Autorização de Rotas e Ações (1 a 8)
|--------------------------------------------------------------------------
*/

test('1. sem clientes.criar recebe 403 ao tentar GET /clientes/create e POST /clientes', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Sem Criar Clientes', ['clientes.visualizar']);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $this->actingAs($user)->get(route('clientes.create'))->assertStatus(403);

    $this->actingAs($user)->post(route('clientes.store'), [
        'nome'     => 'Cliente Teste',
        'telefone' => '11999999999',
    ])->assertStatus(403);
});

test('2. com clientes.criar acessa form e cria cliente com sucesso', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Com Criar Clientes', [
        'clientes.visualizar',
        'clientes.criar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $this->actingAs($user)->get(route('clientes.create'))->assertStatus(200);

    $response = $this->actingAs($user)->post(route('clientes.store'), [
        'nome'     => 'Novo Cliente Válido',
        'telefone' => '11988887777',
    ]);

    $response->assertRedirect(route('clientes.index'));
    expect(Cliente::where('nome', 'Novo Cliente Válido')->where('empresa_id', $this->empresaA->id)->exists())->toBeTrue();
});

test('3. sem servicos.excluir recebe 403 ao tentar DELETE /servicos/{id}', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Sem Excluir Servicos', [
        'servicos.visualizar',
        'servicos.editar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $servico = Servico::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Troca de Óleo',
        'valor_base' => 120.00,
    ]);

    $this->actingAs($user)->delete(route('servicos.destroy', $servico->id))->assertStatus(403);
    expect(Servico::find($servico->id))->not->toBeNull();
});

test('4. com servicos.excluir exclui servico da mesma empresa', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Com Excluir Servicos', [
        'servicos.visualizar',
        'servicos.excluir',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $servico = Servico::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Alinhamento',
        'valor_base' => 80.00,
    ]);

    $response = $this->actingAs($user)->delete(route('servicos.destroy', $servico->id));
    $response->assertRedirect(route('servicos.index'));
    expect(Servico::find($servico->id))->toBeNull();
});

test('5. funcionario sem permissao financeira ve dashboard com dados financeiros zerados e sem vazamento', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Apenas Operacional', [
        'dashboard.operacional.visualizar',
        'ordens.visualizar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);

    // Variáveis financeiras são zeradas / coleções vazias para evitar vazamento
    $response->assertViewHas('faturamentoTotal', 0.0);
    $response->assertViewHas('faturamentoMes', 0.0);
    expect($response->viewData('faturamentoChart')->isEmpty())->toBeTrue();
});

test('6. funcionario com dashboard.financeiro.visualizar ve totais e graficos financeiros', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Com Financeiro', [
        'dashboard.operacional.visualizar',
        'dashboard.financeiro.visualizar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertViewHas('faturamentoTotal');
    $response->assertViewHas('faturamentoMes');
    $response->assertViewHas('faturamentoChart');
});

test('7. usuario sem permissoes operacionais e financeiras de dashboard tem acesso negado 403', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Sem Dashboard', [
        'clientes.visualizar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $this->actingAs($user)->get(route('dashboard'))->assertStatus(403);
});

test('8. sem ordens.alterar_status recebe 403 ao tentar alterar status de OS', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Sem Alterar Status OS', [
        'ordens.visualizar',
        'ordens.editar',
    ]);
    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $cliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente OS Status',
        'telefone'   => '11999998888',
    ]);

    $veiculo = \App\Models\Veiculo::create([
        'empresa_id' => $this->empresaA->id,
        'cliente_id' => $cliente->id,
        'placa'      => 'ABC1D23',
        'marca'      => 'Volkswagen',
        'modelo'     => 'Gol 1.0',
        'ano'        => 2022,
    ]);

    $os = OrdemServico::create([
        'empresa_id'         => $this->empresaA->id,
        'user_id'            => $user->id,
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'numero_os'          => 'OS-STATUS-1',
        'status'             => 'aberta',
        'descricao_problema' => 'Barulho no motor',
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($user)->put(route('ordens.update', $os->id), [
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'descricao_problema' => 'Barulho no motor',
        'status'             => 'em_andamento',
    ]);

    $response->assertStatus(403);
    expect($os->fresh()->status)->toBe('aberta');
});

/*
|--------------------------------------------------------------------------
| Grupo 2: Perfis de Acesso (9 a 14)
|--------------------------------------------------------------------------
*/

test('9. criacao de perfil com lista personalizada salva vinculos em perfil_permissao', function () {
    $response = $this->actingAs($this->adminA)->post(route('perfis.store'), [
        'nome'       => 'Consultor Técnico',
        'descricao'  => 'Perfil técnico especializado',
        'permissoes' => ['clientes.visualizar', 'veiculos.visualizar', 'pecas.visualizar'],
    ]);

    $response->assertRedirect(route('empresa.edit'));

    $perfil = PerfilAcesso::where('nome', 'Consultor Técnico')->where('empresa_id', $this->empresaA->id)->first();
    expect($perfil)->not->toBeNull();
    expect($perfil->permissoes()->pluck('slug')->all())->toEqualCanonicalizing([
        'clientes.visualizar',
        'veiculos.visualizar',
        'pecas.visualizar',
    ]);
});

test('10. edicao de perfil atualiza permissoes sem corromper perfis de outras empresas', function () {
    $perfilA = ($this->criarPerfil)($this->empresaA, 'Perfil A', ['clientes.visualizar']);
    $perfilB = ($this->criarPerfil)($this->empresaB, 'Perfil B', ['servicos.visualizar']);

    $response = $this->actingAs($this->adminA)->put(route('perfis.update', $perfilA->id), [
        'nome'       => 'Perfil A Atualizado',
        'descricao'  => 'Descricao A atualizada',
        'permissoes' => ['clientes.visualizar', 'clientes.criar'],
    ]);

    $response->assertRedirect(route('empresa.edit'));

    expect($perfilA->fresh()->permissoes()->pluck('slug')->all())->toEqualCanonicalizing([
        'clientes.visualizar',
        'clientes.criar',
    ]);

    // Perfil B permanece intacto
    expect($perfilB->fresh()->permissoes()->pluck('slug')->all())->toEqualCanonicalizing([
        'servicos.visualizar',
    ]);
});

test('11. exclusao de perfil sem usuarios vinculados ocorre com sucesso', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Perfil Descartavel', ['clientes.visualizar']);

    $response = $this->actingAs($this->adminA)->delete(route('perfis.destroy', $perfil->id));
    $response->assertRedirect(route('empresa.edit'));

    expect(PerfilAcesso::find($perfil->id))->toBeNull();
});

test('12. exclusao de perfil com usuarios ativos vinculados e bloqueada', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Perfil Em Uso', ['clientes.visualizar']);
    User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    $response = $this->actingAs($this->adminA)->delete(route('perfis.destroy', $perfil->id));
    $response->assertSessionHas('error');
    expect(PerfilAcesso::find($perfil->id))->not->toBeNull();
});

test('13. perfil de acesso nao pode ser salvo com permissoes reservadas a administradores', function () {
    $response = $this->actingAs($this->adminA)->post(route('perfis.store'), [
        'nome'       => 'Perfil Tentando Invasao',
        'permissoes' => ['empresa.visualizar', 'assinatura.gerenciar', 'clientes.visualizar'],
    ]);

    $response->assertSessionHasErrors('permissoes');
    expect(PerfilAcesso::where('nome', 'Perfil Tentando Invasao')->exists())->toBeFalse();
});

test('14. nomes de perfil sao unicos na mesma empresa mas permitidos em empresas diferentes', function () {
    ($this->criarPerfil)($this->empresaA, 'Especialista', ['clientes.visualizar']);

    // Tentativa na mesma empresa A com o mesmo nome deve falhar na validação
    $responseDuplicado = $this->actingAs($this->adminA)->post(route('perfis.store'), [
        'nome'       => 'Especialista',
        'permissoes' => ['pecas.visualizar'],
    ]);
    $responseDuplicado->assertSessionHasErrors('nome');

    // Tentativa na empresa B com o mesmo nome deve ser aceita normalmente
    $responseEmpresaB = $this->actingAs($this->adminB)->post(route('perfis.store'), [
        'nome'       => 'Especialista',
        'permissoes' => ['pecas.visualizar'],
    ]);
    $responseEmpresaB->assertRedirect(route('empresa.edit'));
    expect(PerfilAcesso::withoutGlobalScopes()->where('nome', 'Especialista')->count())->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Grupo 3: Isolamento Multi-tenant (15 a 19)
|--------------------------------------------------------------------------
*/

test('15. usuario da empresa A nao visualiza perfil de acesso da empresa B (403 ou 404)', function () {
    $perfilB = ($this->criarPerfil)($this->empresaB, 'Perfil Secreto B', ['clientes.visualizar']);

    $response = $this->actingAs($this->adminA)->get(route('perfis.edit', $perfilB->id));
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();
});

test('16. usuario da empresa A nao consegue atualizar nem excluir perfil da empresa B', function () {
    $perfilB = ($this->criarPerfil)($this->empresaB, 'Perfil Protegido B', ['clientes.visualizar']);

    $updateResp = $this->actingAs($this->adminA)->put(route('perfis.update', $perfilB->id), [
        'nome'       => 'Tentativa Hack',
        'permissoes' => ['clientes.visualizar'],
    ]);
    expect(in_array($updateResp->status(), [403, 404], true))->toBeTrue();

    $deleteResp = $this->actingAs($this->adminA)->delete(route('perfis.destroy', $perfilB->id));
    expect(in_array($deleteResp->status(), [403, 404], true))->toBeTrue();
    expect(PerfilAcesso::withoutGlobalScopes()->find($perfilB->id))->not->toBeNull();
});

test('17. usuario da empresa A nao consegue atribuir a funcionario perfil da empresa B', function () {
    $perfilB = ($this->criarPerfil)($this->empresaB, 'Perfil de B', ['clientes.visualizar']);

    $response = $this->actingAs($this->adminA)->post(route('usuarios.store'), [
        'name'                  => 'Funcionario Cross Tenant',
        'email'                 => 'cross_' . uniqid() . '@alpha.com',
        'password'              => 'P@ssw0rdOficina#9',
        'password_confirmation' => 'P@ssw0rdOficina#9',
        'perfil_acesso_id'      => $perfilB->id,
    ]);

    $response->assertSessionHasErrors('perfil_acesso_id');
});

test('18. usuario da empresa A com clientes.visualizar nao ve cliente da empresa B', function () {
    $perfilA = ($this->criarPerfil)($this->empresaA, 'Atendente A', ['clientes.visualizar']);
    $userA = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfilA->id,
    ]);

    $clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente Confidencial B',
        'telefone'   => '11988880000',
    ]);

    $response = $this->actingAs($userA)->get(route('clientes.show', $clienteB->id));
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();
});

test('19. usuario da empresa A com clientes.excluir nao consegue excluir cliente da empresa B', function () {
    $perfilA = ($this->criarPerfil)($this->empresaA, 'Gerente A', ['clientes.visualizar', 'clientes.excluir']);
    $userA = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfilA->id,
    ]);

    $clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente Intocavel B',
        'telefone'   => '11977770000',
    ]);

    $response = $this->actingAs($userA)->delete(route('clientes.destroy', $clienteB->id));
    expect(in_array($response->status(), [403, 404], true))->toBeTrue();
    expect(Cliente::withoutGlobalScopes()->find($clienteB->id))->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Grupo 4: Usuários e Compatibilidade Legada (20 a 24)
|--------------------------------------------------------------------------
*/

test('20. administrador tem acesso a todas as permissoes operacionais e administrativas sem perfil_acesso_id', function () {
    expect($this->adminA->perfil_acesso_id)->toBeNull();

    expect($this->adminA->temPermissao('clientes.criar'))->toBeTrue();
    expect($this->adminA->temPermissao('servicos.excluir'))->toBeTrue();
    expect($this->adminA->temPermissao('dashboard.financeiro.visualizar'))->toBeTrue();
    expect($this->adminA->temPermissao('empresa.visualizar'))->toBeTrue();
    expect($this->adminA->temPermissao('assinatura.gerenciar'))->toBeTrue();
});

test('21. usuario legado sem perfil_acesso_id tem permissoes resolvidas pelo papel legado em runtime', function () {
    $gerenteLegado = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'gerente',
        'perfil_acesso_id' => null,
    ]);

    $funcLegado = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => null,
    ]);

    // Gerente tem exclusão e financeiro
    expect($gerenteLegado->temPermissao('clientes.excluir'))->toBeTrue();
    expect($gerenteLegado->temPermissao('dashboard.financeiro.visualizar'))->toBeTrue();
    expect($gerenteLegado->temPermissao('empresa.visualizar'))->toBeFalse(); // Reservada!

    // Funcionário não tem exclusão
    expect($funcLegado->temPermissao('clientes.visualizar'))->toBeTrue();
    expect($funcLegado->temPermissao('clientes.excluir'))->toBeFalse();
    expect($funcLegado->temPermissao('empresa.visualizar'))->toBeFalse();
});

test('22. criacao de usuario com perfil selecionado persiste perfil_acesso_id e aplica permissoes', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Perfil Selecionado', ['pecas.criar', 'pecas.visualizar']);
    $email = 'func_perfil_' . uniqid() . '@alpha.com';

    $response = $this->actingAs($this->adminA)->post(route('usuarios.store'), [
        'name'                  => 'Usuario Com Perfil',
        'email'                 => $email,
        'password'              => 'P@ssw0rdOficina#9',
        'password_confirmation' => 'P@ssw0rdOficina#9',
        'perfil_acesso_id'      => $perfil->id,
    ]);

    $response->assertRedirect(route('empresa.edit'));

    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->perfil_acesso_id)->toBe($perfil->id);
    expect($user->temPermissao('pecas.criar'))->toBeTrue();
    expect($user->temPermissao('clientes.criar'))->toBeFalse();
});

test('23. edicao de usuario altera perfil_acesso_id e reflete imediatamente no cache', function () {
    $perfil1 = ($this->criarPerfil)($this->empresaA, 'Perfil 1', ['pecas.visualizar']);
    $perfil2 = ($this->criarPerfil)($this->empresaA, 'Perfil 2', ['pecas.visualizar', 'pecas.excluir']);

    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil1->id,
    ]);

    expect($user->temPermissao('pecas.excluir'))->toBeFalse();

    $response = $this->actingAs($this->adminA)->put(route('usuarios.update', $user->id), [
        'name'             => $user->name,
        'email'            => $user->email,
        'perfil_acesso_id' => $perfil2->id,
        'ativo'            => 1,
    ]);

    $response->assertRedirect(route('empresa.edit'));

    $user->refresh();
    $user->limparCachePermissoes();
    expect($user->perfil_acesso_id)->toBe($perfil2->id);
    expect($user->temPermissao('pecas.excluir'))->toBeTrue();
});

test('24. alteracao de permissoes de um perfil reflete imediatamente nos usuarios sem relogin', function () {
    $perfil = ($this->criarPerfil)($this->empresaA, 'Perfil Dinamico', ['veiculos.visualizar']);

    $user = User::factory()->create([
        'empresa_id'       => $this->empresaA->id,
        'role'             => 'funcionario',
        'perfil_acesso_id' => $perfil->id,
    ]);

    expect($user->temPermissao('veiculos.criar'))->toBeFalse();

    // Administrador adiciona 'veiculos.criar' ao perfil
    $this->actingAs($this->adminA)->put(route('perfis.update', $perfil->id), [
        'nome'       => 'Perfil Dinamico',
        'permissoes' => ['veiculos.visualizar', 'veiculos.criar'],
    ]);

    // Em uma nova requisição do usuário (ou ao limpar o cache por requisição):
    $userFresh = $user->fresh();
    $userFresh->limparCachePermissoes();
    expect($userFresh->temPermissao('veiculos.criar'))->toBeTrue();
});
