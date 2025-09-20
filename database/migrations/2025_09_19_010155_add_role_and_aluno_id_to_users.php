<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['aluno','professor','admin'])->default('aluno')->after('email');
            $table->foreignId('aluno_id')->nullable()->after('role')
                  ->constrained('alunos')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('aluno_id');
            $table->dropColumn('role');
        });
    }
};

