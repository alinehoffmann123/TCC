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
        Schema::create('video_aulas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('youtube_id'); 
            $table->integer('duracao_minutos')->nullable();
            $table->enum('nivel', ['iniciante', 'intermediario', 'avancado', 'misto']);
            $table->enum('modalidade', ['gi','no-gi','gracie','luta-livre','combate']);
            $table->char('excluido', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_aulas');
    }
};
