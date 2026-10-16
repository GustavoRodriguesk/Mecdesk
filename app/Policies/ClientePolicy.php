<?php

namespace App\Policies;

class ClientePolicy extends PoliticaDeModulo
{
    protected function modulo(): string
    {
        return 'clientes';
    }
}
