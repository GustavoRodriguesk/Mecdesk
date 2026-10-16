<?php

use App\Support\PerfisPadrao;
use App\Support\Permissoes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Migra os usuários existentes para o modelo de perfis, sem apagar nenhum registro.
 *
 *  - role = admin       : permanece administrador da empresa (nenhum perfil necessário).
 *  - role = gerente     : recebe o perfil "Gerente (legado)", que reproduz o acesso anterior.
 *  - role = funcionario : recebe o perfil "Funcionário (legado)", que reproduz o acesso anterior.
 *  - qualquer outro valor (ambíguo), usuários sem empresa ou perfis já definidos: não são tocados.
 *    Usuários ambíguos ficam SEM perfil (sem acesso) e são registrados no log para revisão manual.
 *
 * Nenhum usuário comum recebe permissões reservadas (empresa.* / assinatura.*).
 */
return new class extends Migration
{
    public function up(): void
    {
        Permissoes::sincronizar();

        $idsPermissao = DB::table('permissoes')->pluck('id', 'slug');

        DB::transaction(function () use ($idsPermissao) {
            $empresaIds = DB::table('users')
                ->whereNotNull('empresa_id')
                ->whereNull('perfil_acesso_id')
                ->distinct()
                ->pluck('empresa_id');

            foreach ($empresaIds as $empresaId) {
                foreach (PerfisPadrao::legados() as $role => $modelo) {
                    $usuarios = DB::table('users')
                        ->where('empresa_id', $empresaId)
                        ->where('role', $role)
                        ->whereNull('perfil_acesso_id')
                        ->pluck('id');

                    if ($usuarios->isEmpty()) {
                        continue;
                    }

                    $perfilId = DB::table('perfis_acesso')
                        ->where('empresa_id', $empresaId)
                        ->where('nome', $modelo['nome'])
                        ->value('id');

                    if (! $perfilId) {
                        $perfilId = DB::table('perfis_acesso')->insertGetId([
                            'empresa_id' => $empresaId,
                            'nome' => $modelo['nome'],
                            'descricao' => $modelo['descricao'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $linhas = collect(Permissoes::normalizar($modelo['permissoes']))
                            ->map(fn ($slug) => [
                                'perfil_acesso_id' => $perfilId,
                                'permissao_id' => $idsPermissao[$slug],
                            ])
                            ->all();

                        DB::table('perfil_permissao')->insert($linhas);
                    }

                    DB::table('users')->whereIn('id', $usuarios)->update(['perfil_acesso_id' => $perfilId]);
                }
            }

            $ambiguos = DB::table('users')
                ->whereNotNull('empresa_id')
                ->whereNull('perfil_acesso_id')
                ->where(function ($q) {
                    $q->whereNull('role')->orWhereNotIn('role', ['admin', 'gerente', 'funcionario']);
                })
                ->pluck('id');

            if ($ambiguos->isNotEmpty()) {
                Log::warning('Migração de permissões: usuários com função ambígua ficaram sem perfil (sem acesso).', [
                    'user_ids' => $ambiguos->all(),
                ]);
            }
        });
    }

    public function down(): void
    {
        $perfis = DB::table('perfis_acesso')
            ->whereIn('nome', collect(PerfisPadrao::legados())->pluck('nome'))
            ->pluck('id');

        DB::table('users')->whereIn('perfil_acesso_id', $perfis)->update(['perfil_acesso_id' => null]);
        DB::table('perfil_permissao')->whereIn('perfil_acesso_id', $perfis)->delete();
        DB::table('perfis_acesso')->whereIn('id', $perfis)->delete();
    }
};
