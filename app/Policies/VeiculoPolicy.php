<?php

namespace App\Policies;

class VeiculoPolicy extends PoliticaDeModulo
{
    protected function modulo(): string
    {
        return 'veiculos';
    }
}
