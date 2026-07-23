<?php

namespace Database\Seeders;

use App\Models\Plano;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        $planos = [
            [
                'slug'         => 'free',
                'nome'         => 'Free',
                'descricao'    => 'Plano essencial para pequenas oficinas em início de atividade.',
                'preco_mensal' => 0.00,
                'max_usuarios' => 1,
                'recursos'     => [
                    'ordens_servico'  => 15,
                    'clientes'        => 30,
                    'suporte'         => 'comunitario',
                ],
                'ativo'        => true,
            ],
            [
                'slug'         => 'pro',
                'nome'         => 'Pro',
                'descricao'    => 'Plano profissional completo para oficinas em crescimento.',
                'preco_mensal' => 99.00,
                'max_usuarios' => 5,
                'recursos'     => [
                    'ordens_servico'  => 'unlimited',
                    'clientes'        => 'unlimited',
                    'pdf_custom'      => true,
                    'whatsapp_direct' => true,
                    'suporte'         => 'prioritario',
                ],
                'ativo'        => true,
            ],
            [
                'slug'         => 'ultra',
                'nome'         => 'Ultra',
                'descricao'    => 'Plano avançado com recursos de alta capacidade e múltiplos usuários.',
                'preco_mensal' => 199.00,
                'max_usuarios' => 20,
                'recursos'     => [
                    'ordens_servico'  => 'unlimited',
                    'clientes'        => 'unlimited',
                    'pdf_custom'      => true,
                    'whatsapp_direct' => true,
                    'multi_usuarios'  => true,
                    'suporte'         => 'dedicado 24/7',
                ],
                'ativo'        => true,
            ],
        ];

        foreach ($planos as $planoData) {
            Plano::updateOrCreate(
                ['slug' => $planoData['slug']],
                $planoData
            );
        }
    }
}
