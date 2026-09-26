<?php

use App\Models\Plano;

it('returns a successful response for public landing page', function () {
    $response = $this->get('/planos');

    $response->assertStatus(200);
});

it('renders plan price dynamically from database on /planos page', function () {
    $plano = Plano::updateOrCreate(
        ['slug' => 'pro'],
        [
            'nome'         => 'Pro',
            'descricao'    => 'Plano profissional',
            'preco_mensal' => 149.90,
            'max_usuarios' => 5,
            'ativo'        => true,
        ]
    );

    $response = $this->get('/planos');

    $response->assertStatus(200);
    $response->assertSee('R$ 149,90');
    $response->assertSee('Começar agora por R$ 149,90/mês');
});
