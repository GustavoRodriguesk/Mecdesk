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
use App\Models\Plano;
use App\Models\Assinatura;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PlanoSeeder::class);

        $planoUltra = Plano::where('slug', 'ultra')->first();
        $planoPro = Plano::where('slug', 'pro')->first();
        $planoFree = Plano::where('slug', 'free')->first();

        $empresas = [
            [
                'nome_fantasia' => 'Oficina Demo',
                'razao_social'  => 'Oficina Demo LTDA',
                'email'         => 'teste1@gmail.com',
                'plano'         => $planoUltra,
            ],
            [
                'nome_fantasia' => 'Auto Center Brasil',
                'razao_social'  => 'Auto Center Brasil LTDA',
                'email'         => 'teste2@gmail.com',
                'plano'         => $planoFree,
            ],
            [
                'nome_fantasia' => 'Mecânica do João',
                'razao_social'  => 'Mecânica do João LTDA',
                'email'         => 'teste3@gmail.com',
                'plano'         => $planoPro,
            ],
        ];

        foreach ($empresas as $dadosEmpresa) {
            $plano = $dadosEmpresa['plano'];

            $empresa = new Empresa([
                'nome_fantasia' => $dadosEmpresa['nome_fantasia'],
                'razao_social'  => $dadosEmpresa['razao_social'],
                'email'         => $dadosEmpresa['email'],
                'plano_id'      => $plano->id,
            ]);
            $empresa->ativo = true;
            $empresa->save();

            Assinatura::create([
                'empresa_id'         => $empresa->id,
                'plano_id'           => $plano->id,
                'metodo_pagamento'   => 'cartao',
                'status'             => 'authorized',
                'preco_contratado'   => $plano->preco_mensal,
                'data_inicio'        => now(),
                'proximo_vencimento' => now()->addMonth(),
                'valido_ate'         => now()->addMonth(),
            ]);

            $user = User::factory()->create([
                'name'       => $dadosEmpresa['nome_fantasia'],
                'email'      => $dadosEmpresa['email'],
                'empresa_id' => $empresa->id,
                'password'   => bcrypt('12345678'),
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
                'user_id'    => $user->id,
            ]);
        }
    }
}