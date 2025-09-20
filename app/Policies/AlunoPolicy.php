<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Aluno;

class AlunoPolicy
{
    public function viewEvolucao(User $user, Aluno $aluno) {
        if ($user->isRole(['professor','admin'])) return true;
        return $user->isRole('aluno') && $user->aluno_id === $aluno->id;
    }

    public function alterarEvolucao(User $user, Aluno $aluno) {
        return $user->isRole(['professor','admin']);
    }
}