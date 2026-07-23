<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assinatura_id')->constrained('assinaturas')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();

            $table->string('mp_payment_id')->nullable()->unique();
            $table->enum('metodo_pagamento', ['cartao', 'pix'])->default('cartao');
            $table->string('status', 50)->default('pending');
            $table->string('status_detail', 255)->nullable();
            $table->decimal('valor', 10, 2);

            $table->dateTime('data_vencimento')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->json('payload_resposta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
