<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagamentos', 'mp_authorized_payment_id')) {
                $table->string('mp_authorized_payment_id')->nullable()->unique()->after('mp_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            if (Schema::hasColumn('pagamentos', 'mp_authorized_payment_id')) {
                $table->dropColumn('mp_authorized_payment_id');
            }
        });
    }
};
