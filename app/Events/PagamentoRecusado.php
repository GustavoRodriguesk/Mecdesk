<?php

namespace App\Events;

use App\Models\Pagamento;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PagamentoRecusado
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pagamento $pagamento
    ) {}
}
