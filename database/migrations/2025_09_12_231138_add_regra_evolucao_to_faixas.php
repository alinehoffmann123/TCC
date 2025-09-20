<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('faixas', function (Blueprint $table) {
            $table->unsignedTinyInteger('graus_totais')->default(4)->after('ordem');
            $table->unsignedInteger('tempo_min_meses')->default(18)->after('graus_totais'); // ajuste por faixa
        });
    }
    public function down(): void {
        Schema::table('faixas', function (Blueprint $table) {
            $table->dropColumn(['graus_totais','tempo_min_meses']);
        });
    }
};
