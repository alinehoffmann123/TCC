<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Faixa;

class AlunosController extends Controller {

    /**
     * Exibe a lista de alunos e professores ativos.
     * Carrega dados relacionados: turmas, faixa inicial e última graduação.
     * Faz paginação separada para alunos e professores.
     */
    public function index(Request $oRequest) {
        $oPessoa = $oRequest->get('tab', 'alunos');

        $aAlunos = Aluno::ativas()
            ->alunos()
            ->with(['turmas', 'faixaInicial', 'ultimaGraduacao.faixaNova'])
            ->paginate(10, ['*'], 'alunos_page');

        $aProfessores = Aluno::ativas()
            ->professores()
            ->with(['turmas', 'faixaInicial', 'ultimaGraduacao.faixaNova'])
            ->paginate(10, ['*'], 'professores_page');

        return view('alunos.index', compact('aAlunos', 'aProfessores', 'oPessoa'));
    }

    /**
     * Exibe o formulário para cadastro de novo aluno ou professor.
     * Carrega as turmas ativas e faixas disponíveis para seleção.
     */
    public function create() {
        $aTurmas = Turma::ativas()->orderBy('nome')->get();
        $aFaixas = Faixa::orderBy('ordem')->get(['id','nome','ordem']);
        return view('alunos.cadastro', compact('aTurmas','aFaixas'));
    }

    /**
     * Realiza o cadastro de um novo aluno ou professor.
     * Valida os dados, cria o registro, associa turmas e faixa inicial.
     */
    public function store(Request $oRequest) {
        $bTemFaixaInicial = Schema::hasColumn('alunos', 'faixa_inicial_id');

        $oRequest->validate([
              'tipo'            => ['required', Rule::in(['aluno','professor'])]
            , 'nome'            => 'required|string|max:255'
            , 'email'           => 'required|string|email|max:255|unique:alunos'
            , 'data_nascimento' => 'nullable|date'
            , 'telefone'        => 'nullable|string|max:20'
            , 'faixa_inicial_id'=> [$bTemFaixaInicial ? 'nullable' : 'nullable', 'exists:faixas,id', 'required_if:tipo,aluno']
            , 'faixa'           => ['nullable', Rule::in(['branca','azul','roxa','marrom','preta'])]
            , 'status'          => ['required', Rule::in(['ativo','inativo','trancado'])]
            , 'data_matricula'  => 'nullable|date|required_if:tipo,aluno'
            , 'turmas'          => 'nullable|array'
            , 'turmas.*'        => 'exists:turmas,id'
        ], [
              'tipo.required'               => 'Selecione se é aluno ou professor.'
            , 'faixa_inicial_id.required_if'=> 'A faixa é obrigatória para Aluno.'
            , 'data_matricula.required_if'  => 'A data de matrícula é obrigatória para Aluno.'
        ]);

        $aDados = [
              'tipo'            => $oRequest->tipo
            , 'nome'            => $oRequest->nome
            , 'email'           => $oRequest->email
            , 'data_nascimento' => $oRequest->data_nascimento
            , 'telefone'        => $oRequest->telefone
            , 'status'          => $oRequest->status
            , 'data_matricula'  => $oRequest->data_matricula
        ];

        $oFaixaModel = null;

        if ($bTemFaixaInicial && $oRequest->filled('faixa_inicial_id')) {
            $oFaixaModel = Faixa::find($oRequest->faixa_inicial_id);
            $aDados['faixa_inicial_id'] = $oFaixaModel?->id;
        }

        if (!$oFaixaModel && $oRequest->filled('faixa')) {
            $oFaixaModel = Faixa::whereRaw('LOWER(nome) = ?', [mb_strtolower($oRequest->faixa)])->first();
        }

        $aDados['faixa'] = $oFaixaModel
            ? mb_strtolower($oFaixaModel->nome)
            : ($oRequest->faixa ?: null);

        $aPessoa = Aluno::create($aDados);

        if ($oRequest->filled('turmas')) {
            foreach ($oRequest->turmas as $iCodigoTurma) {
                $aTurma = Turma::find($iCodigoTurma);
                if (!$aTurma) continue;

                $bCapacidade = $oRequest->tipo === 'professor'
                    ? true
                    : ($aTurma->numero_alunos < $aTurma->capacidade_maxima);

                if ($bCapacidade) {
                    $aPessoa->turmas()->attach($iCodigoTurma, [
                          'data_matricula' => $oRequest->data_matricula ?? now()->toDateString()
                        , 'status'         => 'ativo'
                        , 'papel'          => $oRequest->tipo
                    ]);
                }
            }
        }

        return redirect()->route('alunos.index')->with('success', 'Cadastro realizado com sucesso!');
    }

