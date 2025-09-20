<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE turmas
            MODIFY COLUMN modalidade ENUM('gi','no-gi','mma','defesa-pessoal','gracie','luta-livre','combate')
            NOT NULL
        ");

        DB::table('turmas')->where('modalidade', 'mma')->update(['modalidade' => 'combate']);
        DB::table('turmas')->where('modalidade', 'defesa-pessoal')->update(['modalidade' => 'luta-livre']);
        DB::statement("
            ALTER TABLE turmas
            MODIFY COLUMN modalidade ENUM('gi','no-gi','gracie','luta-livre','combate')
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE turmas
            MODIFY COLUMN modalidade ENUM('gi','no-gi','mma','defesa-pessoal','gracie','luta-livre','combate')
            NOT NULL
        ");

        DB::table('turmas')->where('modalidade', 'combate')->update(['modalidade' => 'mma']);
        DB::table('turmas')->where('modalidade', 'luta-livre')->update(['modalidade' => 'defesa-pessoal']);

        DB::statement("
            ALTER TABLE turmas
            MODIFY COLUMN modalidade ENUM('gi','no-gi','mma','defesa-pessoal')
            NOT NULL
        ");
    }
};
