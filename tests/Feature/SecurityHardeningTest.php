<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\OrdemServicoFoto;
use App\Models\Peca;
use App\Models\Plano;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->planoPro = Plano::create([
        'slug'         => 'pro',
        'nome'         => 'Pro',
        'preco_mensal' => 99.00,
        'max_usuarios' => 5,
        'ativo'        => true,
    ]);

    $this->planoEnterprise = Plano::create([
        'slug'         => 'enterprise',
        'nome'         => 'Enterprise',
        'preco_mensal' => 299.00,
        'max_usuarios' => 20,
        'ativo'        => true,
    ]);

    $this->empresaA = new Empresa([
        'nome_fantasia' => 'Oficina Alpha',
        'razao_social'  => 'Alpha Ltda',
        'cnpj'          => '11.111.111/0001-11',
        'plano_id'      => $this->planoPro->id,
    ]);
    $this->empresaA->ativo = true;
    $this->empresaA->save();

    Assinatura::create([
        'empresa_id'       => $this->empresaA->id,
        'plano_id'         => $this->planoPro->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'authorized',
        'preco_contratado' => 99.00,
        'data_inicio'      => now(),
        'valido_ate'       => now()->addMonth(),
    ]);

    $this->empresaB = new Empresa([
        'nome_fantasia' => 'Oficina Beta',
        'razao_social'  => 'Beta Ltda',
        'cnpj'          => '22.222.222/0001-22',
        'plano_id'      => $this->planoPro->id,
    ]);
    $this->empresaB->ativo = true;
    $this->empresaB->save();

    Assinatura::create([
        'empresa_id'       => $this->empresaB->id,
        'plano_id'         => $this->planoPro->id,
        'metodo_pagamento' => 'cartao',
        'status'           => 'authorized',
        'preco_contratado' => 99.00,
        'data_inicio'      => now(),
        'valido_ate'       => now()->addMonth(),
    ]);

    $this->adminA = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'admin',
        'password'   => Hash::make('password123'),
        'ativo'      => true,
    ]);

    $this->funcionarioA = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'funcionario',
        'password'   => Hash::make('password123'),
        'ativo'      => true,
    ]);

    $this->adminB = User::factory()->create([
        'empresa_id' => $this->empresaB->id,
        'role'       => 'admin',
        'password'   => Hash::make('password123'),
        'ativo'      => true,
    ]);

    $this->clienteA = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente Alpha 1',
        'telefone'   => '11999990001',
    ]);

    $this->veiculoA = Veiculo::create([
        'empresa_id' => $this->empresaA->id,
        'cliente_id' => $this->clienteA->id,
        'placa'      => 'ABC1D23',
        'marca'      => 'Volkswagen',
        'modelo'     => 'Gol 1.0',
        'ano'        => 2020,
    ]);

    $this->clienteB = Cliente::create([
        'empresa_id' => $this->empresaB->id,
        'nome'       => 'Cliente Beta 1',
        'telefone'   => '11999990002',
    ]);

    $this->veiculoB = Veiculo::create([
        'empresa_id' => $this->empresaB->id,
        'cliente_id' => $this->clienteB->id,
        'placa'      => 'XYZ9W87',
        'marca'      => 'Honda',
        'modelo'     => 'Civic 2.0',
        'ano'        => 2021,
    ]);
});

test('SEC-001: mass assignment in empresa update ignores plano_id and ativo', function () {
    $response = $this->actingAs($this->adminA)->from(route('empresa.edit'))->put(route('empresa.update'), [
        'nome_fantasia' => 'Oficina Alpha Atualizada',
        'plano_id'      => $this->planoEnterprise->id,
        'ativo'         => false,
    ]);

    $response->assertRedirect(route('empresa.edit'));

    $this->empresaA->refresh();
    expect($this->empresaA->nome_fantasia)->toBe('Oficina Alpha Atualizada');
    expect($this->empresaA->plano_id)->toBe($this->planoPro->id);
    expect($this->empresaA->ativo)->toBeTrue();
});

