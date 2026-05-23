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
   Schema::create('ordem_servico_itens', function (Blueprint $table) {
    $table->id();

   $table->foreignId('ordem_servico_id')
    ->constrained('ordens_servico')
    ->onDelete('cascade');

    $table->enum('tipo_item', ['peca', 'servico']);

    $table->foreignId('peca_id')
        ->nullable()
        ->constrained();

    $table->foreignId('servico_id')
        ->nullable()
        ->constrained();

    $table->string('descricao');

    $table->integer('quantidade');

    $table->decimal('valor_unitario', 10, 2);

    $table->decimal('valor_total', 10, 2);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_servico_items');
    }
};
