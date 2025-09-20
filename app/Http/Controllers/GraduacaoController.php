<?php

namespace App\Http\Controllers;

use App\Models\Faixa;
use App\Models\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller {
    public function index($iCodigoAluno) {
        $aGraduacao = Graduacao::where('aluno_id',$iCodigoAluno)
            ->with(['faixaAnterior','faixaNova'])
            ->orderByDesc('data_graduacao')
            ->get();

        return view('graduacoes.index', compact('aGraduacao','iCodigoAluno'));
    }

    public function create($iCodigoAluno) {
        $aFaixas = Faixa::orderBy('ordem')->get();
        $aUltima = Graduacao::where('aluno_id',$iCodigoAluno)->latest('data_graduacao')->first();

        return view('graduacoes.cadastro', compact('iCodigoAluno','aFaixas','aUltima'));
    }

    public function store(Request $oRequest, $iCodigoAluno) {
        $aDados = $oRequest->validate([
              'faixa_nova_id'  => 'required|exists:faixas,id'
            , 'data_graduacao' => 'required|date'
            , 'instrutor_nome' => 'nullable|string|max:255'
            , 'observacoes'    => 'nullable|string'
        ]);

        $aUltima = Graduacao::where('aluno_id',$iCodigoAluno)->latest('data_graduacao')->first();

        Graduacao::create([
              'aluno_id'          => $iCodigoAluno
            , 'faixa_anterior_id' => $aUltima?->faixa_nova_id
            , 'faixa_nova_id'     => $aDados['faixa_nova_id']
            , 'data_graduacao'    => $aDados['data_graduacao']
            , 'instrutor_nome'    => $aDados['instrutor_nome'] ?? null
            , 'observacoes'       => $aDados['observacoes'] ?? null
        ]);

        return redirect()->route('graduacoes.index', $iCodigoAluno)->with('success','Graduação registrada!');
    }
}