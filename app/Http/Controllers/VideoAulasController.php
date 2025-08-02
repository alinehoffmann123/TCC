<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoAula;
use Illuminate\Validation\Rule; // Importar Rule para validações mais complexas, embora não seja estritamente necessário aqui

class VideoAulasController extends Controller
{
    /**
     * Exibe uma lista de vídeo aulas ativas.
     */
    public function index(Request $request)
    {
        $query = VideoAula::ativas();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('descricao', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('modalidade') && $request->modalidade != '') {
            $query->where('modalidade', $request->modalidade);
        }

        if ($request->has('nivel') && $request->nivel != '') {
            $query->where('nivel', $request->nivel);
        }

        $videoAulas = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('video_aulas.index', compact('videoAulas'));
    }

    /**
     * Exibe o formulário para criar uma nova vídeo aula.
     */
    public function create()
    {
        return view('video_aulas.create');
    }

    /**
     * Armazena uma nova vídeo aula no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'url_youtube' => [
                'required',
                'string',
                'url',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('A URL do YouTube não é válida. Por favor, insira uma URL de vídeo do YouTube válida.');
                    }
                },
            ],
            'duracao_minutos' => 'nullable|integer|min:1|max:999',
            'nivel' => 'required|in:iniciante,intermediario,avancado,misto',
            'modalidade' => 'required|in:gi,no-gi,mma,defesa-pessoal',
        ], [
            'titulo.required' => 'O título da vídeo aula é obrigatório.',
            'url_youtube.required' => 'A URL do YouTube é obrigatória.',
            'url_youtube.url' => 'A URL do YouTube deve ser um formato de URL válido.',
            'duracao_minutos.min' => 'A duração deve ser de pelo menos 1 minuto.',
            'nivel.required' => 'Selecione o nível da vídeo aula.',
            'modalidade.required' => 'Selecione a modalidade da vídeo aula.',
        ]);

        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/', $request->url_youtube, $matches);
        $youtubeId = $matches[1] ?? null;

        if (!$youtubeId) {
            return redirect()->back()->withInput()->withErrors(['url_youtube' => 'Não foi possível extrair o ID do vídeo do YouTube da URL fornecida.']);
        }

        VideoAula::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'youtube_id' => $youtubeId,
            'duracao_minutos' => $request->duracao_minutos,
            'nivel' => $request->nivel,
            'modalidade' => $request->modalidade,
        ]);

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula cadastrada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma vídeo aula específica.
     */
    public function show(string $id)
    {
        $videoAula = VideoAula::ativas()->findOrFail($id);
        return view('video_aulas.show', compact('videoAula'));
    }

    /**
     * Exibe o formulário para editar uma vídeo aula.
     */
    public function edit(string $id)
    {
        $videoAula = VideoAula::ativas()->findOrFail($id);
        return view('video_aulas.edit', compact('videoAula'));
    }

    /**
     * Atualiza uma vídeo aula no banco de dados.
     */
    public function update(Request $request, string $id)
    {
        $videoAula = VideoAula::ativas()->findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'url_youtube' => [
                'required',
                'string',
                'url',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('A URL do YouTube não é válida. Por favor, insira uma URL de vídeo do YouTube válida.');
                    }
                },
            ],
            'duracao_minutos' => 'nullable|integer|min:1|max:999',
            'nivel' => 'required|in:iniciante,intermediario,avancado,misto',
            'modalidade' => 'required|in:gi,no-gi,mma,defesa-pessoal',
        ], [
            'titulo.required' => 'O título da vídeo aula é obrigatório.',
            'url_youtube.required' => 'A URL do YouTube é obrigatória.',
            'url_youtube.url' => 'A URL do YouTube deve ser um formato de URL válido.',
            'duracao_minutos.min' => 'A duração deve ser de pelo menos 1 minuto.',
            'nivel.required' => 'Selecione o nível da vídeo aula.',
            'modalidade.required' => 'Selecione a modalidade da vídeo aula.',
        ]);

        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?$/', $request->url_youtube, $matches);
        $youtubeId = $matches[1] ?? null;

        if (!$youtubeId) {
            return redirect()->back()->withInput()->withErrors(['url_youtube' => 'Não foi possível extrair o ID do vídeo do YouTube da URL fornecida.']);
        }

        $videoAula->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'youtube_id' => $youtubeId,
            'duracao_minutos' => $request->duracao_minutos,
            'nivel' => $request->nivel,
            'modalidade' => $request->modalidade,
        ]);

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula atualizada com sucesso!');
    }

    /**
     * Marca uma vídeo aula como 'excluida' (soft delete).
     */
    public function destroy(string $id)
    {
        $videoAula = VideoAula::findOrFail($id);
        $videoAula->excluido = 'S';
        $videoAula->save();

        return redirect()->route('video-aulas.index')->with('success', 'Vídeo aula movida para a lixeira com sucesso!');
    }

    /**
     * Exibe uma lista de vídeo aulas na lixeira (excluidas).
     */
    public function trash()
    {
        $videoAulas = VideoAula::excluidas()->paginate(12);
        return view('video_aulas.trash', compact('videoAulas'));
    }

    /**
     * Restaura uma vídeo aula da lixeira.
     */
    public function restore(string $id)
    {
        $videoAula = VideoAula::findOrFail($id);
        $videoAula->excluido = 'N';
        $videoAula->save();

        return redirect()->route('video-aulas.trash')->with('success', 'Vídeo aula restaurada com sucesso!');
    }
}
