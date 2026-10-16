<?php

namespace App\Support;

use App\Models\Permissao;

/**
 * Catálogo centralizado de permissões do MecDesk.
 *
 * Todos os identificadores seguem o padrão `modulo.acao` e são a única fonte da verdade
 * para Gates, Policies, Blade (@can) e para o editor de perfis. Para criar permissões para
 * um novo módulo basta adicionar uma entrada em {@see self::modulos()} e executar
 * `php artisan permissoes:sincronizar` (ou `php artisan migrate`, que também sincroniza).
 *
 * Módulos marcados como "reservado" (Minha Empresa e Minha Assinatura) só podem ser exercidos
 * pelo administrador da empresa: nunca são atribuíveis a perfis operacionais.
 */
final class Permissoes
{
    /**
     * @return array<string, array{nome: string, reservado: bool, permissoes: array<string, string>}>
     */
    public static function modulos(): array
    {
        return [
            'dashboard' => [
                'nome' => 'Dashboard',
                'reservado' => false,
                'permissoes' => [
                    'dashboard.operacional.visualizar' => 'Visualizar indicadores operacionais (OS, clientes, veículos, estoque)',
                    'dashboard.financeiro.visualizar' => 'Visualizar indicadores financeiros (faturamento)',
                ],
            ],
            'clientes' => [
                'nome' => 'Clientes',
                'reservado' => false,
                'permissoes' => [
                    'clientes.visualizar' => 'Visualizar',
                    'clientes.criar' => 'Cadastrar',
                    'clientes.editar' => 'Editar',
                    'clientes.excluir' => 'Excluir',
                ],
            ],
            'veiculos' => [
                'nome' => 'Veículos',
                'reservado' => false,
                'permissoes' => [
                    'veiculos.visualizar' => 'Visualizar',
                    'veiculos.criar' => 'Cadastrar',
                    'veiculos.editar' => 'Editar',
                    'veiculos.excluir' => 'Excluir',
                ],
            ],
            'pecas' => [
                'nome' => 'Peças',
                'reservado' => false,
                'permissoes' => [
                    'pecas.visualizar' => 'Visualizar',
                    'pecas.criar' => 'Cadastrar',
                    'pecas.editar' => 'Editar',
                    'pecas.excluir' => 'Excluir',
                ],
            ],
            'servicos' => [
                'nome' => 'Serviços',
                'reservado' => false,
                'permissoes' => [
                    'servicos.visualizar' => 'Visualizar',
                    'servicos.criar' => 'Cadastrar',
                    'servicos.editar' => 'Editar',
                    'servicos.excluir' => 'Excluir',
                ],
            ],
            'ordens' => [
                'nome' => 'Ordens de Serviço',
                'reservado' => false,
                'permissoes' => [
                    'ordens.visualizar' => 'Visualizar',
                    'ordens.criar' => 'Abrir',
                    'ordens.editar' => 'Editar (itens, fotos e desconto)',
                    'ordens.excluir' => 'Excluir',
                    'ordens.alterar_status' => 'Alterar status',
                    'ordens.enviar_aprovacao' => 'Enviar para aprovação do cliente',
                    'ordens.documentos.gerar' => 'Gerar documentos (PDF de orçamento e vistoria)',
                ],
            ],
            'empresa' => [
                'nome' => 'Minha Empresa',
                'reservado' => true,
                'permissoes' => [
                    'empresa.visualizar' => 'Visualizar dados da empresa',
                    'empresa.editar' => 'Editar dados da empresa',
                    'empresa.usuarios.visualizar' => 'Visualizar funcionários',
                    'empresa.usuarios.criar' => 'Cadastrar funcionários',
                    'empresa.usuarios.editar' => 'Editar funcionários',
                    'empresa.usuarios.desativar' => 'Ativar/desativar funcionários',
                    'empresa.usuarios.excluir' => 'Excluir funcionários',
                    'empresa.perfis.visualizar' => 'Visualizar perfis de acesso',
                    'empresa.perfis.criar' => 'Criar perfis de acesso',
                    'empresa.perfis.editar' => 'Editar perfis de acesso',
                    'empresa.perfis.excluir' => 'Excluir perfis de acesso',
                ],
            ],
            'assinatura' => [
                'nome' => 'Minha Assinatura',
                'reservado' => true,
                'permissoes' => [
                    'assinatura.visualizar' => 'Visualizar assinatura',
                    'assinatura.gerenciar' => 'Gerenciar assinatura e pagamento',
                    'assinatura.cancelar' => 'Cancelar assinatura',
                ],
            ],
        ];
    }

    /**
     * Mapa slug => descrição de todas as permissões existentes.
     *
     * @return array<string, string>
     */
    public static function todas(): array
    {
        $todas = [];

        foreach (self::modulos() as $modulo) {
            $todas += $modulo['permissoes'];
        }

        return $todas;
    }

    /**
     * @return list<string>
     */
    public static function slugs(): array
    {
        return array_keys(self::todas());
    }

    /**
     * Permissões que podem ser concedidas a perfis operacionais (exclui as reservadas).
     *
     * @return list<string>
     */
    public static function atribuiveis(): array
    {
        $slugs = [];

        foreach (self::modulos() as $modulo) {
            if (! $modulo['reservado']) {
                array_push($slugs, ...array_keys($modulo['permissoes']));
            }
        }

        return $slugs;
    }

    /**
     * Módulos exibidos no editor de perfis (somente os atribuíveis).
     *
     * @return array<string, array{nome: string, reservado: bool, permissoes: array<string, string>}>
     */
    public static function modulosAtribuiveis(): array
    {
        return array_filter(self::modulos(), fn (array $modulo) => ! $modulo['reservado']);
    }

    public static function existe(string $slug): bool
    {
        return array_key_exists($slug, self::todas());
    }

    public static function reservada(string $slug): bool
    {
        foreach (self::modulos() as $modulo) {
            if ($modulo['reservado'] && array_key_exists($slug, $modulo['permissoes'])) {
                return true;
            }
        }

        return false;
    }

    public static function moduloDe(string $slug): ?string
    {
        foreach (self::modulos() as $chave => $modulo) {
            if (array_key_exists($slug, $modulo['permissoes'])) {
                return $chave;
            }
        }

        return null;
    }

    /**
     * Remove duplicidades/valores inválidos e garante a dependência "visualizar":
     * quem pode criar, editar ou excluir em um módulo precisa poder visualizá-lo.
     * (O módulo dashboard não possui dependência.)
     *
     * @param  array<int, mixed>  $slugs
     * @return list<string>
     */
    public static function normalizar(array $slugs): array
    {
        $validos = array_values(array_unique(array_filter(
            $slugs,
            fn ($slug) => is_string($slug) && in_array($slug, self::atribuiveis(), true)
        )));

        foreach ($validos as $slug) {
            $modulo = self::moduloDe($slug);

            if ($modulo === null || $modulo === 'dashboard') {
                continue;
            }

            $base = $modulo.'.visualizar';

            if ($slug !== $base && ! in_array($base, $validos, true)) {
                $validos[] = $base;
            }
        }

        return array_values($validos);
    }

    /**
     * Garante que todas as permissões do catálogo existam na tabela `permissoes` (idempotente).
     */
    public static function sincronizar(): void
    {
        foreach (self::modulos() as $chave => $modulo) {
            foreach ($modulo['permissoes'] as $slug => $descricao) {
                Permissao::updateOrCreate(
                    ['slug' => $slug],
                    ['descricao' => $descricao, 'modulo' => $chave]
                );
            }
        }
    }
}
