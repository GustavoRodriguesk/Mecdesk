<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordem_servicos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cliente_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('veiculo_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('descricao_problema');

            $table->enum('status', [
                'aberta',
                'em_andamento',
                'aguardando_aprovacao',
                'concluida',
                'cancelada'
            ])->default('aberta');

            $table->decimal('valor_total', 10, 2)
                ->default(0);

            $table->date('data_entrada')
                ->nullable();

            $table->date('data_saida')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_servicos');
    }
};