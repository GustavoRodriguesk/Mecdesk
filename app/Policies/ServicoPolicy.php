<?php

namespace App\Policies;

class ServicoPolicy extends PoliticaDeModulo
{
    protected function modulo(): string
    {
        return 'servicos';
    }
}
