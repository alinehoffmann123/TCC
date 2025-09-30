<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaixaCriterio extends Model {

    /**
     * Campos que podem ser preenchidos via mass assignment.
     * Isso permite criar ou atualizar registros diretamente com FaixaCriterio::create($dados)
     */
    protected $fillable = [
          'faixa_id' 
        , 'chave' 
        , 'operador'
        , 'valor'
        , 'peso'  
    ];

    /**
     * Relacionamento com a faixa à qual o critério pertence.
     * Cada critério pertence a uma única faixa.
     */
    public function faixa() { 
        return $this->belongsTo(Faixa::class); 
    }
}