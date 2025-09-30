<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Aluno;

class AlunoPolicy {
    /**
     * Determina se o usuário pode visualizar a evolução de um aluno.
     *
     * @param User $oUsers - Usuário autenticado
     * @param Aluno $oAluno - Aluno cuja evolução será visualizada
     * @return bool - Retorna true se o usuário tiver permissão
     *
     * Regras:
     *  - Professores pede visualizar qualquer aluno.
     *  - Alunos só podem visualizar a própria evolução.
     */
    public function viewEvolucao(User $oUsers, Aluno $oAluno) {
        if ($oUsers->isRole(['professor','admin'])) {
            return true;
        }

        return $oUsers->isRole('aluno') && $oUsers->aluno_id === $oAluno->id;
    }

    /**
     * Determina se o usuário pode alterar a evolução de um aluno.
     *
     * @param User $oUsers - Usuário autenticado
     * @param Aluno $oAluno - Aluno cuja evolução será alterada
     * @return bool - Retorna true se o usuário tiver permissão
     *
     * Regras:
     *  - Apenas professores e administradores podem alterar a evolução.
     */
    public function alterarEvolucao(User $oUsers, Aluno $oAluno) {
        return $oUsers->isRole(['professor','admin']);
    }
}