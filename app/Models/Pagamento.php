<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'assinatura_id',
        'empresa_id',
        'mp_payment_id',
        'metodo_pagamento',
        'status',
        'status_detail',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'payload_resposta',
    ];

    protected function casts(): array
    {
        return [
            'valor'            => 'decimal:2',
            'data_vencimento'  => 'datetime',
            'data_pagamento'   => 'datetime',
            'payload_resposta' => 'array',
        ];
    }

    public function assinatura()
    {
        return $this->belongsTo(Assinatura::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function isAprovado(): bool
    {
        return $this->status === 'approved';
    }
}
