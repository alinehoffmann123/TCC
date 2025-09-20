<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('graduacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('faixa_anterior_id')->nullable();
            $table->unsignedBigInteger('faixa_nova_id');
            $table->date('data_graduacao');
            $table->unsignedBigInteger('instrutor_id')->nullable(); 
            $table->string('instrutor_nome')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['aluno_id', 'data_graduacao']);
            $table->index('faixa_nova_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('graduacoes');
    }
};
