<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
          'nome'
        , 'modalidade'
        , 'nivel'
        , 'instrutor'
        , 'dias_semana'
        , 'horario_inicio'
        , 'horario_fim'
        , 'capacidade_maxima'
        , 'status'
        , 'observacoes'
        , 'excluido'
    ];

    protected $casts = [
          'dias_semana'    => 'array'
        , 'horario_inicio' => 'datetime:H:i'
        , 'horario_fim'    => 'datetime:H:i'
    ];

    /**
     * Relacionamento com alunos (many-to-many)
     */
    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'turma_alunos', 'turma_id', 'aluno_id')
                    ->withPivot('data_matricula', 'data_saida', 'status')
                    ->withTimestamps()
                    ->wherePivot('status', 'ativo');
    }

    /**
     * Todos os alunos (incluindo inativos)
     */
    public function todosAlunos()
    {
        return $this->belongsToMany(Aluno::class, 'turma_alunos', 'turma_id', 'aluno_id')
                    ->withPivot('data_matricula', 'data_saida', 'status')
                    ->withTimestamps();
    }

    /**
     * Retorna o número de alunos ativos na turma
     */
    public function getNumeroAlunosAttribute()
    {
        return $this->alunos()->count();
    }

    /**
     * Retorna a porcentagem de ocupação da turma
     */
    public function getOcupacaoPercentualAttribute()
    {
        if ($this->capacidade_maxima == 0) return 0;
        return round(($this->numero_alunos / $this->capacidade_maxima) * 100);
    }

    /**
     * Retorna os dias da semana formatados
     */
    public function getDiasSemanaFormatadosAttribute() {
        $aDiasDaSemana = [
             'segunda'  => 'Seg'
            , 'terca'   => 'Ter'
            , 'quarta'  => 'Qua'
            , 'quinta'  => 'Qui'
            , 'sexta'   => 'Sex'
            , 'sabado'  => 'Sáb'
            , 'domingo' => 'Dom'
        ];

        return collect($this->dias_semana)->map(function ($sDia) use ($aDiasDaSemana) {
            return $aDiasDaSemana[$sDia] ?? $sDia;
        })->implode(', ');
    }

    /**
     * Scope para turmas ativas (não excluídas)
     */
    public function scopeAtivas($rQuery) {
        return $rQuery->where('excluido', 'N');
    }

    /**
     * Scope para turmas excluídas
     */
    public function scopeExcluidas($rQuery) {
        return $rQuery->where('excluido', 'S');
    }

    public function instrutor() {
        return $this->belongsTo(Aluno::class, 'instrutor_id');
    }

    /**
     * Nome do instrutor “smart”:
     * 1) usa a relação (novo modelo)
     * 2) cai pro texto legado se existir
     */
    public function getInstrutorNomeAttribute(): ?string {
        return $this->instrutor?->nome ?? ($this->instrutor ?? null);
    }
}
