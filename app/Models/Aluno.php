<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model {
    use HasFactory;

    /**
     * Campos que podem ser preenchidos via mass assignment.
     */
    protected $fillable = [
        'nome','email','tipo','data_nascimento','telefone',
        'faixa','status','data_matricula','excluido','faixa_inicial_id'
    ];

    /**
     * Define o cast de atributos para tipos específicos.
     * Aqui, datas serão automaticamente tratadas como objetos Carbon.
     */
    protected $casts = [
          'data_nascimento' => 'date',
          'data_matricula'  => 'date'
    ];

    /**
     * Relacionamento com as turmas do aluno.
     * Retorna apenas turmas ativas.
     */
    public function turmas() {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
            ->withPivot('data_matricula','data_saida','status','papel') // Campos extras da tabela pivot
            ->withTimestamps()
            ->wherePivot('status','ativo'); // Filtra apenas turmas com status ativo
    }

    /**
     * Relacionamento com todas as turmas do aluno.
     * Retorna todas, independente do status.
     */
    public function todasTurmas() {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
            ->withPivot('data_matricula','data_saida','status','papel')
            ->withTimestamps();
    }

    /**
     * Escopo para filtrar apenas alunos ativos (não excluídos).
     */
    public function scopeAtivas($oQuery)  { 
        return $oQuery->where('excluido','N'); 
    }

    /**
     * Escopo para filtrar apenas alunos do tipo 'aluno'.
     */
    public function scopeAlunos($oQuery) { 
        return $oQuery->where('tipo','aluno'); 
    }

    /**
     * Escopo para filtrar apenas alunos do tipo 'professor'.
     */
    public function scopeProfessores($oQuery) { 
        return $oQuery->where('tipo','professor'); 
    }

    /**
     * Relacionamento com a faixa inicial do aluno.
     */
    public function faixaInicial() {
        return $this->belongsTo(Faixa::class, 'faixa_inicial_id');
    }

    /**
     * Relacionamento com todas as graduações do aluno.
     */
    public function graduacoes() {
        return $this->hasMany(Graduacao::class, 'aluno_id');
    }

    /**
     * Retorna a última graduação do aluno, baseado na data_graduacao.
     */
    public function ultimaGraduacao() {
        return $this->hasOne(Graduacao::class, 'aluno_id')->latestOfMany('data_graduacao');
    }

    /**
     * Retorna o nome da faixa atual do aluno.
     * Prioridade: última graduação -> faixa inicial -> valor de 'faixa' -> null
     */
    public function getFaixaAtualNomeAttribute(): ?string {
        $sFaixa = $this->ultimaGraduacao?->faixaNova ?? $this->faixaInicial;
        return $sFaixa?->nome ?? ($this->faixa ? ucfirst($this->faixa) : null);
    }

    /**
     * Retorna um "slug" da faixa atual para uso em CSS (ex: branca, azul, roxa...).
     */
    public function getFaixaAtualSlugAttribute(): ?string {
        return $this->faixa_atual_nome ? strtolower($this->faixa_atual_nome) : null;
    }
}
