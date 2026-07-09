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

            'cliente_id' => function (array $attributes) {
                $empresaId = $attributes['empresa_id'] ?? null;
                $query = Cliente::query();
                if ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                }
                $cliente = $query->inRandomOrder()->first();
                return $cliente ? $cliente->id : Cliente::factory()->create($empresaId ? ['empresa_id' => $empresaId] : [])->id;
            },

            'veiculo_id' => function (array $attributes) {
                $clienteId = $attributes['cliente_id'] ?? null;
                if ($clienteId) {
                    $veiculo = Veiculo::where('cliente_id', $clienteId)->inRandomOrder()->first();
                    if ($veiculo) {
                        return $veiculo->id;
                    }
                }
                $empresaId = $attributes['empresa_id'] ?? null;
                return Veiculo::factory()->create([
                    'empresa_id' => $empresaId,
                    'cliente_id' => $clienteId,
                ])->id;
            },

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