<?php

namespace App\Policies;

use App\Models\PerfilAcesso;
use App\Models\User;

class PerfilAcessoPolicy
{
    private function daMesmaEmpresa(User $user, PerfilAcesso $perfil): bool
    {
        return ! empty($user->empresa_id) && (int) $perfil->empresa_id === (int) $user->empresa_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->temPermissao('empresa.perfis.visualizar');
    }

    public function view(User $user, PerfilAcesso $perfil): bool
    {
        return $user->temPermissao('empresa.perfis.visualizar') && $this->daMesmaEmpresa($user, $perfil);
    }

    public function create(User $user): bool
    {
        return $user->temPermissao('empresa.perfis.criar');
    }

    public function update(User $user, PerfilAcesso $perfil): bool
    {
        return $user->temPermissao('empresa.perfis.editar') && $this->daMesmaEmpresa($user, $perfil);
    }

    public function delete(User $user, PerfilAcesso $perfil): bool
    {
        return $user->temPermissao('empresa.perfis.excluir') && $this->daMesmaEmpresa($user, $perfil);
    }
}
