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
            $table->string('telefone_2', 20)->nullable()->after('possui_whatsapp');
            $table->string('telefone_3', 20)->nullable()->after('telefone_2');
            $table->string('instagram', 100)->nullable()->after('email');
            $table->string('cep', 10)->nullable()->after('endereco');
            $table->string('rua', 255)->nullable()->after('cep');
            $table->string('numero', 20)->nullable()->after('rua');
            $table->string('complemento', 100)->nullable()->after('numero');
            $table->string('bairro', 100)->nullable()->after('complemento');
            $table->string('cidade', 100)->nullable()->after('bairro');
            $table->string('estado', 2)->nullable()->after('cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'telefone_2',
                'telefone_3',
                'instagram',
                'cep',
                'rua',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado',
            ]);
        });
    }
};
