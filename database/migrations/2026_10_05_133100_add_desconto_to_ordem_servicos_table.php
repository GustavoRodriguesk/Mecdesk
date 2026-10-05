<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('observacoes');
            $table->string('desconto_tipo', 20)->nullable()->after('subtotal');
            $table->decimal('desconto_valor', 10, 2)->default(0)->after('desconto_tipo');
            $table->decimal('valor_desconto', 10, 2)->default(0)->after('desconto_valor');
        });

        // Preenche o subtotal inicial com o valor_total atual das ordens existentes
        DB::table('ordem_servicos')->update([
            'subtotal' => DB::raw('valor_total')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'desconto_tipo',
                'desconto_valor',
                'valor_desconto',
            ]);
        });
    }
};
