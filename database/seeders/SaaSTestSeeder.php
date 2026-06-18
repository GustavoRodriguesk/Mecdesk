<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Peca;
use App\Models\Servico;
use App\Models\OrdemServico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SaaSTestSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [

            [
                'nome' => 'Oficina Demo',
                'plano' => 'business',
                'email' => 'demo@mecdesk.com'
            ],

            [
                'nome' => 'Auto Center Santos',
                'plano' => 'pro',
                'email' => 'santos@mecdesk.com'
            ],

            [
                'nome' => 'Mecânica Silva',
                'plano' => 'starter',
                'email' => 'silva@mecdesk.com'
            ],

        ];

        foreach ($empresas as $dadosEmpresa) {

            $empresa = Empresa::create([
                'nome' => $dadosEmpresa['nome'],
                'plano' => $dadosEmpresa['plano'],
                'ativo' => true,
            ]);

            User::create([
                'empresa_id' => $empresa->id,
                'name' => 'Admin ' . $empresa->nome,
                'email' => $dadosEmpresa['email'],
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]);

            Cliente::factory()
                ->count(rand(20, 40))
                ->create([
                    'empresa_id' => $empresa->id
                ]);

            Veiculo::factory()
                ->count(rand(30, 60))
                ->create([
                    'empresa_id' => $empresa->id
                ]);

            Peca::factory()
                ->count(rand(20, 40))
                ->create([
                    'empresa_id' => $empresa->id
                ]);

            Servico::factory()
                ->count(rand(10, 20))
                ->create([
                    'empresa_id' => $empresa->id
                ]);

            OrdemServico::factory()
                ->count(rand(50, 100))
                ->create([
                    'empresa_id' => $empresa->id
                ]);
        }
    }
}