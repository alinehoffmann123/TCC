<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'data_nascimento',
        'telefone',
        'faixa',
        'status',
        'data_matricula',
        'excluido'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_matricula' => 'date',
    ];

    /**
     * Relacionamento com turmas
     */
    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
                    ->withPivot('data_matricula', 'data_saida', 'status')
                    ->withTimestamps()
                    ->wherePivot('status', 'ativo');
    }

    /**
     * Todas as turmas (incluindo inativas)
     */
    public function todasTurmas()
    {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
                    ->withPivot('data_matricula', 'data_saida', 'status')
                    ->withTimestamps();
    }

    /**
     * Scope para alunos ativos (não excluídos)
     */
    public function scopeAtivos($query)
    {
        return $query->where('excluido', 'N');
    }

    /**
     * Scope para alunos ativas (não excluídos)
     */
    public function scopeAtivas($query)
    {
        return $query->where('excluido', 'N');
    }

    /**
     * Scope para alunos excluídos
     */
    public function scopeExcluidos($query)
    {
        return $query->where('excluido', 'S');
    }
}
