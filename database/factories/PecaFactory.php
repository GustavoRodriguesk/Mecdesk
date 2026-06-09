<?php

namespace Database\Factories;

use App\Models\Peca;
use Illuminate\Database\Eloquent\Factories\Factory;

class PecaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement([
                'Filtro de Óleo',
                'Pastilha de Freio',
                'Correia Dentada',
                'Amortecedor',
                'Velas de Ignição',
                'Bateria',
                'Pneu',
                'Radiador',
            ]),

            'codigo' => strtoupper(fake()->bothify('PEC-####')),

            'valor_unitario' => fake()->randomFloat(2, 10, 1500),

            'estoque' => fake()->numberBetween(0, 50),
        ];
    }
    public function estoqueBaixo()
{
    return $this->state(fn () => [
        'estoque' => fake()->numberBetween(0, 3),
    ]);
}
}