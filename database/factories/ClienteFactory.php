<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
   public function definition(): array
{
    return [
        'nome' => fake()->name(),
        'cpf_cnpj' => fake()->unique()->numerify('###########'),
        'telefone' => fake()->numerify('(##) #####-####'),
        'email' => fake()->unique()->safeEmail(),
        'endereco' => fake()->address(),
    ];
}
}