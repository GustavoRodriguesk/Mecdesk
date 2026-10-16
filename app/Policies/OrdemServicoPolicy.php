<?php

namespace App\Policies;

use App\Models\OrdemServico;
use App\Models\User;

class OrdemServicoPolicy extends PoliticaDeModulo
{
    protected function modulo(): string
    {
        return 'ordens';
    }

    public function alterarStatus(User $user, OrdemServico $ordem): bool
    {
        return $user->temPermissao('ordens.alterar_status') && $this->daMesmaEmpresa($user, $ordem);
    }

    public function enviarAprovacao(User $user, OrdemServico $ordem): bool
    {
        return $user->temPermissao('ordens.enviar_aprovacao') && $this->daMesmaEmpresa($user, $ordem);
    }

    public function gerarDocumentos(User $user, OrdemServico $ordem): bool
    {
        return $user->temPermissao('ordens.documentos.gerar') && $this->daMesmaEmpresa($user, $ordem);
    }
}
