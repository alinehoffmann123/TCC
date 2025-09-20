<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('turma_alunos', function (Blueprint $table) {
            if (!Schema::hasColumn('turma_alunos', 'papel')) {
                $table->enum('papel', ['aluno','professor'])->default('aluno')->after('aluno_id');
                $table->index('papel');
            }
        });

        DB::table('turma_alunos')->whereNull('papel')->update(['papel' => 'aluno']);

        Schema::table('turma_alunos', function (Blueprint $table) {
            $idx = 'ux_turma_aluno_papel';
            if (!collect(DB::select("SHOW INDEX FROM turma_alunos"))->contains(fn($i) => $i->Key_name === $idx)) {
                $table->unique(['turma_id','aluno_id','papel'], $idx);
            }
        });
    }

    public function down(): void
    {
        Schema::table('turma_alunos', function (Blueprint $table) {
            if (Schema::hasColumn('turma_alunos', 'papel')) {
                $table->dropUnique('ux_turma_aluno_papel');
                $table->dropIndex(['papel']);
                $table->dropColumn('papel');
            }
        });
    }
};
