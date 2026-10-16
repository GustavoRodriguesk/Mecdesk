<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\HasPublicUuid;

class PerfilAcesso extends Model
{
    use HasPublicUuid;

    protected $table = 'perfis_acesso';

    /**
     * `empresa_id` NÃO é atribuível em massa: sempre definido pelo servidor.
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function (PerfilAcesso $perfil) {
            if (empty($perfil->empresa_id) && auth()->check()) {
                $perfil->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function permissoes(): BelongsToMany
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissao', 'perfil_acesso_id', 'permissao_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'perfil_acesso_id');
    }

    /**
     * @return list<string>
     */
    public function slugs(): array
    {
        return $this->permissoes()->pluck('slug')->all();
    }
}
