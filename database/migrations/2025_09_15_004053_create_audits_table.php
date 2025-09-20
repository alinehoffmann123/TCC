<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('audits', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('aluno_id')->nullable()->constrained('alunos')->nullOnDelete();
      $table->string('acao', 60); 
      $table->json('detalhes')->nullable(); 
      $table->string('ip', 45)->nullable();
      $table->string('user_agent')->nullable();
      $table->timestamps();
    });
  }
  public function down() { Schema::dropIfExists('audits'); }
};
