<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OrdemServicoFactory extends Factory
{
    public function definition(): array
    {
        $dataEntrada = fake()->dateTimeBetween(
            '-6 months',
            'now'
        );

        $status = fake()->randomElement([
            'concluida',
            'concluida',
            'concluida',
            'concluida',
            'concluida',
            'concluida',

            'em_andamento',
            'em_andamento',

            'aberta',

            'cancelada'
        ]);

        return [

            'numero_os' => 'OS-' . fake()->unique()->numberBetween(1000, 9999),

            'cliente_id' => Cliente::inRandomOrder()->first()->id,

            'veiculo_id' => Veiculo::inRandomOrder()->first()->id,

            'user_id' => 1,

            'status' => $status,

            'descricao_problema' => fake()->sentence(10),

            'valor_total' => match ($status) {
                'cancelada' => 0,
                default => fake()->randomFloat(
                    2,
                    80,
                    5000
                )
            },

            'aprovado_cliente' => fake()->boolean(80),

            'data_entrada' => $dataEntrada,

            'created_at' => $dataEntrada,

            'updated_at' => Carbon::parse($dataEntrada)
                ->addDays(rand(1, 30)),
        ];
    }
}