    /**
     * Atualiza os dados de um aluno ou professor existente.
     * Valida os dados, atualiza o registro, limpa turmas anteriores e associa as novas.
     */
    public function update(Request $oRequest, string $iCodigo) {
        $aPessoa = Aluno::ativas()->findOrFail($iCodigo);
        $bTemFaixaInicial = Schema::hasColumn('alunos', 'faixa_inicial_id');

        $oRequest->validate([
              'tipo'            => ['required', Rule::in(['aluno','professor'])]
            , 'nome'            => 'required|string|max:255'
            , 'email'           => 'required|string|email|max:255|unique:alunos,email,' . $iCodigo
            , 'data_nascimento' => 'nullable|date'
            , 'telefone'        => 'nullable|string|max:20'
            , 'faixa_inicial_id'=> [$bTemFaixaInicial ? 'nullable' : 'nullable', 'exists:faixas,id', 'required_if:tipo,aluno']
            , 'faixa'           => ['nullable', Rule::in(['branca','azul','roxa','marrom','preta'])]
            , 'status'          => ['required', Rule::in(['ativo','inativo','trancado'])]
            , 'data_matricula'  => 'nullable|date|required_if:tipo,aluno'
            , 'turmas'          => 'nullable|array'
            , 'turmas.*'        => 'exists:turmas,id'
        ], [
              'faixa_inicial_id.required_if' => 'A faixa é obrigatória para Aluno.'
            , 'data_matricula.required_if'   => 'A data de matrícula é obrigatória para Aluno.'
        ]);

        $aDados = [
              'tipo'            => $oRequest->tipo
            , 'nome'            => $oRequest->nome
            , 'email'           => $oRequest->email
            , 'data_nascimento' => $oRequest->data_nascimento
            , 'telefone'        => $oRequest->telefone
            , 'status'          => $oRequest->status
            , 'data_matricula'  => $oRequest->data_matricula
        ];

        $oFaixaModel = null;

        if ($bTemFaixaInicial && $oRequest->filled('faixa_inicial_id')) {
            $oFaixaModel = Faixa::find($oRequest->faixa_inicial_id);
            $aDados['faixa_inicial_id'] = $oFaixaModel?->id;
        }

        if (!$oFaixaModel && $oRequest->filled('faixa')) {
            $oFaixaModel = Faixa::whereRaw('LOWER(nome) = ?', [mb_strtolower($oRequest->faixa)])->first();
        }

        $aDados['faixa'] = $oFaixaModel
            ? mb_strtolower($oFaixaModel->nome)
            : ($oRequest->faixa ?: $aPessoa->faixa);

        $aPessoa->update($aDados);
        $aPessoa->todasTurmas()->detach();
        if ($oRequest->filled('turmas')) {
            foreach ($oRequest->turmas as $iCodigoTurma) {
                $aTurma = Turma::find($iCodigoTurma);
                if (!$aTurma) continue;

                $bCapacidade = $oRequest->tipo === 'professor'
                    ? true
                    : ($aTurma->numero_alunos < $aTurma->capacidade_maxima);

                if ($bCapacidade) {
                    $aPessoa->turmas()->attach($iCodigoTurma, [
                          'data_matricula' => $oRequest->data_matricula ?? now()->toDateString()
                       ,  'status'         => 'ativo'
                       ,  'papel'          => $oRequest->tipo
                    ]);
                }
            }
        }

        return redirect()->route('alunos.index')->with('success', 'Cadastro atualizado com sucesso!');
    }

    /**
     * Exibe os detalhes de um aluno ativo, incluindo turmas associadas.
     */
    public function show(string $iCodigo) {
        $aAluno = Aluno::ativas()->with('turmas')->findOrFail($iCodigo);
        return view('alunos.detalhe', compact('aAluno'));
    }

    /**
     * Exibe o formulário para edição de um aluno ativo.
     * Carrega turmas ativas e faixas disponíveis.
     */
    public function edit(string $iCodigo) {
        $aAluno  = Aluno::ativas()->with('turmas')->findOrFail($iCodigo);
        $aTurmas = Turma::ativas()->orderBy('nome')->get();
        $aFaixas = Faixa::orderBy('ordem')->get(['id','nome','ordem']); 
        return view('alunos.alterar', compact('aAluno', 'aTurmas','aFaixas'));
    }

    /**
     * Marca um aluno como excluído (soft delete) alterando o campo 'excluido' para 'S'.
     */
    public function destroy(string $iCodigo) {
        $aAluno = Aluno::findOrFail($iCodigo);
        $aAluno->excluido = 'S';
        $aAluno->save();

        return redirect()->route('alunos.index')->with('success', 'Aluno removido com sucesso!');
    }
}

