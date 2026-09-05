<?php

use App\Models\Assinatura;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\OrdemServicoFoto;
use App\Models\OrdemServicoItem;
use App\Models\Peca;
use App\Models\Plano;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

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

    $this->servico = Servico::create([
        'empresa_id' => $this->empresa->id,
        'nome'       => 'Alinhamento',
        'valor_base' => 100.00,
    ]);

    $this->peca = Peca::create([
        'empresa_id'     => $this->empresa->id,
        'nome'           => 'Pastilha',
        'codigo'         => 'PST-1',
        'estoque'        => 10,
        'valor_unitario' => 50.00,
    ]);
});

test('OS aprovada bloqueia adicao de servicos e pecas', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0010',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aprovada',
        'descricao_problema' => 'Freios',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
    ]);

    // Tentativa de adicionar serviço via JSON
    $responseServico = $this->actingAs($this->user)->postJson(route('ordens.itens.store', $ordem->id), [
        'servico_id'     => $this->servico->id,
        'quantidade'     => 1,
        'valor_unitario' => 100.00,
    ]);

    $responseServico->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em peças ou serviços, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    // Tentativa de adicionar peça via JSON
    $responsePeca = $this->actingAs($this->user)->postJson(route('ordens.itens.peca.store', $ordem->id), [
        'peca_id'        => $this->peca->id,
        'quantidade'     => 1,
        'valor_unitario' => 50.00,
    ]);

    $responsePeca->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em peças ou serviços, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    expect($ordem->itens()->count())->toBe(0);
});

test('OS aprovada bloqueia edicao e exclusao de itens existentes', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0011',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Revisão',
        'valor_total'        => 100.00,
        'data_entrada'       => now(),
    ]);

    $item = OrdemServicoItem::create([
        'ordem_servico_id' => $ordem->id,
        'tipo_item'        => 'servico',
        'servico_id'       => $this->servico->id,
        'descricao'        => 'Alinhamento',
        'quantidade'       => 1,
        'valor_unitario'   => 100.00,
        'valor_total'      => 100.00,
    ]);

    // Muda o status para aprovada
    $ordem->update(['status' => 'aprovada']);

    // Tentativa de atualizar item
    $responseUpdate = $this->actingAs($this->user)->putJson(route('ordens.itens.update', $item->id), [
        'descricao'      => 'Alinhamento Modificado',
        'quantidade'     => 2,
        'valor_unitario' => 120.00,
    ]);

    $responseUpdate->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em peças ou serviços, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    // Tentativa de excluir item
    $responseDelete = $this->actingAs($this->user)->deleteJson(route('ordens.itens.destroy', $item->id));

    $responseDelete->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em peças ou serviços, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    expect(OrdemServicoItem::find($item->id))->not->toBeNull();
});

test('OS aprovada bloqueia upload e exclusao de fotos de avarias', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0012',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Funilaria',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
    ]);

    $foto = $ordem->fotos()->create([
        'empresa_id'   => $this->empresa->id,
        'caminho_foto' => 'os_fotos/foto_teste.jpg',
    ]);

    // Muda o status para aprovada
    $ordem->update(['status' => 'aprovada']);

    // Tentativa de upload de nova foto
    $file = UploadedFile::fake()->image('avaria.jpg');
    $responseUpload = $this->actingAs($this->user)->postJson(route('ordens.fotos.store', $ordem->id), [
        'fotos' => [$file],
    ]);

    $responseUpload->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em imagens de avarias, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    // Tentativa de excluir foto
    $responseDelete = $this->actingAs($this->user)->deleteJson(route('ordens.fotos.destroy', $foto->id));

    $responseDelete->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Para realizar alterações em imagens de avarias, a Ordem de Serviço deve estar em condição Aberta.',
        ]);

    expect(OrdemServicoFoto::find($foto->id))->not->toBeNull();
});

test('quando a OS retorna para aberta, todas as alteracoes sao liberadas', function () {
    $ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0013',
        'cliente_id'         => $this->cliente->id,
        'veiculo_id'         => $this->veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aprovada',
        'descricao_problema' => 'Suspensão',
        'valor_total'        => 0.00,
        'data_entrada'       => now(),
    ]);

    // Altera para aberta
    $ordem->update(['status' => 'aberta']);

    $responseServico = $this->actingAs($this->user)->postJson(route('ordens.itens.store', $ordem->id), [
        'servico_id'     => $this->servico->id,
        'quantidade'     => 1,
        'valor_unitario' => 100.00,
    ]);

    $responseServico->assertStatus(201)
        ->assertJson([
            'success' => true,
        ]);

    expect($ordem->itens()->count())->toBe(1);
});
