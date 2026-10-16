<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Base das Policies de módulos com permissões `{modulo}.visualizar|criar|editar|excluir`.
 *
 * Toda decisão exige (1) a permissão correspondente e (2) que o registro pertença à empresa
 * do usuário autenticado — a permissão genérica nunca vale para registros de outra empresa.
 */
abstract class PoliticaDeModulo
{
    /** Prefixo do módulo no catálogo (ex.: "clientes"). */
    abstract protected function modulo(): string;

    protected function pode(User $user, string $acao): bool
    {
        return $user->temPermissao($this->modulo().'.'.$acao);
    }

    protected function daMesmaEmpresa(User $user, Model $registro): bool
    {
        return ! empty($user->empresa_id)
            && (int) $registro->getAttribute('empresa_id') === (int) $user->empresa_id;
    }

    public function viewAny(User $user): bool
    {
        return $this->pode($user, 'visualizar');
    }

    public function view(User $user, Model $registro): bool
    {
        return $this->pode($user, 'visualizar') && $this->daMesmaEmpresa($user, $registro);
    }

    public function create(User $user): bool
    {
        return $this->pode($user, 'criar');
    }

    public function update(User $user, Model $registro): bool
    {
        return $this->pode($user, 'editar') && $this->daMesmaEmpresa($user, $registro);
    }

    public function delete(User $user, Model $registro): bool
    {
        return $this->pode($user, 'excluir') && $this->daMesmaEmpresa($user, $registro);
    }
}
