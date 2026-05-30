<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    public function definition(): array
    {
        return [
            
            'cliente_id' => Cliente::inRandomOrder()->first()->id,

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