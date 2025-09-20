<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // adiciona o tipo (aluno/professor) após o email
            if (!Schema::hasColumn('alunos', 'tipo')) {
                $table->enum('tipo', ['aluno', 'professor'])->default('aluno')->after('email');
                $table->index('tipo');
            }
        });

        // backfill para registros antigos
        DB::table('alunos')->whereNull('tipo')->update(['tipo' => 'aluno']);

        // professor não precisa de faixa/data_matricula -> deixar opcionais
        // requer doctrine/dbal para ->change()
        Schema::table('alunos', function (Blueprint $table) {
            if (Schema::hasColumn('alunos', 'faixa')) {
                $table->string('faixa')->nullable()->change();
            }
            if (Schema::hasColumn('alunos', 'data_matricula')) {
                $table->date('data_matricula')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            if (Schema::hasColumn('alunos', 'tipo')) {
                $table->dropIndex(['tipo']);
                $table->dropColumn('tipo');
            }
        });
    }
};
