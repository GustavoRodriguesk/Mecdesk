<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $table = 'planos';

    protected $fillable = [
        'slug',
        'nome',
        'descricao',
        'preco_mensal',
        'max_usuarios',
        'recursos',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'preco_mensal' => 'decimal:2',
            'max_usuarios' => 'integer',
            'recursos'     => 'array',
            'ativo'        => 'boolean',
        ];
    }

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}
