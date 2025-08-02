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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('modalidade', ['gi', 'no-gi', 'mma', 'defesa-pessoal']);
            $table->enum('nivel', ['iniciante', 'intermediario', 'avancado', 'misto']);
            $table->string('instrutor');
            $table->json('dias_semana');
            $table->time('horario_inicio');
            $table->time('horario_fim');
            $table->integer('capacidade_maxima');
            $table->enum('status', ['ativa', 'inativa', 'pausada'])->default('ativa');
            $table->text('observacoes')->nullable();
            $table->char('excluido', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
