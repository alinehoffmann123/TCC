<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Turma;

class AlunosController extends Controller
{
    /**
     * Exibe uma lista de alunos.
     */
    public function index()
    {
        $alunos = Aluno::ativas()
                      ->with('turmas')
                      ->paginate(10);
        
        return view('alunos.index', compact('alunos'));
    }

    /**
     * Exibe o formulário para criar um novo aluno.
     */
    public function create()
    {
        $turmas = Turma::ativas()
                      ->orderBy('nome')
                      ->get();
        
        return view('alunos.create', compact('turmas'));
    }

    /**
     * Armazena um novo aluno no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alunos',
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'faixa' => 'required|string|in:branca,azul,roxa,marrom,preta',
            'status' => 'required|string|in:ativo,inativo,trancado',
            'data_matricula' => 'required|date',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está cadastrado.',
            'faixa.required' => 'Selecione uma faixa.',
            'status.required' => 'Selecione um status.',
            'data_matricula.required' => 'A data de matrícula é obrigatória.',
            'turmas.*.exists' => 'Uma ou mais turmas selecionadas não existem.',
        ]);

        $aluno = Aluno::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'faixa' => $request->faixa,
            'status' => $request->status,
            'data_matricula' => $request->data_matricula,
        ]);

        if ($request->has('turmas') && !empty($request->turmas)) {
            foreach ($request->turmas as $turmaId) {
                $turma = Turma::find($turmaId);
                
                if ($turma && $turma->numero_alunos < $turma->capacidade_maxima) {
                    $aluno->turmas()->attach($turmaId, [
                        'data_matricula' => $request->data_matricula,
                        'status' => 'ativo'
                    ]);
                }
            }
        }

        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um aluno específico.
     */
    public function show(string $id)
    {
        $aluno = Aluno::ativas()->with('turmas')->findOrFail($id);
        return view('alunos.show', compact('aluno'));
    }

    /**
     * Exibe o formulário para editar um aluno.
     */
    public function edit(string $id)
    {
        $aluno = Aluno::ativas()->with('turmas')->findOrFail($id);
        
        $turmas = Turma::ativas()
                  ->orderBy('nome')
                  ->get();
        
        return view('alunos.edit', compact('aluno', 'turmas'));
    }

    /**
     * Atualiza um aluno no banco de dados.
     */
    public function update(Request $request, string $id)
    {
        $aluno = Aluno::ativas()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alunos,email,' . $id,
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'faixa' => 'required|string|in:branca,azul,roxa,marrom,preta',
            'status' => 'required|string|in:ativo,inativo,trancado',
            'data_matricula' => 'required|date',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ]);

        $aluno->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'faixa' => $request->faixa,
            'status' => $request->status,
            'data_matricula' => $request->data_matricula,
        ]);

        if ($request->has('turmas')) {
            $aluno->turmas()->detach();
            
            foreach ($request->turmas as $turmaId) {
                $turma = Turma::find($turmaId);
                
                if ($turma && $turma->numero_alunos < $turma->capacidade_maxima) {
                    $aluno->turmas()->attach($turmaId, [
                        'data_matricula' => $request->data_matricula,
                        'status' => 'ativo'
                    ]);
                }
            }
        } else {
            $aluno->turmas()->detach();
        }

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * Marca um aluno como 'excluido' (soft delete).
     */
    public function destroy(string $id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->excluido = 'S';
        $aluno->save();

        return redirect()->route('alunos.index')->with('success', 'Aluno movido para a lixeira com sucesso!');
    }

    /**
     * Exibe uma lista de alunos na lixeira (excluidos).
     */
    public function trash()
    {
        $alunos = Aluno::excluidos()->with('turmas')->paginate(10);
        return view('alunos.trash', compact('alunos'));
    }

    /**
     * Restaura um aluno da lixeira.
     */
    public function restore(string $id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->excluido = 'N';
        $aluno->save();

        return redirect()->route('alunos.trash')->with('success', 'Aluno restaurado com sucesso!');
    }
}
