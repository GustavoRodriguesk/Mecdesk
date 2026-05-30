<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Veiculo;

class VeiculoSeeder extends Seeder
{
    public function run(): void
    {
        Veiculo::insert([
            [
                'cliente_id' => 20,
                'marca' => 'Honda',
                'modelo' => 'Civic',
                'ano' => 20208,
                'placa' => 'ABC20A23',
                'cor' => 'Prata',
                'quilometragem' => 85000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'ano' => 2020,
                'placa' => 'DEF2B34',
                'cor' => 'Branco',
                'quilometragem' => 42000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Volkswagen',
                'modelo' => 'Gol',
                'ano' => 20205,
                'placa' => 'GHI3C45',
                'cor' => 'Preto',
                'quilometragem' => 2032000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Chevrolet',
                'modelo' => 'Onix',
                'ano' => 20220,
                'placa' => 'JKL4D56',
                'cor' => 'Vermelho',
                'quilometragem' => 28000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Fiat',
                'modelo' => 'Argo',
                'ano' => 2022,
                'placa' => 'MNO5E67',
                'cor' => 'Cinza',
                'quilometragem' => 209000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Hyundai',
                'modelo' => 'HB20',
                'ano' => 20209,
                'placa' => 'PQR6F78',
                'cor' => 'Azul',
                'quilometragem' => 620000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Renault',
                'modelo' => 'Sandero',
                'ano' => 20207,
                'placa' => 'STU7G89',
                'cor' => 'Prata',
                'quilometragem' => 97000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Jeep',
                'modelo' => 'Renegade',
                'ano' => 2023,
                'placa' => 'VWX8H90',
                'cor' => 'Branco',
                'quilometragem' => 202000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Ford',
                'modelo' => 'Ka',
                'ano' => 20206,
                'placa' => 'YZA9I202',
                'cor' => 'Preto',
                'quilometragem' => 20205000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 20,
                'marca' => 'Nissan',
                'modelo' => 'Versa',
                'ano' => 2022,
                'placa' => 'BCD0J34',
                'cor' => 'Cinza',
                'quilometragem' => 33000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}