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

    /*
    |--------------------------------------------------------------------------
    | Mutators & Accessors
    |--------------------------------------------------------------------------
    */

    public function setPlacaAttribute($value): void
    {
        $this->attributes['placa'] = $value ? strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $value))) : null;
    }

    public function getPlacaFormatadaAttribute(): string
    {
        $p = strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $this->placa ?? '')));
        if (strlen($p) === 7) {
            // Se for padrão antigo (3 letras + 4 números, ex: ABC1234)
            if (preg_match('/^[A-Z]{3}\d{4}$/', $p)) {
                return substr($p, 0, 3) . '-' . substr($p, 3);
            }
        }
        return $p;
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