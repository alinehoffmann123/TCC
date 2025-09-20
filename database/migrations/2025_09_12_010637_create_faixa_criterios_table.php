<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('faixa_criterios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faixa_id');
            $table->string('chave');
            $table->string('operador')->default('>=');
            $table->decimal('valor', 8,2)->default(0);
            $table->decimal('peso', 5,2)->nullable(); 
            $table->timestamps();

            $table->index('faixa_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('faixa_criterios');
    }
};
