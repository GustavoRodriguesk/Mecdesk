<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
        use HasFactory;

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
    public function ordensServico()
{
    return $this->hasMany(OrdemServico::class);
}
}