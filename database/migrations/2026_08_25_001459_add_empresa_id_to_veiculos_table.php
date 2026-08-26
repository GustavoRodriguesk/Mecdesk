<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            // Se a coluna não existe, adicione
            if (! Schema::hasColumn('veiculos', 'empresa_id')) {
                $table->foreignId('empresa_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Remove a constraint antiga
        Schema::table('veiculos', function (Blueprint $table) {
            $table->dropUnique(['placa']);
        });

        // Cria a nova constraint composta
        Schema::table('veiculos', function (Blueprint $table) {
            $table->unique(['empresa_id', 'placa']);
        });
    }

    public function down(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            $table->dropUnique(['empresa_id', 'placa']);
            $table->unique(['placa']);
            $table->dropForeignKeyIfExists(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
