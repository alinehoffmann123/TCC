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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->enum('faixa', ['branca', 'azul', 'roxa', 'marrom', 'preta']);
            $table->enum('status', ['ativo', 'inativo', 'trancado'])->default('ativo');
            $table->date('data_matricula');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};