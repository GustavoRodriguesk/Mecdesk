<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Trait para entidades que utilizam UUIDs públicos em rotas,
 * mantendo compatibilidade com buscas por chave primária inteira e preservando escopos de tenant.
 */
trait HasPublicUuid
{
    /**
     * Registra o evento de criação para preencher o UUID automaticamente se ainda não definido.
     */
    protected static function bootHasPublicUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Identificador padrão utilizado na geração e resolução de rotas.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Resolve a vinculação do modelo na rota (Route Model Binding):
     *  1. Se o valor for um UUID válido, busca pela coluna `uuid`.
     *  2. Se for numérico (fallback para chamadas legadas e testes unitários), busca por `id`.
     *  Ambos os caminhos respeitam o EmpresaScope global do modelo.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field && $field !== 'uuid') {
            return parent::resolveRouteBinding($value, $field);
        }

        if (Str::isUuid((string) $value)) {
            return $this->where('uuid', $value)->first();
        }

        if (is_numeric($value)) {
            return $this->where('id', (int) $value)->first();
        }

        return null;
    }
}
