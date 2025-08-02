<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;
use App\Models\Aluno;

class TurmasController extends Controller
{
    /**
     * Exibe uma lista de turmas ativas.
     */
    public function index()
    {
        $turmas = Turma::ativas()
                      ->with('alunos')
                      ->paginate(12);
        
        return view('turmas.index', compact('turmas'));
    }

    /**
     * Exibe o formulário para criar uma nova turma.
     */
    public function create()
    {
        return view('turmas.create');
    }

    /**
     * Armazena uma nova turma no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'modalidade' => 'required|in:gi,no-gi,mma,defesa-pessoal',
            'nivel' => 'required|in:iniciante,intermediario,avancado,misto',
            'instrutor' => 'required|string|max:255',
            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'horario_inicio' => 'required',
            'horario_fim' => 'required|after:horario_inicio',
            'capacidade_maxima' => 'required|integer|min:1|max:50',
            'status' => 'required|in:ativa,inativa,pausada',
            'observacoes' => 'nullable|string|max:1000',
        ], [
            'nome.required' => 'O nome da turma é obrigatório.',
            'modalidade.required' => 'Selecione uma modalidade.',
            'nivel.required' => 'Selecione o nível da turma.',
            'instrutor.required' => 'O nome do instrutor é obrigatório.',
            'dias_semana.required' => 'Selecione pelo menos um dia da semana.',
            'dias_semana.min' => 'Selecione pelo menos um dia da semana.',
            'horario_inicio.required' => 'O horário de início é obrigatório.',
            'horario_fim.required' => 'O horário de fim é obrigatório.',
            'horario_fim.after' => 'O horário de fim deve ser posterior ao horário de início.',
            'capacidade_maxima.required' => 'A capacidade máxima é obrigatória.',
            'capacidade_maxima.min' => 'A capacidade deve ser de pelo menos 1 aluno.',
            'capacidade_maxima.max' => 'A capacidade máxima é de 50 alunos.',
            'status.required' => 'Selecione o status da turma.',
        ]);

        Turma::create($request->all());

        return redirect()->route('turmas.index')->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma turma específica.
     */
    public function show(string $id)
    {
        $turma = Turma::ativas()->with('alunos')->findOrFail($id);
        return view('turmas.show', compact('turma'));
    }

    /**
     * Exibe o formulário para editar uma turma.
     */
    public function edit(string $id)
    {
        $turma = Turma::ativas()->findOrFail($id);
        return view('turmas.edit', compact('turma'));
    }

    /**
     * Atualiza uma turma no banco de dados.
     */
    public function update(Request $request, string $id)
    {
        $turma = Turma::ativas()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'modalidade' => 'required|in:gi,no-gi,mma,defesa-pessoal',
            'nivel' => 'required|in:iniciante,intermediario,avancado,misto',
            'instrutor' => 'required|string|max:255',
            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'horario_inicio' => 'required',
            'horario_fim' => 'required|after:horario_inicio',
            'capacidade_maxima' => 'required|integer|min:1|max:50',
            'status' => 'required|in:ativa,inativa,pausada',
            'observacoes' => 'nullable|string|max:1000',
        ], [
            'nome.required' => 'O nome da turma é obrigatório.',
            'modalidade.required' => 'Selecione uma modalidade.',
            'nivel.required' => 'Selecione o nível da turma.',
            'instrutor.required' => 'O nome do instrutor é obrigatório.',
            'dias_semana.required' => 'Selecione pelo menos um dia da semana.',
            'dias_semana.min' => 'Selecione pelo menos um dia da semana.',
            'horario_inicio.required' => 'O horário de início é obrigatório.',
            'horario_fim.required' => 'O horário de fim é obrigatório.',
            'horario_fim.after' => 'O horário de fim deve ser posterior ao horário de início.',
            'capacidade_maxima.required' => 'A capacidade máxima é obrigatória.',
            'capacidade_maxima.min' => 'A capacidade deve ser de pelo menos 1 aluno.',
            'capacidade_maxima.max' => 'A capacidade máxima é de 50 alunos.',
            'status.required' => 'Selecione o status da turma.',
        ]);

        $turma->update($request->all());

        return redirect()->route('turmas.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Marca uma turma como 'excluida'
     */
    public function destroy(string $id)
    {
        $turma = Turma::findOrFail($id);
        $turma->excluido = 'S';
        $turma->save();

        return redirect()->route('turmas.index')->with('success', 'Turma movida para a lixeira com sucesso!');
    }

    /**
     * Exibe uma lista de turmas na lixeira
     */
    public function trash()
    {
        $turmas = Turma::excluidas()->with('alunos')->paginate(12);
        return view('turmas.trash', compact('turmas'));
    }

    /**
     * Restaura uma turma da lixeira.
     */
    public function restore(string $id)
    {
        $turma = Turma::findOrFail($id);
        $turma->excluido = 'N';
        $turma->save();

        return redirect()->route('turmas.trash')->with('success', 'Turma restaurada com sucesso!');
    }

    /**
     * Exibe a página de gestão de alunos de uma turma.
     */
    public function alunos(string $id)
    {
        $turma = Turma::ativas()->with('alunos')->findOrFail($id);
        $alunosDisponiveis = Aluno::ativas()
                                  ->whereDoesntHave('turmas', function ($query) use ($id) {
                                      $query->where('turma_alunos.turma_id', $id);
                                  })
                                  ->get();
        
        return view('turmas.alunos', compact('turma', 'alunosDisponiveis'));
    }

    /**
     * Matricula um aluno em uma turma.
     */
    public function matricularAluno(Request $request, string $id)
    {
        $turma = Turma::ativas()->findOrFail($id);
        
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
        ]);

        if ($turma->numero_alunos >= $turma->capacidade_maxima) {
            return redirect()->back()->with('error', 'Turma já atingiu a capacidade máxima!');
        }

        if ($turma->alunos()->where('aluno_id', $request->aluno_id)->exists()) {
            return redirect()->back()->with('error', 'Aluno já está matriculado nesta turma!');
        }

        $turma->alunos()->attach($request->aluno_id, [
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo'
        ]);

        return redirect()->back()->with('success', 'Aluno matriculado com sucesso!');
    }

    /**
     * Remove um aluno de uma turma.
     */
    public function removerAluno(string $turmaId, string $alunoId)
    {
        $turma = Turma::ativas()->findOrFail($turmaId);
        
        $turma->alunos()->updateExistingPivot($alunoId, [
            'data_saida' => now()->toDateString(),
            'status' => 'inativo'
        ]);

        return redirect()->back()->with('success', 'Aluno removido da turma com sucesso!');
    }
}
