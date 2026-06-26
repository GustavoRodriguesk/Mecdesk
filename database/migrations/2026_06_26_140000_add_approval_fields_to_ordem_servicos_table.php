<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar 'reprovada' ao enum de status se for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ordem_servicos MODIFY COLUMN status ENUM(
                'aberta',
                'aguardando_aprovacao',
                'aprovada',
                'reprovada',
                'em_andamento',
                'concluida',
                'entregue',
                'cancelada'
            ) DEFAULT 'aberta'");
        }

        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->string('approval_token', 64)->nullable()->unique()->after('data_saida');
            $table->string('approval_status', 20)->nullable()->after('approval_token');
            $table->timestamp('approval_requested_at')->nullable()->after('approval_status');
            $table->timestamp('approval_response_at')->nullable()->after('approval_requested_at');
            $table->text('approval_comment')->nullable()->after('approval_response_at');
            $table->string('approval_ip', 45)->nullable()->after('approval_comment');
            $table->text('approval_user_agent')->nullable()->after('approval_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropColumn([
                'approval_token',
                'approval_status',
                'approval_requested_at',
                'approval_response_at',
                'approval_comment',
                'approval_ip',
                'approval_user_agent',
            ]);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ordem_servicos MODIFY COLUMN status ENUM(
                'aberta',
                'aguardando_aprovacao',
                'aprovada',
                'em_andamento',
                'concluida',
                'entregue',
                'cancelada'
            ) DEFAULT 'aberta'");
        }
    }
};
