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
use App\Models\OrdemServicoItem;
use App\Models\OrdemServicoHistorico;
use App\Models\Plano;
use App\Models\Assinatura;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PlanoSeeder::class);

        $planoUltra = Plano::where('slug', 'ultra')->first() ?? Plano::first();
        $planoPro = Plano::where('slug', 'pro')->first() ?? Plano::first();
        $planoFree = Plano::where('slug', 'free')->first() ?? Plano::first();

        $empresas = [
            [
                'nome_fantasia' => 'Mecânica Modelo',
                'razao_social'  => 'Mecânica Modelo e Auto Center LTDA',
                'cnpj'          => '12.345.678/0001-90',
                'email'         => 'admin@mecdesk.com',
                'telefone'      => '(11) 3456-7890',
                'whatsapp'      => '(11) 98765-4321',
                'cep'           => '01310-100',
                'logradouro'    => 'Avenida Paulista',
                'numero'        => '1000',
                'bairro'        => 'Bela Vista',
                'cidade'        => 'São Paulo',
                'estado'        => 'SP',
                'plano'         => $planoUltra,
            ],
            [
                'nome_fantasia' => 'Auto Center Express',
                'razao_social'  => 'Express Manutenção Automotiva LTDA',
                'cnpj'          => '23.456.789/0001-01',
                'email'         => 'express@mecdesk.com',
                'telefone'      => '(21) 2233-4455',
                'whatsapp'      => '(21) 99887-6655',
                'cep'           => '20040-002',
                'logradouro'    => 'Rua da Assembleia',
                'numero'        => '50',
                'bairro'        => 'Centro',
                'cidade'        => 'Rio de Janeiro',
                'estado'        => 'RJ',
                'plano'         => $planoPro,
            ],
        ];

        foreach ($empresas as $dadosEmpresa) {
            $plano = $dadosEmpresa['plano'] ?? $planoPro;

            $empresa = new Empresa([
                'nome_fantasia' => $dadosEmpresa['nome_fantasia'],
                'razao_social'  => $dadosEmpresa['razao_social'],
                'cnpj'          => $dadosEmpresa['cnpj'],
                'email'         => $dadosEmpresa['email'],
                'telefone'      => $dadosEmpresa['telefone'],
                'whatsapp'      => $dadosEmpresa['whatsapp'],
                'cep'           => $dadosEmpresa['cep'],
                'logradouro'    => $dadosEmpresa['logradouro'],
                'numero'        => $dadosEmpresa['numero'],
                'bairro'        => $dadosEmpresa['bairro'],
                'cidade'        => $dadosEmpresa['cidade'],
                'estado'        => $dadosEmpresa['estado'],
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
                'data_inicio'        => now()->subMonths(2),
                'proximo_vencimento' => now()->addMonth(),
                'valido_ate'         => now()->addMonth(),
            ]);

            // Usuário Administrador
            $user = User::create([
                'empresa_id' => $empresa->id,
                'name'       => 'Administrador ' . $dadosEmpresa['nome_fantasia'],
                'email'      => $dadosEmpresa['email'],
                'password'   => Hash::make('12345678'),
                'role'       => 'admin',
            ]);

            // 1. Cadastro de Peças Realistas
            $pecasPadrao = [
                ['nome' => 'Óleo Motor 5W30 Sintético 1L', 'codigo' => 'OLE-5W30', 'estoque' => 48, 'valor_unitario' => 45.00],
                ['nome' => 'Óleo Motor 0W20 Sintético 1L', 'codigo' => 'OLE-0W20', 'estoque' => 36, 'valor_unitario' => 58.00],
                ['nome' => 'Filtro de Óleo Blindado', 'codigo' => 'FIL-OC90', 'estoque' => 25, 'valor_unitario' => 38.00],
                ['nome' => 'Filtro de Ar do Motor', 'codigo' => 'FIL-AR22', 'estoque' => 18, 'valor_unitario' => 42.00],
                ['nome' => 'Filtro de Combustível', 'codigo' => 'FIL-CB10', 'estoque' => 20, 'valor_unitario' => 35.00],
                ['nome' => 'Filtro de Cabine (Ar Condicionado)', 'codigo' => 'FIL-CAB01', 'estoque' => 15, 'valor_unitario' => 48.00],
                ['nome' => 'Jogo de Pastilhas de Freio Dianteiras', 'codigo' => 'PAS-FR01', 'estoque' => 12, 'valor_unitario' => 160.00],
                ['nome' => 'Par de Discos de Freio Dianteiros Ventilados', 'codigo' => 'DIS-FR02', 'estoque' => 8, 'valor_unitario' => 320.00],
                ['nome' => 'Fluido de Freio DOT 4 500ml', 'codigo' => 'FLU-DOT4', 'estoque' => 30, 'valor_unitario' => 32.00],
                ['nome' => 'Kit Correia Dentada + Tensor', 'codigo' => 'COR-KIT01', 'estoque' => 6, 'valor_unitario' => 380.00],
                ['nome' => 'Bomba d\'Água Motor', 'codigo' => 'BOM-AG01', 'estoque' => 5, 'valor_unitario' => 220.00],
                ['nome' => 'Líquido de Arrefecimento Orgânico Rosa 1L', 'codigo' => 'ARR-ORG01', 'estoque' => 40, 'valor_unitario' => 36.00],
                ['nome' => 'Jogo de Velas de Ignição Iridium', 'codigo' => 'VEL-IRI04', 'estoque' => 14, 'valor_unitario' => 240.00],
                ['nome' => 'Bateria Automotiva 60Ah Selada', 'codigo' => 'BAT-60AH', 'estoque' => 9, 'valor_unitario' => 450.00],
                ['nome' => 'Par de Amortecedores Dianteiros', 'codigo' => 'AMO-DT01', 'estoque' => 4, 'valor_unitario' => 680.00],
                ['nome' => 'Par de Palhetas Limpador de Parabrisa', 'codigo' => 'PAL-PB01', 'estoque' => 22, 'valor_unitario' => 65.00],
            ];

            $pecasModelos = [];
            foreach ($pecasPadrao as $pecaDado) {
                $pecasModelos[] = Peca::create([
                    'empresa_id'     => $empresa->id,
                    'nome'           => $pecaDado['nome'],
                    'codigo'         => $pecaDado['codigo'],
                    'estoque'        => $pecaDado['estoque'],
                    'valor_unitario' => $pecaDado['valor_unitario'],
                ]);
            }

            // 2. Cadastro de Serviços Realistas
            $servicosPadrao = [
                ['nome' => 'Troca de Óleo e Filtros', 'descricao' => 'Substituição do óleo do motor, filtro de óleo e filtro de ar', 'valor_base' => 90.00],
                ['nome' => 'Alinhamento 3D e Balanceamento das 4 Rodas', 'descricao' => 'Alinhamento a laser computadorizado e balanceamento', 'valor_base' => 140.00],
                ['nome' => 'Troca de Pastilhas e Discos de Freio', 'descricao' => 'Substituição das pastilhas e discos do eixo dianteiro', 'valor_base' => 160.00],
                ['nome' => 'Higienização do Sistema de Ar Condicionado + Filtro', 'descricao' => 'Aplicação de ozônio e substituição do filtro de cabine', 'valor_base' => 120.00],
                ['nome' => 'Troca do Kit da Correia Dentada e Bomba d\'Água', 'descricao' => 'Substituição preventiva de correia, tensor e bomba d\'água', 'valor_base' => 350.00],
                ['nome' => 'Diagnóstico Eletrônico Computadorizado (Scanner)', 'descricao' => 'Varredura completa dos módulos de injeção, ABS e airbag', 'valor_base' => 110.00],
                ['nome' => 'Limpeza e Teste de Bicos Injetores', 'descricao' => 'Equalização em cuba ultrassônica e teste de vazão', 'valor_base' => 180.00],
                ['nome' => 'Troca e Sangria do Fluido de Freio', 'descricao' => 'Substituição completa do fluido DOT 4 com máquina de pressurização', 'valor_base' => 130.00],
                ['nome' => 'Revisão Geral Preventiva (Checkup 40 Itens)', 'descricao' => 'Inspeção completa de suspensão, freios, fluidos e sistema elétrico', 'valor_base' => 250.00],
                ['nome' => 'Troca de Amortecedores e Molas Dianteiras', 'descricao' => 'Mão de obra para troca dos amortecedores dianteiros', 'valor_base' => 220.00],
            ];

            $servicosModelos = [];
            foreach ($servicosPadrao as $servicoDado) {
                $servicosModelos[] = Servico::create([
                    'empresa_id' => $empresa->id,
                    'nome'       => $servicoDado['nome'],
                    'descricao'  => $servicoDado['descricao'],
                    'valor_base' => $servicoDado['valor_base'],
                ]);
            }

            // 3. Cadastro de Clientes e Veículos Realistas
            $clientesExemplos = [
                ['nome' => 'Carlos Eduardo Santos', 'cpf_cnpj' => '214.567.890-11', 'telefone' => '(11) 98111-2233', 'email' => 'carlos.santos@gmail.com', 'veiculo' => ['marca' => 'Honda', 'modelo' => 'Civic EXL 2.0', 'ano' => 2021, 'placa' => 'BRA2E19', 'cor' => 'Prata', 'km' => 45000]],
                ['nome' => 'Mariana Oliveira Costa', 'cpf_cnpj' => '325.678.901-22', 'telefone' => '(11) 98222-3344', 'email' => 'mariana.costa@hotmail.com', 'veiculo' => ['marca' => 'Toyota', 'modelo' => 'Corolla XEi 2.0', 'ano' => 2022, 'placa' => 'RJG4H32', 'cor' => 'Branco Pérola', 'km' => 32000]],
                ['nome' => 'Roberto Silveira Lima', 'cpf_cnpj' => '436.789.012-33', 'telefone' => '(11) 98333-4455', 'email' => 'roberto.lima@outlook.com', 'veiculo' => ['marca' => 'Volkswagen', 'modelo' => 'Golf TSI 1.4', 'ano' => 2019, 'placa' => 'FGH7J89', 'cor' => 'Preto', 'km' => 68000]],
                ['nome' => 'Fernanda Rodrigues Alves', 'cpf_cnpj' => '547.890.123-44', 'telefone' => '(11) 98444-5566', 'email' => 'fernanda.alves@yahoo.com.br', 'veiculo' => ['marca' => 'Jeep', 'modelo' => 'Renegade Longitude 1.3 Turbo', 'ano' => 2023, 'placa' => 'SPX9K41', 'cor' => 'Cinza', 'km' => 18000]],
                ['nome' => 'Lucas Ferreira Martins', 'cpf_cnpj' => '658.901.234-55', 'telefone' => '(11) 98555-6677', 'email' => 'lucas.martins@gmail.com', 'veiculo' => ['marca' => 'Chevrolet', 'modelo' => 'Onix Premier 1.0 Turbo', 'ano' => 2022, 'placa' => 'ABC1D23', 'cor' => 'Vermelho Carmim', 'km' => 29000]],
                ['nome' => 'Patrícia Mendes Guimarães', 'cpf_cnpj' => '769.012.345-66', 'telefone' => '(11) 98666-7788', 'email' => 'patricia.mendes@uol.com.br', 'veiculo' => ['marca' => 'Hyundai', 'modelo' => 'HB20 Diamond Plus 1.0T', 'ano' => 2021, 'placa' => 'HYU5B67', 'cor' => 'Azul Sapphire', 'km' => 38000]],
                ['nome' => 'Transportes & Logística Veloz LTDA', 'cpf_cnpj' => '45.678.901/0001-23', 'telefone' => '(11) 3344-5566', 'email' => 'frotas@transveloz.com.br', 'veiculo' => ['marca' => 'Fiat', 'modelo' => 'Fiorino Endurance 1.4', 'ano' => 2022, 'placa' => 'FIO8C90', 'cor' => 'Branco', 'km' => 82000]],
                ['nome' => 'Bruno Henrique Castro', 'cpf_cnpj' => '870.123.456-77', 'telefone' => '(11) 98777-8899', 'email' => 'bruno.castro@gmail.com', 'veiculo' => ['marca' => 'Renault', 'modelo' => 'Duster Iconic 1.6 CVT', 'ano' => 2020, 'placa' => 'REN3M45', 'cor' => 'Marrom Castanho', 'km' => 54000]],
            ];

            $clientesCriados = [];
            $veiculosCriados = [];

            foreach ($clientesExemplos as $cliDado) {
                $cliente = Cliente::create([
                    'empresa_id' => $empresa->id,
                    'nome'       => $cliDado['nome'],
                    'cpf_cnpj'   => $cliDado['cpf_cnpj'],
                    'telefone'   => $cliDado['telefone'],
                    'email'      => $cliDado['email'],
                    'endereco'   => 'Rua das Palmeiras, ' . rand(10, 500) . ' - São Paulo/SP',
                ]);

                $veicDado = $cliDado['veiculo'];
                $veiculo = Veiculo::create([
                    'empresa_id'     => $empresa->id,
                    'cliente_id'     => $cliente->id,
                    'marca'          => $veicDado['marca'],
                    'modelo'         => $veicDado['modelo'],
                    'ano'            => $veicDado['ano'],
                    'placa'          => $veicDado['placa'],
                    'cor'            => $veicDado['cor'],
                    'quilometragem'  => $veicDado['km'],
                ]);

                $clientesCriados[] = $cliente;
                $veiculosCriados[] = $veiculo;
            }

            // 4. Cadastro de Ordens de Serviço Realistas e Itens
            $cenariosOS = [
                [
                    'cliente_idx' => 0, // Carlos Eduardo
                    'status'      => 'em_andamento',
                    'problema'    => 'Barulho ao frear na dianteira e luz de revisão acesa no painel',
                    'itens'       => [
                        ['tipo' => 'servico', 'idx' => 0, 'qtd' => 1, 'v_unit' => 90.00],   // Troca de óleo
                        ['tipo' => 'peca',    'idx' => 0, 'qtd' => 4, 'v_unit' => 45.00],   // 4L Óleo 5W30
                        ['tipo' => 'peca',    'idx' => 2, 'qtd' => 1, 'v_unit' => 38.00],   // Filtro de Óleo
                        ['tipo' => 'servico', 'idx' => 2, 'qtd' => 1, 'v_unit' => 160.00],  // Troca Pastilhas
                        ['tipo' => 'peca',    'idx' => 6, 'qtd' => 1, 'v_unit' => 160.00],  // Pastilhas Dianteiras
                    ],
                ],
                [
                    'cliente_idx' => 1, // Mariana
                    'status'      => 'aguardando_aprovacao',
                    'problema'    => 'Revisão dos 30.000 km e ar condicionado com cheiro desagradável',
                    'itens'       => [
                        ['tipo' => 'servico', 'idx' => 8, 'qtd' => 1, 'v_unit' => 250.00],  // Revisão Preventiva
                        ['tipo' => 'servico', 'idx' => 3, 'qtd' => 1, 'v_unit' => 120.00],  // Higienização Ar
                        ['tipo' => 'peca',    'idx' => 5, 'qtd' => 1, 'v_unit' => 48.00],   // Filtro Cabine
                        ['tipo' => 'peca',    'idx' => 1, 'qtd' => 4, 'v_unit' => 58.00],   // 4L Óleo 0W20
                        ['tipo' => 'peca',    'idx' => 2, 'qtd' => 1, 'v_unit' => 38.00],   // Filtro Óleo
                    ],
                ],
                [
                    'cliente_idx' => 2, // Roberto
                    'status'      => 'concluida',
                    'problema'    => 'Volante vibrando em alta velocidade e puxando levemente para a direita',
                    'itens'       => [
                        ['tipo' => 'servico', 'idx' => 1, 'qtd' => 1, 'v_unit' => 140.00],  // Alinhamento 3D
                        ['tipo' => 'servico', 'idx' => 0, 'qtd' => 1, 'v_unit' => 90.00],   // Troca de óleo
                        ['tipo' => 'peca',    'idx' => 0, 'qtd' => 4, 'v_unit' => 45.00],   // 4L Óleo 5W30
                        ['tipo' => 'peca',    'idx' => 2, 'qtd' => 1, 'v_unit' => 38.00],   // Filtro Óleo
                    ],
                ],
                [
                    'cliente_idx' => 3, // Fernanda
                    'status'      => 'aberta',
                    'problema'    => 'Diagnóstico de barulho na suspensão ao passar em lombadas e troca de palhetas',
                    'itens'       => [
                        ['tipo' => 'servico', 'idx' => 5, 'qtd' => 1, 'v_unit' => 110.00],  // Scanner Diagnóstico
                        ['tipo' => 'peca',    'idx' => 15, 'qtd' => 1, 'v_unit' => 65.00],  // Palhetas Limpador
                        ['tipo' => 'personalizado_servico', 'desc' => 'Regulagem e reaperto de abraçadeiras do escapamento', 'qtd' => 1, 'v_unit' => 80.00],
                    ],
                ],
                [
                    'cliente_idx' => 6, // Transportes Veloz
                    'status'      => 'concluida',
                    'problema'    => 'Troca preventiva de correia dentada e bomba d\'água da frota',
                    'itens'       => [
                        ['tipo' => 'servico', 'idx' => 4, 'qtd' => 1, 'v_unit' => 350.00],  // Troca Correia
                        ['tipo' => 'peca',    'idx' => 9, 'qtd' => 1, 'v_unit' => 380.00],  // Kit Correia
                        ['tipo' => 'peca',    'idx' => 10, 'qtd' => 1, 'v_unit' => 220.00], // Bomba d'água
                        ['tipo' => 'peca',    'idx' => 11, 'qtd' => 3, 'v_unit' => 36.00],  // 3L Aditivo Radiador
                    ],
                ],
            ];

            $contadorOs = 1;
            foreach ($cenariosOS as $cenario) {
                $cliente = $clientesCriados[$cenario['cliente_idx']];
                $veiculo = $veiculosCriados[$cenario['cliente_idx']];

                $ordem = OrdemServico::create([
                    'empresa_id'         => $empresa->id,
                    'numero_os'          => 'OS-' . str_pad($contadorOs++, 4, '0', STR_PAD_LEFT),
                    'cliente_id'         => $cliente->id,
                    'veiculo_id'         => $veiculo->id,
                    'user_id'            => $user->id,
                    'status'             => $cenario['status'],
                    'descricao_problema' => $cenario['problema'],
                    'observacoes'        => 'Atendimento padrão MecDesk',
                    'valor_total'        => 0,
                    'aprovado_cliente'   => in_array($cenario['status'], ['aprovada', 'concluida', 'em_andamento']),
                    'data_entrada'       => now()->subDays(rand(1, 15)),
                    'approval_token'     => $cenario['status'] === 'aguardando_aprovacao' ? Str::uuid()->toString() : null,
                    'approval_status'    => $cenario['status'] === 'aguardando_aprovacao' ? 'pending' : null,
                    'approval_requested_at' => $cenario['status'] === 'aguardando_aprovacao' ? now()->subHours(4) : null,
                ]);

                $totalOrdem = 0;
                foreach ($cenario['itens'] as $itemDado) {
                    if ($itemDado['tipo'] === 'servico') {
                        $serv = $servicosModelos[$itemDado['idx']];
                        $vTotal = $itemDado['qtd'] * $itemDado['v_unit'];
                        $totalOrdem += $vTotal;

                        OrdemServicoItem::create([
                            'ordem_servico_id' => $ordem->id,
                            'tipo_item'        => 'servico',
                            'servico_id'       => $serv->id,
                            'peca_id'          => null,
                            'descricao'        => $serv->nome,
                            'quantidade'       => $itemDado['qtd'],
                            'valor_unitario'   => $itemDado['v_unit'],
                            'valor_total'      => $vTotal,
                        ]);
                    } elseif ($itemDado['tipo'] === 'peca') {
                        $pec = $pecasModelos[$itemDado['idx']];
                        $vTotal = $itemDado['qtd'] * $itemDado['v_unit'];
                        $totalOrdem += $vTotal;

                        OrdemServicoItem::create([
                            'ordem_servico_id' => $ordem->id,
                            'tipo_item'        => 'peca',
                            'peca_id'          => $pec->id,
                            'servico_id'       => null,
                            'descricao'        => $pec->nome,
                            'quantidade'       => $itemDado['qtd'],
                            'valor_unitario'   => $itemDado['v_unit'],
                            'valor_total'      => $vTotal,
                        ]);

                        $pec->decrement('estoque', $itemDado['qtd']);
                    } elseif ($itemDado['tipo'] === 'personalizado_servico') {
                        $vTotal = $itemDado['qtd'] * $itemDado['v_unit'];
                        $totalOrdem += $vTotal;

                        OrdemServicoItem::create([
                            'ordem_servico_id' => $ordem->id,
                            'tipo_item'        => 'servico',
                            'servico_id'       => null,
                            'peca_id'          => null,
                            'descricao'        => $itemDado['desc'],
                            'quantidade'       => $itemDado['qtd'],
                            'valor_unitario'   => $itemDado['v_unit'],
                            'valor_total'      => $vTotal,
                        ]);
                    }
                }

                $ordem->update(['valor_total' => $totalOrdem]);

                // Histórico de status
                OrdemServicoHistorico::create([
                    'ordem_servico_id' => $ordem->id,
                    'status'           => 'aberta',
                    'created_at'       => $ordem->data_entrada,
                ]);

                if ($ordem->status !== 'aberta') {
                    OrdemServicoHistorico::create([
                        'ordem_servico_id' => $ordem->id,
                        'status'           => $ordem->status,
                        'created_at'       => now()->subHours(rand(1, 24)),
                    ]);
                }
            }
        }
    }
}