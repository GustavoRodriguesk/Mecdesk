<?php

use App\Support\Permissoes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissoes', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('descricao')->nullable();
            $table->string('modulo', 50)->index();
            $table->timestamps();
        });

        Schema::create('perfis_acesso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nome', 100);
            $table->string('descricao')->nullable();
            $table->timestamps();

            $table->unique(['empresa_id', 'nome']);
        });

        Schema::create('perfil_permissao', function (Blueprint $table) {
            $table->foreignId('perfil_acesso_id')->constrained('perfis_acesso')->cascadeOnDelete();
            $table->foreignId('permissao_id')->constrained('permissoes')->cascadeOnDelete();

            $table->primary(['perfil_acesso_id', 'permissao_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            // nullOnDelete: a aplicação impede excluir perfis em uso; no banco, a ausência de
            // perfil significa "sem acesso operacional" (nunca concede acesso por padrão).
            $table->foreignId('perfil_acesso_id')
                ->nullable()
                ->after('role')
                ->constrained('perfis_acesso')
                ->nullOnDelete();
        });

        // Catálogo inicial (idempotente; também pode ser reaplicado por `permissoes:sincronizar`).
        Permissoes::sincronizar();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('perfil_acesso_id');
        });

        Schema::dropIfExists('perfil_permissao');
        Schema::dropIfExists('perfis_acesso');
        Schema::dropIfExists('permissoes');
    }
};
