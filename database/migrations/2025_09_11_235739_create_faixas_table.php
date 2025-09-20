<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('faixas', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); 
            $table->unsignedInteger('ordem');
            $table->string('cor_hex', 7)->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();

            $table->unique(['nome']);
            $table->unique(['ordem']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('faixas');
    }
};
