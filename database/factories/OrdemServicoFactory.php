<?php

namespace Database\Factories;

use App\Models\OrdemServico;
use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdemServicoFactory extends Factory
{

    public function definition(): array
{
    return [
        'numero_os' => 'OS-' . fake()->unique()->numberBetween(1000, 9999),

        'cliente_id' => Cliente::inRandomOrder()->first()->id,

        'veiculo_id' => Veiculo::inRandomOrder()->first()->id,

        'user_id' => 1,


            'status' => fake()->randomElement([
                'aberta',
                'em_andamento',
                'aguardando_aprovacao',
                'concluida',
                'cancelada'
            ]),
        'descricao_problema' => fake()->paragraph(),

        'valor_total' => 0,

        'aprovado_cliente' => false,

        'data_entrada' => now(),
    ];
}
}