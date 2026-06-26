<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OrdemServico;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Scopes\EmpresaScope;
use Illuminate\Support\Str;

beforeEach(function () {
    // 1. Criar empresa
    $this->empresa = Empresa::create([
        'nome_fantasia' => 'Oficina Mecânica Teste',
        'razao_social' => 'Oficina Teste Ltda',
        'cnpj' => '12.345.678/0001-90',
        'ativo' => true,
    ]);

    // 2. Criar usuário associado a essa empresa
    $this->user = User::factory()->create([
        'empresa_id' => $this->empresa->id,
        'role' => 'admin', // garante privilégios administrativos no sistema se necessário
    ]);

    // 3. Criar cliente e veículo
    $this->cliente = Cliente::factory()->create([
        'empresa_id' => $this->empresa->id,
    ]);

    $this->veiculo = Veiculo::factory()->create([
        'empresa_id' => $this->empresa->id,
        'cliente_id' => $this->cliente->id,
    ]);
});

test('public approval page returns 404 for non-existent token', function () {
    $response = $this->get('/aprovacao/token-invalido-123');
    $response->assertStatus(404);
});

test('public approval page displays OS details for valid token', function () {
    $token = (string) Str::uuid();

    // Criar OS sem EmpresaScope ativo ou injetando empresa_id diretamente
    $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)->create([
        'empresa_id' => $this->empresa->id,
        'numero_os' => 'OS-9999',
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'user_id' => $this->user->id,
        'status' => 'aguardando_aprovacao',
        'descricao_problema' => 'Problema de teste',
        'valor_total' => 1500.00,
        'aprovado_cliente' => false,
        'data_entrada' => now(),
        'approval_token' => $token,
        'approval_status' => 'pending',
        'approval_requested_at' => now(),
    ]);

    $response = $this->get("/aprovacao/{$token}");

    $response->assertOk()
        ->assertSee('OS-9999')
        ->assertSee($this->cliente->nome)
        ->assertSee($this->veiculo->modelo)
        ->assertSee('1.500,00')
        ->assertSee('Sua aprovação é necessária');
});

test('public approval route updates OS to approved', function () {
    $token = (string) Str::uuid();

    $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)->create([
        'empresa_id' => $this->empresa->id,
        'numero_os' => 'OS-8888',
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'user_id' => $this->user->id,
        'status' => 'aguardando_aprovacao',
        'descricao_problema' => 'Problema no motor',
        'valor_total' => 2350.00,
        'aprovado_cliente' => false,
        'data_entrada' => now(),
        'approval_token' => $token,
        'approval_status' => 'pending',
        'approval_requested_at' => now(),
    ]);

    $response = $this->post("/aprovacao/{$token}/aprovar");

    $response->assertRedirect("/aprovacao/{$token}")
        ->assertSessionHas('success');

    $ordem->refresh();

    expect($ordem->approval_status)->toBe('approved')
        ->and($ordem->status)->toBe('aprovada')
        ->and($ordem->approval_response_at)->not->toBeNull()
        ->and($ordem->approval_ip)->not->toBeNull()
        ->and($ordem->approval_user_agent)->not->toBeNull();

    // Verifica que o histórico foi registrado
    $this->assertDatabaseHas('ordem_servico_historicos', [
        'ordem_servico_id' => $ordem->id,
        'status' => 'aprovada',
    ]);
});

test('public approval route updates OS to rejected with a comment', function () {
    $token = (string) Str::uuid();

    $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)->create([
        'empresa_id' => $this->empresa->id,
        'numero_os' => 'OS-7777',
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'user_id' => $this->user->id,
        'status' => 'aguardando_aprovacao',
        'descricao_problema' => 'Barulho na suspensão',
        'valor_total' => 1200.00,
        'aprovado_cliente' => false,
        'data_entrada' => now(),
        'approval_token' => $token,
        'approval_status' => 'pending',
        'approval_requested_at' => now(),
    ]);

    // Comentário em branco deve falhar
    $responseError = $this->post("/aprovacao/{$token}/reprovar", ['approval_comment' => '']);
    $responseError->assertSessionHasErrors('approval_comment');

    // Comentário válido deve funcionar
    $response = $this->post("/aprovacao/{$token}/reprovar", [
        'approval_comment' => 'Muito caro',
    ]);

    $response->assertRedirect("/aprovacao/{$token}")
        ->assertSessionHas('success');

    $ordem->refresh();

    expect($ordem->approval_status)->toBe('rejected')
        ->and($ordem->status)->toBe('reprovada')
        ->and($ordem->approval_comment)->toBe('Muito caro')
        ->and($ordem->approval_response_at)->not->toBeNull()
        ->and($ordem->approval_ip)->not->toBeNull()
        ->and($ordem->approval_user_agent)->not->toBeNull();

    // Verifica que o histórico foi registrado
    $this->assertDatabaseHas('ordem_servico_historicos', [
        'ordem_servico_id' => $ordem->id,
        'status' => 'reprovada',
    ]);
});

test('responded public page shows responded status and blocks actions', function () {
    $token = (string) Str::uuid();

    $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)->create([
        'empresa_id' => $this->empresa->id,
        'numero_os' => 'OS-5555',
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'user_id' => $this->user->id,
        'status' => 'aprovada',
        'descricao_problema' => 'Problema elétrico',
        'valor_total' => 450.00,
        'aprovado_cliente' => true,
        'data_entrada' => now(),
        'approval_token' => $token,
        'approval_status' => 'approved',
        'approval_requested_at' => now(),
        'approval_response_at' => now(),
    ]);

    $response = $this->get("/aprovacao/{$token}");

    $response->assertOk()
        ->assertSee('Esta Ordem de Serviço já foi respondida')
        ->assertSee('APROVADA')
        ->assertDontSee('Aprovar Ordem de Serviço')
        ->assertDontSee('Reprovar Ordem de Serviço');

    // Tentativas adicionais de aprovar/reprovar devem redirecionar com aviso
    $responseApprove = $this->post("/aprovacao/{$token}/aprovar");
    $responseApprove->assertRedirect("/aprovacao/{$token}")
        ->assertSessionHas('error', 'Esta Ordem de Serviço já foi respondida.');
});

test('authenticated user can request approval and generate token', function () {
    $ordem = OrdemServico::withoutGlobalScope(EmpresaScope::class)->create([
        'empresa_id' => $this->empresa->id,
        'numero_os' => 'OS-4444',
        'cliente_id' => $this->cliente->id,
        'veiculo_id' => $this->veiculo->id,
        'user_id' => $this->user->id,
        'status' => 'aberta',
        'descricao_problema' => 'Problema na embreagem',
        'valor_total' => 3000.00,
        'aprovado_cliente' => false,
        'data_entrada' => now(),
    ]);

    $response = $this->actingAs($this->user)
        ->post("/ordens/{$ordem->id}/solicitar-aprovacao");

    $response->assertRedirect("/ordens/{$ordem->id}")
        ->assertSessionHas('success');

    $ordem->refresh();

    expect($ordem->approval_token)->not->toBeNull()
        ->and($ordem->approval_status)->toBe('pending')
        ->and($ordem->status)->toBe('aguardando_aprovacao')
        ->and($ordem->approval_requested_at)->not->toBeNull();

    $this->assertDatabaseHas('ordem_servico_historicos', [
        'ordem_servico_id' => $ordem->id,
        'status' => 'aguardando_aprovacao',
    ]);
});