test('SEC-002: admin can deactivate employee and deactivated user cannot login', function () {
    // 1. Admin toggles status to inactive
    $toggleResponse = $this->actingAs($this->adminA)->patch(route('usuarios.toggle', $this->funcionarioA->id));
    $toggleResponse->assertRedirect(route('empresa.edit'));

    $this->funcionarioA->refresh();
    expect($this->funcionarioA->isAtivo())->toBeFalse();

    // 2. Clear current auth session so we can test guest login
    auth()->logout();

    // 3. Inactive employee attempts login
    $loginResponse = $this->post(route('login'), [
        'email'    => $this->funcionarioA->email,
        'password' => 'password123',
    ]);

    $loginResponse->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('SEC-002: active session of deactivated user is terminated on subsequent requests', function () {
    $this->funcionarioA->ativo = false;
    $this->funcionarioA->save();

    $response = $this->actingAs($this->funcionarioA)->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('SEC-002: admin cannot deactivate self', function () {
    $response = $this->actingAs($this->adminA)->patch(route('usuarios.toggle', $this->adminA->id));
    $response->assertStatus(422);

    $this->adminA->refresh();
    expect($this->adminA->isAtivo())->toBeTrue();
});

test('SEC-002: cross-tenant user deactivation is prohibited', function () {
    $response = $this->actingAs($this->adminA)->patch(route('usuarios.toggle', $this->adminB->id));
    $response->assertStatus(403);
});

test('SEC-002: funcionario cannot deactivate or delete another user', function () {
    $outroFuncionario = User::factory()->create([
        'empresa_id' => $this->empresaA->id,
        'role'       => 'funcionario',
        'ativo'      => true,
    ]);

    $this->actingAs($this->funcionarioA)
        ->patch(route('usuarios.toggle', $outroFuncionario->id))
        ->assertStatus(403);

    $this->actingAs($this->funcionarioA)
        ->delete(route('usuarios.destroy', $outroFuncionario->id))
        ->assertStatus(403);
});

test('SEC-004: SVG files are rejected for company logo upload', function () {
    Storage::fake('public');

    $svgFile = UploadedFile::fake()->create('malicious.svg', 10, 'image/svg+xml');

    $response = $this->actingAs($this->adminA)->put(route('empresa.update'), [
        'nome_fantasia' => 'Oficina Alpha',
        'logo'          => $svgFile,
    ]);

    $response->assertSessionHasErrors('logo');
});

test('SEC-007: OS cannot be created with a vehicle that belongs to a different client or company', function () {
    // Attempt with vehicle from another company
    $responseCrossCompany = $this->actingAs($this->adminA)->post(route('ordens.store'), [
        'cliente_id' => $this->clienteA->id,
        'veiculo_id' => $this->veiculoB->id,
    ]);
    $responseCrossCompany->assertSessionHasErrors('veiculo_id');

    // Attempt with vehicle belonging to a different client in same company
    $outroCliente = Cliente::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Cliente Alpha 2',
        'telefone'   => '11999990003',
    ]);

    $responseCrossClient = $this->actingAs($this->adminA)->post(route('ordens.store'), [
        'cliente_id' => $outroCliente->id,
        'veiculo_id' => $this->veiculoA->id,
    ]);
    $responseCrossClient->assertSessionHasErrors('veiculo_id');
});

test('SEC-010: funcionario cannot delete items or photos from an OS', function () {
    $os = OrdemServico::create([
        'empresa_id'         => $this->empresaA->id,
        'user_id'            => $this->adminA->id,
        'cliente_id'         => $this->clienteA->id,
        'veiculo_id'         => $this->veiculoA->id,
        'numero_os'          => 'OS-001',
        'status'             => 'aberta',
        'descricao_problema' => 'Barulho no motor',
        'data_entrada'       => now(),
    ]);

    $servico = Servico::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Troca de Óleo',
        'valor_base' => 150.00,
    ]);

    $item = OrdemServicoItem::create([
        'ordem_servico_id' => $os->id,
        'tipo_item'        => 'servico',
        'descricao'        => 'Troca de Óleo',
        'servico_id'       => $servico->id,
        'quantidade'       => 1,
        'valor_unitario'   => 150.00,
        'valor_total'      => 150.00,
    ]);

    $foto = OrdemServicoFoto::create([
        'empresa_id'       => $this->empresaA->id,
        'ordem_servico_id' => $os->id,
        'caminho_foto'     => 'fotos/test.jpg',
    ]);

    // Funcionario tries to delete item
    $this->actingAs($this->funcionarioA)
        ->delete(route('ordens.itens.destroy', [$os->id, $item->id]))
        ->assertStatus(403);

    // Funcionario tries to delete photo
    $this->actingAs($this->funcionarioA)
        ->delete(route('ordens.fotos.destroy', [$os->id, $foto->id]))
        ->assertStatus(403);
});

test('SEC-011: service cannot be created or updated with negative base value', function () {
    $responseStore = $this->actingAs($this->adminA)->post(route('servicos.store'), [
        'nome'       => 'Alinhamento',
        'valor_base' => -50.00,
    ]);
    $responseStore->assertSessionHasErrors('valor_base');

    $servico = Servico::create([
        'empresa_id' => $this->empresaA->id,
        'nome'       => 'Alinhamento',
        'valor_base' => 80.00,
    ]);

    $responseUpdate = $this->actingAs($this->adminA)->put(route('servicos.update', $servico->id), [
        'nome'       => 'Alinhamento 3D',
        'valor_base' => -10.00,
    ]);
    $responseUpdate->assertSessionHasErrors('valor_base');
});

test('SEC-012: OS cannot be updated with an invalid status', function () {
    $os = OrdemServico::create([
        'empresa_id'         => $this->empresaA->id,
        'user_id'            => $this->adminA->id,
        'cliente_id'         => $this->clienteA->id,
        'veiculo_id'         => $this->veiculoA->id,
        'numero_os'          => 'OS-002',
        'status'             => 'aberta',
        'descricao_problema' => 'Barulho na suspensão',
        'data_entrada'       => now(),
    ]);

    $response = $this->actingAs($this->adminA)->put(route('ordens.update', $os->id), [
        'cliente_id' => $this->clienteA->id,
        'veiculo_id' => $this->veiculoA->id,
        'status'     => 'status_invalido_hacker',
    ]);

    $response->assertSessionHasErrors('status');
});

test('SEC-013: security headers are present in HTTP responses', function () {
    $response = $this->get('/');

    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
});
