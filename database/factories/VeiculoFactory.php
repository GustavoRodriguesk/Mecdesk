<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    public function definition(): array
    {
        return [
            
            'cliente_id' => function (array $attributes) {
                $empresaId = $attributes['empresa_id'] ?? null;
                $query = Cliente::query();
                if ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                }
                $cliente = $query->inRandomOrder()->first();
                return $cliente ? $cliente->id : Cliente::factory()->create($empresaId ? ['empresa_id' => $empresaId] : [])->id;
            },

            'marca' => fake()->randomElement([
                'Honda',
                'Toyota',
                'Volkswagen',
                'Chevrolet',
                'Fiat',
                'Hyundai',
                'Ford',
                'Renault',
                'Jeep'
            ]),

            'modelo' => fake()->randomElement([
                'Civic',
                'Corolla',
                'Gol',
                'Onix',
                'Argo',
                'HB20',
                'Ka',
                'Sandero',
                'Renegade'
            ]),

            'ano' => fake()->numberBetween(2005, 2025),

            'placa' => strtoupper(
                fake()->bothify('???#?##')
            ),

            'cor' => fake()->randomElement([
                'Preto',
                'Branco',
                'Prata',
                'Cinza',
                'Azul',
                'Vermelho'
            ]),

            'quilometragem' => fake()->numberBetween(0, 250000),
        ];
    }
}