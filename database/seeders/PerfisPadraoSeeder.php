<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\PerfilAcesso;
use App\Models\Permissao;
use App\Support\Permissoes;
use Illuminate\Database\Seeder;

/**
 * Cria perfis de acesso padrão para todas as empresas existentes.
 *
 * Perfis gerados:
 *   - Mecânico        → operações diárias (OS, clientes, veículos)
 *   - Atendente       → recepção e abertura de OS
 *   - Financeiro      → visualização e gestão financeira
 *   - Almoxarife      → controle de estoque (peças)
 *   - Gerente         → acesso amplo, exceto configurações reservadas de empresa/assinatura
 *
 * O seeder é idempotente: usa updateOrCreate para nunca duplicar.
 * Perfis reservados (Minha Empresa / Minha Assinatura) pertencem ao admin e
 * NÃO são criados aqui – o sistema já concede essas permissões automaticamente
 * para usuários com role = 'admin'.
 */
class PerfisPadraoSeeder extends Seeder
{
    /**
     * Definição dos perfis padrão.
     * Cada entrada: ['nome', 'descricao', 'slugs' => [...]]
     *
     * @return array<int, array{nome: string, descricao: string, slugs: list<string>}>
     */
    private function perfis(): array
    {
        return [
            // ── Mecânico ──────────────────────────────────────────────────
            // Executa serviços: acessa OS, clientes e veículos.
            // Não mexe em peças, financeiro nem configurações.
            [
                'nome'      => 'Mecânico',
                'descricao' => 'Acesso às ordens de serviço, clientes e veículos. Perfil para técnicos de oficina.',
                'slugs'     => [
                    'dashboard.operacional.visualizar',
                    'clientes.visualizar',
                    'veiculos.visualizar',
                    'ordens.visualizar',
                    'ordens.criar',
                    'ordens.editar',
                    'ordens.alterar_status',
                    'ordens.documentos.gerar',
                ],
            ],

            // ── Atendente ─────────────────────────────────────────────────
            // Faz o atendimento ao cliente: abre OS, cadastra clientes/veículos,
            // envia para aprovação. Não acessa dados financeiros.
            [
                'nome'      => 'Atendente',
                'descricao' => 'Recepção e abertura de ordens de serviço. Cadastra clientes e veículos.',
                'slugs'     => [
                    'dashboard.operacional.visualizar',
                    'clientes.visualizar',
                    'clientes.criar',
                    'clientes.editar',
                    'veiculos.visualizar',
                    'veiculos.criar',
                    'veiculos.editar',
                    'ordens.visualizar',
                    'ordens.criar',
                    'ordens.editar',
                    'ordens.alterar_status',
                    'ordens.enviar_aprovacao',
                    'ordens.documentos.gerar',
                    'servicos.visualizar',
                    'pecas.visualizar',
                ],
            ],

            // ── Financeiro ────────────────────────────────────────────────
            // Acessa indicadores financeiros e pode consultar OS/clientes,
            // mas não altera dados operacionais.
            [
                'nome'      => 'Financeiro',
                'descricao' => 'Visualiza indicadores financeiros, ordens de serviço e clientes. Sem edição operacional.',
                'slugs'     => [
                    'dashboard.operacional.visualizar',
                    'dashboard.financeiro.visualizar',
                    'clientes.visualizar',
                    'veiculos.visualizar',
                    'ordens.visualizar',
                    'ordens.documentos.gerar',
                    'servicos.visualizar',
                    'pecas.visualizar',
                ],
            ],

            // ── Almoxarife ────────────────────────────────────────────────
            // Controle total do estoque de peças e consulta de serviços.
            [
                'nome'      => 'Almoxarife',
                'descricao' => 'Gerencia o estoque de peças e insumos da oficina.',
                'slugs'     => [
                    'dashboard.operacional.visualizar',
                    'pecas.visualizar',
                    'pecas.criar',
                    'pecas.editar',
                    'pecas.excluir',
                    'servicos.visualizar',
                    'ordens.visualizar',
                ],
            ],

            // ── Gerente ───────────────────────────────────────────────────
            // Acesso operacional completo: gerencia equipe, OS, clientes, estoque,
            // serviços e visualiza financeiro. Não acessa config. de empresa/assinatura
            // (essas são reservadas ao administrador).
            [
                'nome'      => 'Gerente',
                'descricao' => 'Acesso operacional completo. Gerencia equipe, OS, clientes, estoque e serviços.',
                'slugs'     => Permissoes::atribuiveis(),
            ],
        ];
    }

    public function run(): void
    {
        // Garante que o catálogo de permissões está sincronizado antes de referenciar slugs.
        Permissoes::sincronizar();

        $empresas = Empresa::all();

        foreach ($empresas as $empresa) {
            foreach ($this->perfis() as $config) {
                // Normaliza slugs (resolve dependências visualizar, remove inválidos).
                $slugs = Permissoes::normalizar($config['slugs']);

                // Cria ou atualiza o perfil para esta empresa.
                $perfil = PerfilAcesso::withoutGlobalScopes()->updateOrCreate(
                    [
                        'empresa_id' => $empresa->id,
                        'nome'       => $config['nome'],
                    ],
                    [
                        'descricao' => $config['descricao'],
                    ]
                );

                // Sincroniza permissões pelo slug.
                $ids = Permissao::whereIn('slug', $slugs)->pluck('id');
                $perfil->permissoes()->sync($ids);
            }
        }

        $total = $empresas->count() * count($this->perfis());
        $this->command->info("✔ {$total} perfis padrão criados/atualizados em {$empresas->count()} empresa(s).");
    }
}
