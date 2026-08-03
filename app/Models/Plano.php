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
        'preco_unico',
        'max_usuarios',
        'recursos',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'preco_mensal' => 'decimal:2',
            'preco_unico'  => 'decimal:2',
            'max_usuarios' => 'integer',
            'recursos'     => 'array',
            'ativo'        => 'boolean',
        ];
    }

    /**
     * Retorna o preço calculado estritamente no servidor com base no tipo de pagamento.
     */
    public function getPrecoForTipo(string $tipoPagamento): float
    {
        if ($tipoPagamento === 'unico') {
            // Se tiver preco_unico cadastrado, usa ele. Caso contrário, calcula com 15% de desconto anual (10 meses)
            return (float) ($this->preco_unico ?? round((float) $this->preco_mensal * 10, 2));
        }

        return (float) $this->preco_mensal;
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
