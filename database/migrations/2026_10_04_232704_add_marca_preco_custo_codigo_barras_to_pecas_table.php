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
        Schema::table('pecas', function (Blueprint $table) {
            $table->string('marca')->nullable()->after('nome');
            $table->string('codigo_barras')->nullable()->after('codigo');
            $table->decimal('preco_custo', 10, 2)->default(0)->after('codigo_barras');
            $table->decimal('preco_venda', 10, 2)->nullable()->after('preco_custo');
        });

        \Illuminate\Support\Facades\DB::table('pecas')
            ->whereNull('preco_venda')
            ->update([
                'preco_venda' => \Illuminate\Support\Facades\DB::raw('valor_unitario'),
                'preco_custo' => 0,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pecas', function (Blueprint $table) {
            $table->dropColumn(['marca', 'codigo_barras', 'preco_custo', 'preco_venda']);
        });
    }
};
