<?php

namespace Database\Seeders;

use App\Models\Plano;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        // Desativa planos antigos
        Plano::where('slug', '!=', 'pro')->update(['ativo' => false]);

        // Garante o plano Pro como o único ativo no sistema
        Plano::updateOrCreate(
            ['slug' => 'pro'],
            [
                'nome'         => 'Pro',
                'descricao'    => 'Plano profissional completo para gestão de oficinas mecânicas.',
                'preco_mensal' => 99.90,
                'max_usuarios' => 5,
                'recursos'     => [
                    'ordens_servico'  => 'unlimited',
                    'clientes'        => 'unlimited',
                    'pdf_custom'      => true,
                    'whatsapp_direct' => true,
                    'suporte'         => 'prioritario',
                ],
                'ativo'        => true,
            ]
        );
    }
}
