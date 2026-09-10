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
        Schema::table('clientes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('veiculos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pecas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('servicos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('servicos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pecas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('veiculos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
