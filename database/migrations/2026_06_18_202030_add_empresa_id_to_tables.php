<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });

    Schema::table('clientes', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });

    Schema::table('veiculos', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });

    Schema::table('pecas', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });

    Schema::table('servicos', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });

    Schema::table('ordem_servicos', function (Blueprint $table) {
        $table->foreignId('empresa_id')
            ->nullable()
            ->constrained()
            ->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
