<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FaixaCriterio extends Model {
    protected $fillable = ['faixa_id','chave','operador','valor','peso'];
    
    public function faixa() { 
        return $this->belongsTo(Faixa::class); 
    }
}