<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoAula;

class VideoAulasController extends Controller {

    /**
     * Exibe uma lista de vídeo aulas ativas.
     */
    public function index(Request $oRequest) {
        $aDadosVideo = VideoAula::ativas();

        if ($oRequest->has('search') && $oRequest->search != '') {
            $aDadosVideo->where(function($q) use ($oRequest) {
                $q->where('titulo', 'like', '%' . $oRequest->search . '%')
                  ->orWhere('descricao', 'like', '%' . $oRequest->search . '%');
            });
        }

        if ($oRequest->has('modalidade') && $oRequest->modalidade != '') {
            $aDadosVideo->where('modalidade', $oRequest->modalidade);
        }

        if ($oRequest->has('nivel') && $oRequest->nivel != '') {
            $aDadosVideo->where('nivel', $oRequest->nivel);
        }

        $aVideoAulas = $aDadosVideo->orderBy('created_at', 'desc')->paginate(12);
        
        return view('video_aulas.index', compact('aVideoAulas'));
    }

    /**
     * Exibe o formulário para criar uma nova vídeo aula.
     */
    public function create() {
        return view('video_aulas.cadastro');
    }

    /**
     * Armazena uma nova vídeo aula no banco de dados.
     */
    public function store(Request $oRequest) {
        $oRequest->validate([
              'titulo'      => 'required|string|max:255'
            , 'descricao'   => 'nullable|string|max:1000'
            , 'url_youtube' => [
                  'required'
                , 'string'
                , 'url'
                , function ($aAttribute, $sValue, $sValidacao) {
                    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/';
                    if (!preg_match($pattern, $sValue)) {
                        $sValidacao('A URL do YouTube não é válida. Por favor, insira uma URL de vídeo do YouTube válida.');
                    }
                },
            ]
            , 'duracao_minutos' => 'nullable|integer|min:1|max:999'
            , 'nivel'           => 'required|in:iniciante,intermediario,avancado,misto'
            , 'modalidade'      => 'required|in:gi,no-gi,gracie,luta-livre,combate'
        ], [
             'titulo.required'      => 'O título da vídeo aula é obrigatório.'
           , 'url_youtube.required' => 'A URL do YouTube é obrigatória.'
           , 'url_youtube.url'      => 'A URL do YouTube deve ser um formato de URL válido.'
           , 'duracao_minutos.min'  => 'A duração deve ser de pelo menos 1 minuto.'
           , 'nivel.required'       => 'Selecione o nível da vídeo aula.'
           , 'modalidade.required'  => 'Selecione a modalidade da vídeo aula.'
        ]);

        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/', $oRequest->url_youtube, $matches);
        $iCodigoVideo = $matches[1] ?? null;

        if (!$iCodigoVideo) {
            return redirect()->back()->withInput()->withErrors(['url_youtube' => 'Não foi possível extrair o ID do vídeo do YouTube da URL fornecida.']);
        }

        VideoAula::create([
              'titulo'          => $oRequest->titulo
            , 'descricao'       => $oRequest->descricao
            , 'youtube_id'      => $iCodigoVideo
            , 'duracao_minutos' => $oRequest->duracao_minutos
            , 'nivel'           => $oRequest->nivel
            , 'modalidade'      => $oRequest->modalidade
        ]);

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula cadastrada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma vídeo aula específica.
     */
    public function show(string $iCodigo) {
        $oVideoAulas = VideoAula::ativas()->findOrFail($iCodigo);
        return view('video_aulas.detalhe', compact('oVideoAulas'));
    }

    /**
     * Exibe o formulário para editar uma vídeo aula.
     */
    public function edit(string $iCodigo) {
        $oVideoAula = VideoAula::ativas()->findOrFail($iCodigo);
        return view('video_aulas.alterar', compact('oVideoAula'));
    }

    /**
     * Atualiza uma vídeo aula no banco de dados.
     */
    public function update(Request $oRequest, string $iCodigo) {
        $oVideoAula = VideoAula::ativas()->findOrFail($iCodigo);

        $oRequest->validate([
              'titulo'      => 'required|string|max:255'
            , 'descricao'   => 'nullable|string|max:1000'
            , 'url_youtube' => [
                  'required'
                , 'string'
                , 'url'
                , function ($aAttribute, $sValue, $sValidacao) {
                    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/';
                    if (!preg_match($pattern, $sValue)) {
                        $sValidacao('A URL do YouTube não é válida. Por favor, insira uma URL de vídeo do YouTube válida.');
                    }
                },
            ]
            , 'duracao_minutos' => 'nullable|integer|min:1|max:999'
            , 'nivel'           => 'required|in:iniciante,intermediario,avancado,misto'
            , 'modalidade'      => 'required|in:gi,no-gi,gracie,luta-livre,combate'
        ], [
              'titulo.required'      => 'O título da vídeo aula é obrigatório.'
            , 'url_youtube.required' => 'A URL do YouTube é obrigatória.'
            , 'url_youtube.url'      => 'A URL do YouTube deve ser um formato de URL válido.'
            , 'duracao_minutos.min'  => 'A duração deve ser de pelo menos 1 minuto.'
            , 'nivel.required'       => 'Selecione o nível da vídeo aula.'
            , 'modalidade.required'  => 'Selecione a modalidade da vídeo aula.'
        ]);

        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/', $oRequest->url_youtube, $matches);
        $iCodigoVideo = $matches[1] ?? null;

        if (!$iCodigoVideo) {
            return redirect()->back()->withInput()->withErrors(['url_youtube' => 'Não foi possível extrair o ID do vídeo do YouTube da URL fornecida.']);
        }

        $oVideoAula->update([
              'titulo'          => $oRequest->titulo
            , 'descricao'       => $oRequest->descricao
            , 'youtube_id'      => $iCodigoVideo
            , 'duracao_minutos' => $oRequest->duracao_minutos
            , 'nivel'           => $oRequest->nivel
            , 'modalidade'      => $oRequest->modalidade
        ]);

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula atualizada com sucesso!');
    }

    /**
     * Marca uma vídeo aula como 'excluida'.
     */
    public function destroy(string $id) {
        $oVideoAula = VideoAula::findOrFail($id);
        $oVideoAula->excluido = 'S';
        $oVideoAula->save();

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula removida com sucesso!');
    }
}
