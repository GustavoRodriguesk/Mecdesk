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
        'nome'            => fake()->name(),
        'cpf_cnpj'        => fake()->unique()->numerify('###########'),
        'telefone'        => fake()->numerify('(##) #####-####'),
        'telefone_2'      => fake()->optional()->numerify('(##) #####-####'),
        'telefone_3'      => null,
        'possui_whatsapp' => true,
        'email'           => fake()->unique()->safeEmail(),
        'instagram'       => '@' . fake()->userName(),
        'cep'             => fake()->numerify('########'),
        'rua'             => fake()->streetName(),
        'numero'          => (string) fake()->buildingNumber(),
        'complemento'     => fake()->optional()->secondaryAddress(),
        'bairro'          => fake()->cityPrefix(),
        'cidade'          => fake()->city(),
        'estado'          => 'SP',
        'endereco'        => fake()->address(),
    ];
}
}