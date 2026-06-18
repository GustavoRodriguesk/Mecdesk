<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Servico;
use App\Models\Peca;
use App\Models\OrdemServico;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [

            [
                'nome' => 'Oficina Demo',
                'plano' => 'business',
                'email' => 'teste1@gmail.com'
            ],

            [
                'nome' => 'Auto Center Brasil',
                'plano' => 'starter',
                'email' => 'teste2@gmail.com'
            ],

            [
                'nome' => 'Mecânica do João',
                'plano' => 'pro',
                'email' => 'teste3@gmail.com'
            ]

        ];

        foreach ($empresas as $dadosEmpresa) {

            $empresa = Empresa::create([
                'nome' => $dadosEmpresa['nome'],
                'email' => $dadosEmpresa['email'],
                'plano' => $dadosEmpresa['plano'],
                'ativo' => true,
            ]);

           $user = User::factory()->create([
    'name' => $dadosEmpresa['nome'],
    'email' => $dadosEmpresa['email'],
    'empresa_id' => $empresa->id,
    'password' => bcrypt('12345678'),
]);

            Cliente::factory(30)->create([
                'empresa_id' => $empresa->id,
            ]);

            Veiculo::factory(60)->create([
                'empresa_id' => $empresa->id,
            ]);

            Servico::factory(30)->create([
                'empresa_id' => $empresa->id,
            ]);

            Peca::factory(80)->create([
                'empresa_id' => $empresa->id,
            ]);

            OrdemServico::factory(200)->create([
                'empresa_id' => $empresa->id,
                'user_id' => $user->id,
            ]);
        }
    }
}