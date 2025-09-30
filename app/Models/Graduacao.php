<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduacao extends Model {

    /**
     * Define explicitamente a tabela associada ao model.
     */
    protected $table = 'graduacoes';

    /**
     * Campos que podem ser preenchidos via mass assignment.
     * Permite criar ou atualizar graduações diretamente com Graduacao::create($dados)
     */
    protected $fillable = [
          'aluno_id'
        , 'faixa_anterior_id'
        , 'faixa_nova_id'      
        , 'data_graduacao'
        , 'instrutor_id'    
        , 'instrutor_nome' 
        , 'observacoes'
    ];

    /**
     * Define o cast do campo 'data_graduacao' para tipo date (Carbon).
     */
    protected $casts = [
        'data_graduacao' => 'date'
    ];

    /**
     * Relacionamento com a nova faixa.
     * Cada graduação pertence a uma faixa nova.
     */
    public function faixaNova() { 
        return $this->belongsTo(Faixa::class, 'faixa_nova_id');
    }

    /**
     * Relacionamento com a faixa anterior.
     * Cada graduação também pode ter uma faixa anterior associada.
     */
    public function faixaAnterior() { 
        return $this->belongsTo(Faixa::class, 'faixa_anterior_id'); 
    }
}
