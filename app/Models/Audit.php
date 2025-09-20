<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model {
  protected $fillable = ['user_id','aluno_id','acao','detalhes','ip','user_agent'];
  protected $casts = ['detalhes' => 'array'];
}