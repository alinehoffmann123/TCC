<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grau extends Model {

    /**
     * Define explicitamente a tabela associada ao model.
     */
    protected $table = 'graus';

    /**
     * Campos que podem ser preenchidos via mass assignment.
     * Permite criar ou atualizar registros diretamente com Grau::create($dados)
     */
    protected $fillable = [
          'aluno_id'
        , 'faixa_id'
        , 'numero'
        , 'data'
        , 'instrutor_id'
        , 'instrutor_nome'
        , 'observacoes'
    ];

    /**
     * Define o cast do campo 'data' para tipo date (Carbon).
     */
    protected $casts = [
        'data' => 'date'
    ];

    /**
     * Relacionamento com a faixa.
     * Cada grau pertence a uma faixa específica.
     */
    public function faixa() { 
        return $this->belongsTo(Faixa::class); 
    }
}