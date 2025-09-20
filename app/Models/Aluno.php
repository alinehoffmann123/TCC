<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model {
    use HasFactory;

    protected $fillable = [
        'nome','email','tipo','data_nascimento','telefone',
        'faixa','status','data_matricula','excluido','faixa_inicial_id'
    ];

    protected $casts = [
          'data_nascimento' => 'date'
        , 'data_matricula'  => 'date'
    ];

    public function turmas() {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
            ->withPivot('data_matricula','data_saida','status','papel')
            ->withTimestamps()
            ->wherePivot('status','ativo');
    }

    public function todasTurmas() {
        return $this->belongsToMany(Turma::class, 'turma_alunos', 'aluno_id', 'turma_id')
            ->withPivot('data_matricula','data_saida','status','papel')
            ->withTimestamps();
    }

    public function scopeAtivas($q)  { 
        return $q->where('excluido','N'); 
    }

    public function scopeAlunos($q) { 
        return $q->where('tipo','aluno'); 
    }
    public function scopeProfessores($q) { 
        return $q->where('tipo','professor'); 
    }

    public function faixaInicial() {
        return $this->belongsTo(Faixa::class, 'faixa_inicial_id');
    }

    public function graduacoes() {
        return $this->hasMany(Graduacao::class, 'aluno_id');
    }

    // pega a última por data_graduacao
    public function ultimaGraduacao() {
        return $this->hasOne(Graduacao::class, 'aluno_id')->latestOfMany('data_graduacao');
    }


    /** Nome da faixa atual (Faixa Nova da última graduação -> senão faixa_inicial -> senão string) */
    public function getFaixaAtualNomeAttribute(): ?string {
        $faixa = $this->ultimaGraduacao?->faixaNova ?? $this->faixaInicial;
        return $faixa?->nome ?? ($this->faixa ? ucfirst($this->faixa) : null);
    }

    /** slug para css (branca/azul/roxa/...) */
    public function getFaixaAtualSlugAttribute(): ?string {
        return $this->faixa_atual_nome ? strtolower($this->faixa_atual_nome) : null;
    }
}
