<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\EmpresaScope;

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
                !$peca->empresa_id
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
}