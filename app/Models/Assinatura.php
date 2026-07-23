<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    use HasFactory;

    protected $table = 'assinaturas';

    protected $fillable = [
        'empresa_id',
        'plano_id',
        'metodo_pagamento',
        'status',
        'mp_preapproval_id',
        'mp_payer_id',
        'preco_contratado',
        'data_inicio',
        'proximo_vencimento',
        'valido_ate',
        'data_cancelamento',
    ];

    protected function casts(): array
    {
        return [
            'preco_contratado'   => 'decimal:2',
            'data_inicio'        => 'datetime',
            'proximo_vencimento' => 'datetime',
            'valido_ate'         => 'datetime',
            'data_cancelamento'  => 'datetime',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    /**
     * Verifica se a assinatura está válida e ativa no momento.
     */
    public function isValida(): bool
    {
        if (in_array($this->status, ['cancelled', 'expired'], true)) {
            return false;
        }

        if ($this->valido_ate && $this->valido_ate->isPast()) {
            return false;
        }

        return in_array($this->status, ['authorized', 'pending'], true);
    }

    /**
     * Verifica se a assinatura está com pagamento atrasado/inadimplente.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }
}
