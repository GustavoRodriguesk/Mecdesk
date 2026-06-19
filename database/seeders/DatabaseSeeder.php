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
            'nome_fantasia' => 'Oficina Demo',
            'razao_social'  => 'Oficina Demo LTDA',
            'email'         => 'teste1@gmail.com',
            'plano'         => 'business',
        ],

        [
            'nome_fantasia' => 'Auto Center Brasil',
            'razao_social'  => 'Auto Center Brasil LTDA',
            'email'         => 'teste2@gmail.com',
            'plano'         => 'starter',
        ],

        [
            'nome_fantasia' => 'Mecânica do João',
            'razao_social'  => 'Mecânica do João LTDA',
            'email'         => 'teste3@gmail.com',
            'plano'         => 'pro',
        ],

    ];

    foreach ($empresas as $dadosEmpresa) {

    $empresa = Empresa::create([
        'nome_fantasia' => $dadosEmpresa['nome_fantasia'],
        'razao_social' => $dadosEmpresa['razao_social'],
        'email' => $dadosEmpresa['email'],
        'plano' => $dadosEmpresa['plano'],
        'ativo' => true,
    ]);

    $user = User::factory()->create([
        'name' => $dadosEmpresa['nome_fantasia'],
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