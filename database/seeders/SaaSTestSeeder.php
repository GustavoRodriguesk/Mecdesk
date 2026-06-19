<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SaaSTestSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [

            [
                'nome_fantasia' => 'Oficina Demo',
                'razao_social'  => 'Oficina Demo LTDA',
                'cnpj'          => '11.111.111/0001-11',
                'email'         => 'teste1@mecdesk.com',
                'telefone'      => '(11) 3333-1111',
                'whatsapp'      => '(11) 99999-1111',
                'cep'           => '01000-000',
                'logradouro'    => 'Rua das Oficinas',
                'numero'        => '100',
                'bairro'        => 'Centro',
                'cidade'        => 'São Paulo',
                'estado'        => 'SP',
                'plano'         => 'business',
            ],

            [
                'nome_fantasia' => 'Auto Center Santos',
                'razao_social'  => 'Auto Center Santos LTDA',
                'cnpj'          => '22.222.222/0001-22',
                'email'         => 'teste2@mecdesk.com',
                'telefone'      => '(13) 3333-2222',
                'whatsapp'      => '(13) 99999-2222',
                'cep'           => '11000-000',
                'logradouro'    => 'Avenida Ana Costa',
                'numero'        => '250',
                'bairro'        => 'Gonzaga',
                'cidade'        => 'Santos',
                'estado'        => 'SP',
                'plano'         => 'pro',
            ],

            [
                'nome_fantasia' => 'Mecânica Silva',
                'razao_social'  => 'Mecânica Silva LTDA',
                'cnpj'          => '33.333.333/0001-33',
                'email'         => 'teste3@mecdesk.com',
                'telefone'      => '(12) 3333-3333',
                'whatsapp'      => '(12) 99999-3333',
                'cep'           => '11660-000',
                'logradouro'    => 'Rua das Flores',
                'numero'        => '45',
                'bairro'        => 'Centro',
                'cidade'        => 'Caraguatatuba',
                'estado'        => 'SP',
                'plano'         => 'starter',
            ],

        ];

        foreach ($empresas as $dados) {

            $empresa = Empresa::create([
                ...$dados,
                'ativo' => true,
            ]);

            User::create([
                'empresa_id' => $empresa->id,
                'name'       => 'Administrador',
                'email'      => $dados['email'],
                'password'   => Hash::make('12345678'),
                'role'       => 'admin',
            ]);
        }
    }
}