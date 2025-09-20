<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAula extends Model {
    use HasFactory;

    protected $fillable = [
          'titulo'
        , 'descricao'
        , 'youtube_id'
        , 'duracao_minutos'
        , 'nivel'
        , 'modalidade'
        , 'excluido'
    ];

    /**
     * Scope para vídeo aulas ativas (não excluídas)
     */
    public function scopeAtivas($rQuery) {
        return $rQuery->where('excluido', 'N');
    }

    /**
     * Scope para vídeo aulas excluídas
     */
    public function scopeExcluidas($rQuery) {
        return $rQuery->where('excluido', 'S');
    }

    /**
     * Acessor para obter a URL de thumbnail do YouTube
     */
    public function getThumbnailUrlAttribute() {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    /**
     * Acessor para obter a URL de embed do YouTube
     */
    public function getEmbedUrlAttribute() {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    /**
     * Acessor para formatar a duração
     */
    public function getDuracaoFormatadaAttribute() {
        if (is_null($this->duracao_minutos)) {
            return 'N/A';
        }
        $nHoras = floor($this->duracao_minutos / 60);
        $nMinutos = $this->duracao_minutos % 60;
        
        if ($nHoras > 0) {
            return "{$nHoras}h {$nMinutos}min";
        }
        return "{$nMinutos}min";
    }
}
