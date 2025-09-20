<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Faixa extends Model {
    protected $fillable = ['nome','ordem','cor_hex','ativa'];

    public function criterios() {
        return $this->hasMany(FaixaCriterio::class);
    }
}