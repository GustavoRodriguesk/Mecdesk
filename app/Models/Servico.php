<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'descricao',
        'valor_base',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($servico) {

            if (
                auth()->check() &&
                ! $servico->empresa_id
            ) {
                $servico->empresa_id =
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
        return $this->hasMany(OrdemServicoItem::class, 'servico_id');
    }
}
