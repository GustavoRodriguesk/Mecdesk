<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permissao extends Model
{
    protected $table = 'permissoes';

    protected $fillable = [
        'slug',
        'descricao',
        'modulo',
    ];

    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(PerfilAcesso::class, 'perfil_permissao', 'permissao_id', 'perfil_acesso_id')
            ->withoutGlobalScopes();
    }
}
