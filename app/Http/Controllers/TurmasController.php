<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;
use App\Models\Aluno;
use Illuminate\Validation\Rule;

class TurmasController extends Controller {
    /**
     * Exibe uma lista de turmas ativas.
     */
    public function index() {
        $aTurmas = Turma::ativas()
                      ->with('alunos')
                      ->paginate(12);
        
        return view('turmas.index', compact('aTurmas'));
    }

    /**
     * Exibe o formulário para criar uma nova turma.
     */
    public function create() {
        $aProfessores = Aluno::ativas()
            ->where('tipo', 'professor')
            ->orderBy('nome')
            ->get();

        return view('turmas.cadastro', compact('aProfessores'));
    }

    /**
     * Armazena uma nova turma no banco de dados.
     */
    public function store(Request $oRequest) {
        $oRequest->validate([
              'nome'         => 'required|string|max:255'
            , 'modalidade'   => 'required|in:gi,no-gi,gracie,luta-livre,combate'
            , 'nivel'        => 'required|in:iniciante,intermediario,avancado,misto'
            , 'instrutor_id' => [
                  'required'
                , Rule::exists('alunos', 'id')->where(fn($oQuery) =>
                    $oQuery->where('tipo', 'professor')->where('excluido', 'N')
                )
            ]
            , 'dias_semana'       => 'required|array|min:1'
            , 'dias_semana.*'     => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo'
            , 'horario_inicio'    => 'required'
            , 'horario_fim'       => 'required|after:horario_inicio'
            , 'capacidade_maxima' => 'required|integer|min:1|max:50'
            , 'status'            => 'required|in:ativa,inativa,pausada'
            , 'observacoes'       => 'nullable|string|max:1000'
        ]);

        $aProfessor = Aluno::findOrFail($oRequest->instrutor_id);

        $aDados = $oRequest->all();
        $aDados['instrutor'] = $aProfessor->nome;

        Turma::create($aDados);

        return redirect()->route('turmas.index')->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma turma específica.
     */
    public function show(string $iCodigo) {
        $aTurmas = Turma::ativas()->with('alunos')->findOrFail($iCodigo);
        return view('turmas.detalhe', compact('aTurmas'));
    }

    /**
     * Exibe o formulário para editar uma turma.
     */
    public function edit(string $iCodigo) {
        $aTurmas = Turma::ativas()->findOrFail($iCodigo);

        $aProfessores = Aluno::ativas()
            ->where('tipo', 'professor')
            ->orderBy('nome')
            ->get();

        return view('turmas.alterar', compact('aTurmas', 'aProfessores'));
    }

    /**
     * Atualiza uma turma no banco de dados.
     */
    public function update(Request $oRequest, string $iCodigo) {
        $aTurma = Turma::ativas()->findOrFail($iCodigo);

        $oRequest->validate([
              'nome'         => 'required|string|max:255'
            , 'modalidade'   => 'required|in:gi,no-gi,gracie,luta-livre,combate'
            , 'nivel'        => 'required|in:iniciante,intermediario,avancado,misto'
            , 'instrutor_id' => [
                  'required'
                , Rule::exists('alunos', 'id')->where(fn($oQuery) =>
                    $oQuery->where('tipo', 'professor')->where('excluido', 'N')
                )
            ]
            , 'dias_semana'       => 'required|array|min:1'
            , 'dias_semana.*'     => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo'
            , 'horario_inicio'    => 'required'
            , 'horario_fim'       => 'required|after:horario_inicio'
            , 'capacidade_maxima' => 'required|integer|min:1|max:50'
            , 'status'            => 'required|in:ativa,inativa,pausada'
            , 'observacoes'       => 'nullable|string|max:1000'
        ]);

        $aProfessor = Aluno::findOrFail($oRequest->instrutor_id);

        $aDados = $oRequest->all();
        $aDados['instrutor'] = $aProfessor->nome; 

        $aTurma->update($aDados);

        return redirect()->route('turmas.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Marca uma turma como 'excluida'
     */
    public function destroy(string $iCodigo) {
        $aTurmas = Turma::findOrFail($iCodigo);
        $aTurmas->excluido = 'S';
        $aTurmas->save();

        return redirect()->route('turmas.index')->with('success', 'Turma removida com sucesso!');
    }

    /**
     * Exibe a página de gestão de alunos de uma turma.
     */
    public function alunos(string $iCodigo) {
        $aTurmas = Turma::ativas()->with('alunos')->findOrFail($iCodigo);
        $aAlunosDisponiveis = Aluno::ativas()
                                  ->whereDoesntHave('turmas', function ($rQuery) use ($iCodigo) {
                                      $rQuery->where('turma_alunos.turma_id', $iCodigo);
                                  })
                                  ->get();
        
        return view('turmas.alunos', compact('aTurmas', 'aAlunosDisponiveis'));
    }

    /**
     * Matricula um aluno em uma turma.
     */
    public function matricularAluno(Request $oRequest, string $iCodigo) {
        $aTurmas = Turma::ativas()->findOrFail($iCodigo);
        
        $oRequest->validate([
            'aluno_id' => 'required|exists:alunos,id'
        ]);

        if ($aTurmas->numero_alunos >= $aTurmas->capacidade_maxima) {
            return redirect()->back()->with('error', 'Turma já atingiu a capacidade máxima!');
        }

        if ($aTurmas->alunos()->where('aluno_id', $oRequest->aluno_id)->exists()) {
            return redirect()->back()->with('error', 'Aluno já está matriculado nesta turma!');
        }

        $aTurmas->alunos()->attach($oRequest->aluno_id, [
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo'
        ]);

        return redirect()->back()->with('success', 'Aluno matriculado com sucesso!');
    }

    /**
     * Remove um aluno de uma turma.
     */
    public function removerAluno(string $iTurmaId, string $iAlunoId) {
        $aTurmas = Turma::ativas()->findOrFail($iTurmaId);
        
        $aTurmas->alunos()->updateExistingPivot($iAlunoId, [
              'data_saida' => now()->toDateString()
            , 'status'     => 'inativo'
        ]);

        return redirect()->back()->with('success', 'Aluno removido da turma com sucesso!');
    }
}