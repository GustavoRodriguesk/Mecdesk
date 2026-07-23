<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('plano_id')->constrained('planos');
            $table->enum('metodo_pagamento', ['cartao', 'pix', 'free'])->default('cartao');
            $table->enum('status', [
                'pending',
                'authorized',
                'overdue',
                'paused',
                'cancelled',
                'expired'
            ])->default('pending');

            $table->string('mp_preapproval_id')->nullable()->unique();
            $table->string('mp_payer_id')->nullable();
            $table->decimal('preco_contratado', 10, 2);

            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('proximo_vencimento')->nullable();
            $table->dateTime('valido_ate')->nullable();
            $table->dateTime('data_cancelamento')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
