<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\Plano;
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
        'nome_fantasia' => 'Oficina Arquivos',
        'razao_social'  => 'Oficina Arquivos Ltda',
        'cnpj'          => '12.345.678/0001-90',
        'plano_id'      => $plano->id,
    ]);
    $this->empresa->ativo = true;
    $this->empresa->save();

    $this->user = User::factory()->create(['empresa_id' => $this->empresa->id, 'role' => 'admin']);
    $cliente = Cliente::factory()->create(['empresa_id' => $this->empresa->id]);
    $veiculo = Veiculo::factory()->create(['empresa_id' => $this->empresa->id, 'cliente_id' => $cliente->id]);

    $this->ordem = OrdemServico::create([
        'empresa_id'         => $this->empresa->id,
        'numero_os'          => 'OS-0099',
        'cliente_id'         => $cliente->id,
        'veiculo_id'         => $veiculo->id,
        'user_id'            => $this->user->id,
        'status'             => 'aberta',
        'descricao_problema' => 'Teste',
        'valor_total'        => 0,
        'data_entrada'       => now(),
    ]);

    $path = UploadedFile::fake()->image('avaria.jpg')->store('os_fotos', 'public');

    $this->foto = $this->ordem->fotos()->create([
        'empresa_id'   => $this->empresa->id,
        'caminho_foto' => $path,
    ]);
});

test('foto de vistoria e servida pela URL assinada sem depender do symlink', function () {
    expect($this->foto->url)->toStartWith('/arquivos/fotos/')->not->toContain('/storage/');

    // Funciona mesmo sem login (pagina publica de aprovacao)
    $this->get($this->foto->url)->assertOk();
});

test('foto de vistoria sem assinatura valida e bloqueada', function () {
    $this->get('/arquivos/fotos/' . $this->foto->id)->assertForbidden();
    $this->get($this->foto->url . 'x')->assertForbidden();
});

test('logo da empresa e servido pela rota do laravel', function () {
    $this->empresa->update([
        'logo' => UploadedFile::fake()->image('logo.png')->store('logos', 'public'),
    ]);

    $this->get($this->empresa->fresh()->logo_url)->assertOk();
});

test('logo inexistente retorna 404', function () {
    $this->get(route('arquivos.logo', $this->empresa->id))->assertNotFound();
});
