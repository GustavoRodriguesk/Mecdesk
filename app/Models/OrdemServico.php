<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdemServico extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'veiculo_id',
        'descricao_problema',
        'status',
        'valor_total',
        'data_entrada',
        'data_saida',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
}