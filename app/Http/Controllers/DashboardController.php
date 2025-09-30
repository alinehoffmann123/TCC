<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {

    /**
     * Exibe o painel principal do sistema (dashboard).
     *
     * Essa função monta os dados que alimentam os cards, gráficos e lista de atividades recentes
     * no dashboard, incluindo:
     * - Quantidade de alunos ativos
     * - Próximas aulas na semana
     * - Novas matrículas nos últimos 7 dias
     * - Evolução de matrículas no ano (gráfico mensal)
     * - Distribuição de faixas dos alunos
     * - Atividades recentes (alunos e turmas criados recentemente)
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $sHoje             = Carbon::now();
        $sDataLimiteSemana = $sHoje->copy()->subDays(7);

        $aDiasDaSemana   = ['domingo','segunda','terca','quarta','quinta','sexta','sabado'];
        $sNomeDiasSemana = $aDiasDaSemana[$sHoje->dayOfWeek];
        $iAlunosAtivos = Aluno::ativas()
            ->where('status', 'ativo')
            ->count();

        $iProximasAulas = Turma::ativas()
            ->where('status', 'ativa')
            ->whereJsonContains('dias_semana', $sNomeDiasSemana) 
            ->count();

        $iNovasMatriculasSemana = Aluno::ativas()
            ->whereDate('data_matricula', '>=', $sDataLimiteSemana->toDateString())
            ->count();

        $iAnoAtual = $sHoje->year;

        $aEvolucao = Aluno::ativas()
            ->selectRaw('MONTH(data_matricula) as mes, COUNT(*) as total')
            ->whereYear('data_matricula', $iAnoAtual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $aEvolucaoMeses = array_fill(1, 12, 0);
        foreach ($aEvolucao as $row) {
            $aEvolucaoMeses[(int) $row->mes] = (int) $row->total;
        }

        $aLabelMeses   = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $aDataEvolucao = array_values($aEvolucaoMeses);

        $sUltimasDatas = DB::table('graduacoes as g')
            ->select('g.aluno_id', DB::raw('MAX(g.data_graduacao) as max_data'))
            ->groupBy('g.aluno_id');

        $sFaixaAtualPorGraducao = DB::table('graduacoes as gu')
            ->joinSub($sUltimasDatas, 'm', function ($oJoin) {
                $oJoin->on('gu.aluno_id', '=', 'm.aluno_id')
                     ->on('gu.data_graduacao', '=', 'm.max_data');
            })
            ->select('gu.aluno_id', 'gu.faixa_nova_id');

        $sDistFaixa = DB::table('alunos as a')
            ->where('a.excluido', 'N')
            ->where('a.status', 'ativo')
            ->leftJoinSub($sFaixaAtualPorGraducao, 'ga', 'ga.aluno_id', '=', 'a.id')
            ->leftJoin('faixas as fn', function ($oJoin) {
                $oJoin->on(DB::raw('LOWER(fn.nome)'), '=', DB::raw('LOWER(a.faixa)'));
            })
            ->selectRaw('COALESCE(ga.faixa_nova_id, a.faixa_inicial_id, fn.id) AS faixa_id, COUNT(*) AS total')
            ->groupBy('faixa_id')
            ->get();

        $aFaixas = DB::table('faixas')
            ->orderBy('ordem')
            ->get(['id','nome']);

        $aLabelsFaixa = [];
        $aDataFaixas   = [];
        foreach ($aFaixas as $aFaixa) {
            $aLabelsFaixa[] = $aFaixa->nome;
            $sMatch = $sDistFaixa->firstWhere('faixa_id', $aFaixa->id);
            $aDataFaixas[] = $sMatch ? (int) $sMatch->total : 0;
        }

        $aAlunosRecentes = Aluno::ativas()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($aAluno) {
                return [
                    'tipo'      => 'aluno',
                    'iniciais'  => $this->iniciais($aAluno->nome),
                    'titulo'    => $aAluno->nome,
                    'descricao' => 'novo cadastro de aluno.',
                    'quando'    => $aAluno->created_at,
                ];
            });

        $aTurmasRecentes = Turma::ativas()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($aTurma) {
                return [
                      'tipo'      => 'turma'
                    , 'iniciais'  => $this->iniciais($aTurma->nome)
                    , 'titulo'    => 'Nova turma'
                    , 'descricao' => $aTurma->nome . ' adicionada.'
                    , 'quando'    => $aTurma->created_at
                ];
            });

        $aAtividadesRecentes = $aAlunosRecentes
            ->merge($aTurmasRecentes)
            ->sortByDesc('quando')
            ->take(5)
            ->values();

        return view('dashboard', [
              'iAlunosAtivos'          => $iAlunosAtivos
            , 'iProximasAulas'         => $iProximasAulas
            , 'iNovasMatriculasSemana' => $iNovasMatriculasSemana
            , 'aLabelMeses'            => $aLabelMeses
            , 'aDataEvolucao'          => $aDataEvolucao
            , 'aLabelsFaixa'           => $aLabelsFaixa
            , 'aDataFaixas'            => $aDataFaixas 
            , 'aAtividadesRecentes'    => $aAtividadesRecentes
        ]);
    }

    /**
     * Gera as iniciais de um nome.
     *
     * Exemplo:
     * - "Maria Silva" => "MS"
     * - "João" => "J"
     *
     * @param string $sNome Nome completo da pessoa
     * @return string Iniciais em maiúsculo, ou "??" caso não seja possível gerar
     */
    private function iniciais(string $sNome) {
        $iPartes   = preg_split('/\s+/', trim($sNome));
        $sIniciais = mb_strtoupper(mb_substr($iPartes[0] ?? '', 0, 1));
        if (count($iPartes) > 1) {
            $sIniciais .= mb_strtoupper(mb_substr(end($iPartes), 0, 1));
        }
        return $sIniciais ?: '??';
    }
}
