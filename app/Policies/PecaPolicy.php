<?php

namespace App\Policies;

class PecaPolicy extends PoliticaDeModulo
{
    protected function modulo(): string
    {
        return 'pecas';
    }
}
