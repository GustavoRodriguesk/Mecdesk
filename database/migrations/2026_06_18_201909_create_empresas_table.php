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
Schema::create('empresas', function (Blueprint $table) {
    $table->id();

    $table->string('nome_fantasia');
    $table->string('razao_social')->nullable();

    $table->string('cnpj', 18)->unique()->nullable();

    $table->string('email')->nullable();
    $table->string('telefone', 20)->nullable();
    $table->string('whatsapp', 20)->nullable();

    $table->string('cep', 9)->nullable();
    $table->string('logradouro')->nullable();
    $table->string('numero')->nullable();
    $table->string('bairro')->nullable();
    $table->string('cidade')->nullable();
    $table->string('estado', 2)->nullable();

    $table->string('logo')->nullable();

    $table->enum('plano', [
        'starter',
        'pro',
        'business'
    ])->default('starter');

    $table->boolean('ativo')->default(true);

    $table->timestamps();
});    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
