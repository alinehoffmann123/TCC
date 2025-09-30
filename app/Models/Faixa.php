<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faixa extends Model {
    use HasFactory;

    /**
     * Campos que podem ser preenchidos via mass assignment.
     */
    protected $fillable = [
          'nome'
        , 'ordem'
        , 'cor_hex' 
        , 'ativa'    
    ];

    /**
     * Relacionamento com os critérios da faixa.
     * Uma faixa pode ter vários critérios associados.
     */
    public function criterios() {
        return $this->hasMany(FaixaCriterio::class, 'faixa_id');
    }

    /**
     * Escopo para filtrar apenas faixas ativas.
     */
    public function scopeAtivas($oQuery) {
        return $oQuery->where('ativa', true);
    }

    /**
     * Escopo para ordenar as faixas pela ordem definida.
     */
    public function scopeOrdenadas($oQuery) {
        return $oQuery->orderBy('ordem');
    }
}
