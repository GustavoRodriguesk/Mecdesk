<?php

namespace Database\Seeders;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Servico;
use App\Models\OrdemServico;
use App\Models\User;
use App\Models\Peca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        // User::factory(10)->create();
      Cliente::factory(10)->create();
      Veiculo::factory(20)->create();
        Servico::factory(20)->create();
        OrdemServico::factory(10)->create();
        Peca::factory(50)->create();
        Peca::factory(10)->estoqueBaixo()->create();

       
    }
}
