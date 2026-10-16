<?php

namespace App\Support;

use App\Models\PerfilAcesso;
use App\Models\Permissao;
use Illuminate\Support\Facades\DB;

/**
 * Modelos iniciais de perfis operacionais. São apenas pontos de partida:
 * o administrador pode editá-los, renomeá-los ou excluí-los livremente.
 */
final class PerfisPadrao
{
    /**
     * @return array<string, array{descricao: string, permissoes: list<string>}>
     */
    public static function modelos(): array
    {
        $cadastro = fn (string $modulo, array $acoes) => array_map(fn ($a) => "$modulo.$a", $acoes);

        return [
            'Gerente' => [
                'descricao' => 'Controle operacional completo, incluindo exclusões e indicadores financeiros.',
                'permissoes' => [
                    'dashboard.operacional.visualizar',
                    'dashboard.financeiro.visualizar',
                    ...$cadastro('clientes', ['visualizar', 'criar', 'editar', 'excluir']),
                    ...$cadastro('veiculos', ['visualizar', 'criar', 'editar', 'excluir']),
                    ...$cadastro('pecas', ['visualizar', 'criar', 'editar', 'excluir']),
                    ...$cadastro('servicos', ['visualizar', 'criar', 'editar', 'excluir']),
                    'ordens.visualizar',
                    'ordens.criar',
                    'ordens.editar',
                    'ordens.excluir',
                    'ordens.alterar_status',
                    'ordens.enviar_aprovacao',
                    'ordens.documentos.gerar',
                ],
            ],
            'Mecânico' => [
                'descricao' => 'Executa as ordens de serviço: consulta cadastros, edita OS e atualiza o status.',
                'permissoes' => [
                    'dashboard.operacional.visualizar',
                    'clientes.visualizar',
                    'veiculos.visualizar',
                    'pecas.visualizar',
                    'servicos.visualizar',
                    'ordens.visualizar',
                    'ordens.editar',
                    'ordens.alterar_status',
                    'ordens.documentos.gerar',
                ],
            ],
            'Atendente' => [
                'descricao' => 'Atendimento ao cliente: cadastra clientes e veículos, abre OS e envia orçamentos.',
                'permissoes' => [
                    'dashboard.operacional.visualizar',
                    ...$cadastro('clientes', ['visualizar', 'criar', 'editar']),
                    ...$cadastro('veiculos', ['visualizar', 'criar', 'editar']),
                    'pecas.visualizar',
                    'servicos.visualizar',
                    'ordens.visualizar',
                    'ordens.criar',
                    'ordens.editar',
                    'ordens.enviar_aprovacao',
                    'ordens.documentos.gerar',
                ],
            ],
            'Financeiro' => [
                'descricao' => 'Acompanha indicadores financeiros e consulta ordens e documentos, sem editar.',
                'permissoes' => [
                    'dashboard.operacional.visualizar',
                    'dashboard.financeiro.visualizar',
                    'clientes.visualizar',
                    'pecas.visualizar',
                    'servicos.visualizar',
                    'ordens.visualizar',
                    'ordens.documentos.gerar',
                ],
            ],
        ];
    }

    /**
     * Perfis equivalentes ao acesso que as antigas funções (role) já possuíam,
     * usados apenas pela migração dos usuários existentes.
     *
     * @return array<string, array{nome: string, descricao: string, permissoes: list<string>}>
     */
    public static function legados(): array
    {
        $operacional = [];
        foreach (['clientes', 'veiculos', 'pecas', 'servicos'] as $modulo) {
            array_push($operacional, "$modulo.visualizar", "$modulo.criar", "$modulo.editar");
        }
        array_push(
            $operacional,
            'dashboard.operacional.visualizar',
            'ordens.visualizar',
            'ordens.criar',
            'ordens.editar',
            'ordens.alterar_status',
            'ordens.enviar_aprovacao',
            'ordens.documentos.gerar',
        );

        $gerente = $operacional;
        foreach (['clientes', 'veiculos', 'pecas', 'servicos', 'ordens'] as $modulo) {
            $gerente[] = "$modulo.excluir";
        }
        $gerente[] = 'dashboard.financeiro.visualizar';

        return [
            'gerente' => [
                'nome' => 'Gerente (legado)',
                'descricao' => 'Criado na migração: reproduz o acesso da antiga função Gerente.',
                'permissoes' => $gerente,
            ],
            'funcionario' => [
                'nome' => 'Funcionário (legado)',
                'descricao' => 'Criado na migração: reproduz o acesso da antiga função Funcionário (sem exclusões).',
                'permissoes' => $operacional,
            ],
        ];
    }

    /**
     * Cria (uma única vez) os perfis padrão da empresa. Idempotente: perfis já existentes
     * (mesmo nome) não são tocados, preservando eventuais ajustes do administrador.
     *
     * @return list<string> nomes dos perfis efetivamente criados
     */
    public static function criarParaEmpresa(int $empresaId): array
    {
        $criados = [];

        DB::transaction(function () use ($empresaId, &$criados) {
            Permissoes::sincronizar();
            $ids = Permissao::pluck('id', 'slug');

            foreach (self::modelos() as $nome => $modelo) {
                $perfil = PerfilAcesso::withoutGlobalScopes()
                    ->where('empresa_id', $empresaId)
                    ->where('nome', $nome)
                    ->first();

                if ($perfil) {
                    continue;
                }

                $perfil = new PerfilAcesso(['nome' => $nome, 'descricao' => $modelo['descricao']]);
                $perfil->empresa_id = $empresaId;
                $perfil->save();

                $perfil->permissoes()->sync(
                    collect(Permissoes::normalizar($modelo['permissoes']))
                        ->map(fn ($slug) => $ids[$slug])
                        ->all()
                );

                $criados[] = $nome;
            }
        });

        return $criados;
    }
}
