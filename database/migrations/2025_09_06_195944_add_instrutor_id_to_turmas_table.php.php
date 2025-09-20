<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('turmas', function (Blueprint $table) {
            $table->unsignedBigInteger('instrutor_id')->nullable()->after('nome');
            $table->foreign('instrutor_id')
                  ->references('id')->on('alunos')
                  ->nullOnDelete(); 
        });
    }

    public function down(): void {
        Schema::table('turmas', function (Blueprint $table) {
            $table->dropForeign(['instrutor_id']);
            $table->dropColumn('instrutor_id');
        });
    }
};
