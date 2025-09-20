<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('graus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('faixa_id');
            $table->unsignedTinyInteger('numero'); 
            $table->date('data');
            $table->unsignedBigInteger('instrutor_id')->nullable();
            $table->string('instrutor_nome')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['aluno_id','faixa_id','numero']);
            $table->unique(['aluno_id','faixa_id','numero']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('graus');
    }
};