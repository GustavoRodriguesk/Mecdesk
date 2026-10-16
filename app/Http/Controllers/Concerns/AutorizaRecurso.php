<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Routing\Controllers\Middleware;

/**
 * Mapeia as ações de um resource controller para as abilities da respectiva Policy,
 * aplicando a autorização ANTES de qualquer validação ou execução da ação.
 *
 * Uso (no controller que implementa HasMiddleware):
 *   public static function middleware(): array { return static::middlewareDoRecurso(Cliente::class, 'cliente'); }
 */
trait AutorizaRecurso
{
    /**
     * @param  class-string  $modelo
     * @return list<Middleware>
     */
    protected static function middlewareDoRecurso(string $modelo, string $parametro): array
    {
        return [
            new Middleware("can:viewAny,{$modelo}", only: ['index']),
            new Middleware("can:view,{$parametro}", only: ['show']),
            new Middleware("can:create,{$modelo}", only: ['create', 'store']),
            new Middleware("can:update,{$parametro}", only: ['edit', 'update']),
            new Middleware("can:delete,{$parametro}", only: ['destroy']),
        ];
    }
}
