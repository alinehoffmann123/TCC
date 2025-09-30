<?php
namespace App\Http\Controllers;

use App\Models\Faixa;
use App\Models\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller {

    /**
     * Exibe a lista de graduações de um aluno.
     * Carrega os relacionamentos de faixa anterior e faixa nova.
     * Ordena da mais recente para a mais antiga.
     *
     * @param int $iCodigoAluno - ID do aluno cujas graduações serão listadas
     */
    public function index($iCodigoAluno) {
        $aGraduacoes = Graduacao::where('aluno_id',$iCodigoAluno)
            ->with(['faixaAnterior','faixaNova'])
            ->orderByDesc('data_graduacao')
            ->get();

        return view('graduacoes.index', compact('aGraduacoes','iCodigoAluno'));
    }

    /**
     * Exibe o formulário para registrar uma nova graduação para um aluno.
     * Carrega todas as faixas e a última graduação existente.
     *
     * @param int $iCodigoAluno - ID do aluno que receberá a graduação
     */
    public function create($iCodigoAluno) {
        $aFaixas = Faixa::orderBy('ordem')->get();
        $aUltima = Graduacao::where('aluno_id',$iCodigoAluno)->latest('data_graduacao')->first();

        return view('graduacoes.cadastro', compact('iCodigoAluno','aFaixas','aUltima'));
    }

    /**
     * Registra uma nova graduação para o aluno.
     * Valida os dados recebidos, associa a faixa anterior automaticamente
     * com base na última graduação, e cria o registro no banco.
     *
     * @param Request $oRequest - Dados enviados pelo formulário
     * @param int $iCodigoAluno - ID do aluno que receberá a graduação
     */
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