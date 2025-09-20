<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grau extends Model {
    protected $table = 'graus';
    protected $fillable = ['aluno_id','faixa_id','numero','data','instrutor_id','instrutor_nome','observacoes'];
    protected $casts = ['data' => 'date'];

    public function faixa() { 
        return $this->belongsTo(Faixa::class); 
    }
}