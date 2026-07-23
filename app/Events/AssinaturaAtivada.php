<?php

namespace App\Events;

use App\Models\Assinatura;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssinaturaAtivada
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Assinatura $assinatura
    ) {}
}
