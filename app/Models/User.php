<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    /**
     * Campos que podem ser preenchidos via mass assignment.
     * Permite criar ou atualizar usuários diretamente com User::create($dados)
     */
    protected $fillable = [
          'name'
        , 'email'
        , 'password'
        , 'role'
        , 'aluno_id'
    ];

    /**
     * Relacionamento com a entidade Aluno.
     * Um usuário pode estar associado a um aluno.
     */
    public function pessoa() {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    /**
     * Verifica se o usuário possui determinado papel/role.
     *
     * @param string|array $aRoles - Papel ou lista de papéis a verificar
     * @return bool - Retorna true se o usuário tiver algum dos papéis
     */
    public function isRole(string|array $aRoles): bool {
        $aRoles = (array) $aRoles; 
        return in_array($this->role, $aRoles, true);
    }
}
