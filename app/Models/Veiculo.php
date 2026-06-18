<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\EmpresaScope;

class Veiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'marca',
        'modelo',
        'ano',
        'placa',
        'cor',
        'quilometragem',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($veiculo) {

            if (
                auth()->check() &&
                !$veiculo->empresa_id
            ) {
                $veiculo->empresa_id =
                    auth()->user()->empresa_id;
            }

        });
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ordensServico()
    {
        return $this->hasMany(
            OrdemServico::class,
            'veiculo_id'
        );
    }
}