<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->decimal('preco_mensal', 10, 2)->default(0.00);
            $table->integer('max_usuarios')->default(1);
            $table->json('recursos')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
