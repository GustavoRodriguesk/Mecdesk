<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\EmpresaScope;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'cpf_cnpj',
        'telefone',
        'email',
        'endereco',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            new EmpresaScope
        );

        static::creating(function ($cliente) {

            if (
                auth()->check() &&
                empty($cliente->empresa_id)
            ) {
                $cliente->empresa_id =
                    auth()->user()->empresa_id;
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function empresa()
    {
        return $this->belongsTo(
            Empresa::class
        );
    }

    public function veiculos()
    {
        return $this->hasMany(
            Veiculo::class
        );
    }

    public function ordensServico()
    {
        return $this->hasMany(
            OrdemServico::class
        );
    }
}