<?php

namespace App\Policies;

use App\Models\User;

/**
 * Gestão de funcionários (permissões reservadas ao administrador da empresa).
 * O usuário alvo precisa pertencer à empresa de quem executa a ação.
 */
class UserPolicy
{
    private function daMesmaEmpresa(User $user, User $alvo): bool
    {
        return ! empty($user->empresa_id) && (int) $alvo->empresa_id === (int) $user->empresa_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->temPermissao('empresa.usuarios.visualizar');
    }

    public function create(User $user): bool
    {
        return $user->temPermissao('empresa.usuarios.criar');
    }

    /**
     * Administradores não são editados por esta tela (nem por outros administradores).
     */
    public function update(User $user, User $alvo): bool
    {
        return $user->temPermissao('empresa.usuarios.editar')
            && $this->daMesmaEmpresa($user, $alvo)
            && ! $alvo->isAdmin();
    }

    public function desativar(User $user, User $alvo): bool
    {
        return $user->temPermissao('empresa.usuarios.desativar') && $this->daMesmaEmpresa($user, $alvo);
    }

    public function delete(User $user, User $alvo): bool
    {
        return $user->temPermissao('empresa.usuarios.excluir') && $this->daMesmaEmpresa($user, $alvo);
    }
}
