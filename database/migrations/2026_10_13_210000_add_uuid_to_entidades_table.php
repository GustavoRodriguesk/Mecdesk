<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Tabelas de entidades que expõem identificadores nas URLs da aplicação.
     *
     * @var list<string>
     */
    private array $tabelas = [
        'clientes',
        'veiculos',
        'pecas',
        'servicos',
        'ordem_servicos',
        'perfis_acesso',
        'users',
    ];

    public function up(): void
    {
        foreach ($this->tabelas as $tabela) {
            if (Schema::hasTable($tabela) && ! Schema::hasColumn($tabela, 'uuid')) {
                Schema::table($tabela, function (Blueprint $table) {
                    $table->uuid('uuid')->nullable()->after('id');
                });

                // Popula com UUID único para quaisquer registros já existentes
                $ids = DB::table($tabela)->whereNull('uuid')->pluck('id');
                foreach ($ids as $id) {
                    DB::table($tabela)->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
                }

                Schema::table($tabela, function (Blueprint $table) {
                    $table->unique('uuid');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tabelas as $tabela) {
            if (Schema::hasTable($tabela) && Schema::hasColumn($tabela, 'uuid')) {
                Schema::table($tabela, function (Blueprint $table) use ($tabela) {
                    try {
                        $table->dropUnique("{$tabela}_uuid_unique");
                    } catch (\Throwable $e) {
                        // Ignora em drivers que não exigem drop explícito do índice antes do dropColumn
                    }
                    $table->dropColumn('uuid');
                });
            }
        }
    }
};
