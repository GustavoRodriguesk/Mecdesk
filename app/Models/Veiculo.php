<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $fillable = [
        'cliente_id',
        'marca',
        'modelo',
        'ano',
        'placa',
        'cor',
        'quilometragem',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}