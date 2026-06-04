<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Servico>
 */
class ServicoFactory extends Factory

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


{
    public function definition(): array
    {
        $servicos = [
            'Troca de óleo',
            'Alinhamento',
            'Balanceamento',
            'Troca de pastilhas',
            'Diagnóstico eletrônico',
            'Troca de bateria',
            'Troca de pneus',
            'Revisão completa',
            'Limpeza de bicos',
            'Troca de correia'
        ];

        return [
            'nome' => fake()->randomElement($servicos),
            'descricao' => fake()->sentence(),
            'valor_base' => fake()->randomFloat(2, 50, 1500),
        ];
    }
}