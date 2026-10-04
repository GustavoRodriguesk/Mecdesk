<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peca extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'nome',
        'marca',
        'codigo',
        'codigo_barras',
        'estoque',
        'preco_custo',
        'preco_venda',
        'valor_unitario',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'valor_unitario' => 'decimal:2',
        'estoque' => 'integer',
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

        static::saving(function ($peca) {
            if ($peca->preco_venda !== null && ($peca->valor_unitario === null || $peca->isDirty('preco_venda'))) {
                $peca->valor_unitario = $peca->preco_venda;
            } elseif ($peca->valor_unitario !== null && ($peca->preco_venda === null || $peca->isDirty('valor_unitario'))) {
                $peca->preco_venda = $peca->valor_unitario;
            }
            if ($peca->preco_custo === null) {
                $peca->preco_custo = 0;
            }
            if ($peca->estoque === null) {
                $peca->estoque = 0;
            }
        });
    }

    public function getPrecoVendaAttribute($value)
    {
        return $value ?? $this->attributes['valor_unitario'] ?? 0;
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
