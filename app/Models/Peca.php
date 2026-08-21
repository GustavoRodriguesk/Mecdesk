<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peca extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'codigo',
        'estoque',
        'valor_unitario',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($peca) {

            if (
                auth()->check() &&
                ! $peca->empresa_id
            ) {
                $peca->empresa_id =
                    auth()->user()->empresa_id;
            }

        });
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ordemServicoItens()
    {
        return $this->hasMany(OrdemServicoItem::class, 'peca_id');
    }
}
