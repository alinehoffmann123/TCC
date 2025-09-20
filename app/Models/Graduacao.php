<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduacao extends Model {
    protected $table = 'graduacoes';
    protected $fillable = [
        'aluno_id','faixa_anterior_id','faixa_nova_id',
        'data_graduacao','instrutor_id','instrutor_nome','observacoes',
    ];
    protected $casts = ['data_graduacao' => 'date'];

    public function faixaNova() { 
        return $this->belongsTo(Faixa::class, 'faixa_nova_id');
    }

    public function faixaAnterior() { 
        return $this->belongsTo(Faixa::class, 'faixa_anterior_id'); 
    }
}
