<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = [
        'name', 'email', 'password', 'role', 'aluno_id',
    ];

    public function pessoa() {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function isRole(string|array $roles): bool {
        $roles = (array) $roles;
        return in_array($this->role, $roles, true);
    }
}
