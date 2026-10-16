<?php

namespace App\Models;

use App\Support\Permissoes;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\Concerns\HasPublicUuid;

#[Fillable([
    'empresa_id',
    'name',
    'email',
    'password',
    'role',
    'perfil_acesso_id',
    'ativo',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasPublicUuid;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
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

    public function perfilAcesso()
    {
        return $this->belongsTo(PerfilAcesso::class, 'perfil_acesso_id')->withoutGlobalScopes();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers & Permissões
    |--------------------------------------------------------------------------
    */

    /** @var array{perfil: int|null, slugs: list<string>}|null */
    protected ?array $cachePermissoes = null;

    public function isAtivo(): bool
    {
        return (bool) ($this->ativo ?? true);
    }

    /**
     * Administrador da empresa (acesso administrativo completo, restrito à própria empresa).
     * Só é definido pelo servidor no cadastro da empresa; nunca por formulários de funcionários.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGerente(): bool
    {
        return $this->role === 'gerente';
    }

    public function isFuncionario(): bool
    {
        return $this->role === 'funcionario';
    }

    public function canDelete(): bool
    {
        return in_array($this->role, ['admin', 'gerente'], true) || $this->temPermissao('clientes.excluir');
    }

    /**
     * Ponto único de decisão de autorização do sistema.
     *
     *  - Falha de identificação (inativo, sem empresa, permissão desconhecida) => nega.
     *  - Administrador => acesso completo na própria empresa.
     *  - Permissões reservadas (empresa.* / assinatura.*) => somente administrador.
     *  - Demais => depende exclusivamente das permissões do perfil de acesso da MESMA empresa.
     */
    public function temPermissao(string $slug): bool
    {
        if (! $this->isAtivo() || empty($this->empresa_id) || ! Permissoes::existe($slug)) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        if (Permissoes::reservada($slug)) {
            return false;
        }

        return in_array($slug, $this->permissoesDoPerfil(), true);
    }

    /**
     * Permissões concedidas pelo perfil (uma única consulta por requisição). O vínculo só é
     * considerado se o perfil pertencer à mesma empresa do usuário.
     * Caso o usuário não tenha perfil (legado em testes/transição), aplica fallback seguro.
     *
     * @return list<string>
     */
    public function permissoesDoPerfil(): array
    {
        $perfilId = $this->perfil_acesso_id ? (int) $this->perfil_acesso_id : null;

        if ($this->cachePermissoes === null || $this->cachePermissoes['perfil'] !== $perfilId) {
            $slugs = [];

            if ($perfilId !== null) {
                $slugs = DB::table('perfil_permissao as pp')
                    ->join('permissoes as p', 'p.id', '=', 'pp.permissao_id')
                    ->join('perfis_acesso as pa', 'pa.id', '=', 'pp.perfil_acesso_id')
                    ->where('pp.perfil_acesso_id', $perfilId)
                    ->where('pa.empresa_id', $this->empresa_id)
                    ->pluck('p.slug')
                    ->all();
            } elseif (! empty($this->role) && isset(\App\Support\PerfisPadrao::legados()[$this->role])) {
                $slugs = \App\Support\PerfisPadrao::legados()[$this->role]['permissoes'];
            }

            $this->cachePermissoes = ['perfil' => $perfilId, 'slugs' => $slugs];
        }

        return $this->cachePermissoes['slugs'];
    }

    public function limparCachePermissoes(): void
    {
        $this->cachePermissoes = null;
    }

    public function canManageCompany(): bool
    {
        return $this->temPermissao('empresa.visualizar');
    }

    public function canManageSubscription(): bool
    {
        return $this->temPermissao('assinatura.gerenciar');
    }
}