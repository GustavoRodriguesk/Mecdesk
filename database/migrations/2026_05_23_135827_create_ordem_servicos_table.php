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
        Schema::create('ordens_servico', function (Blueprint $table) {
    $table->id();

    $table->string('numero_os')->unique();

    $table->foreignId('cliente_id')->constrained();
    $table->foreignId('veiculo_id')->constrained();
    $table->foreignId('user_id')->constrained();

    $table->enum('status', [
        'aberta',
        'aguardando_aprovacao',
        'aprovada',
        'em_andamento',
        'concluida',
        'entregue',
        'cancelada'
    ])->default('aberta');

    $table->text('descricao_problema');
    $table->text('observacoes')->nullable();

    $table->decimal('valor_total', 10, 2)->default(0);

    $table->boolean('aprovado_cliente')->default(false);

    $table->dateTime('data_entrada');
    $table->dateTime('data_saida')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_servicos');
    }
};
