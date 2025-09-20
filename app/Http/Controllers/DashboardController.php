<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $hoje             = Carbon::now();
        $dataLimiteSemana = $hoje->copy()->subDays(7);

        $nomesSemana   = ['domingo','segunda','terca','quarta','quinta','sexta','sabado'];
        $diaSemanaNome = $nomesSemana[$hoje->dayOfWeek];
        $alunosAtivos = Aluno::ativas()
            ->where('status', 'ativo')
            ->count();

        $proximasAulasHoje = Turma::ativas()
            ->where('status', 'ativa')
            ->whereJsonContains('dias_semana', $diaSemanaNome) 
            ->count();

        $novasMatriculasSemana = Aluno::ativas()
            ->whereDate('data_matricula', '>=', $dataLimiteSemana->toDateString())
            ->count();

        $anoAtual = $hoje->year;

        $evolucao = Aluno::ativas()
            ->selectRaw('MONTH(data_matricula) as mes, COUNT(*) as total')
            ->whereYear('data_matricula', $anoAtual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $evolucaoMeses = array_fill(1, 12, 0);
        foreach ($evolucao as $row) {
            $evolucaoMeses[(int) $row->mes] = (int) $row->total;
        }

        $labelsMeses     = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $datasetEvolucao = array_values($evolucaoMeses);

        $ultimasDatas = DB::table('graduacoes as g')
            ->select('g.aluno_id', DB::raw('MAX(g.data_graduacao) as max_data'))
            ->groupBy('g.aluno_id');

        $faixaAtualPorGraduacao = DB::table('graduacoes as gu')
            ->joinSub($ultimasDatas, 'm', function ($join) {
                $join->on('gu.aluno_id', '=', 'm.aluno_id')
                     ->on('gu.data_graduacao', '=', 'm.max_data');
            })
            ->select('gu.aluno_id', 'gu.faixa_nova_id');

        $distFaixas = DB::table('alunos as a')
            ->where('a.excluido', 'N')
            ->where('a.status', 'ativo')
            ->leftJoinSub($faixaAtualPorGraduacao, 'ga', 'ga.aluno_id', '=', 'a.id')
            ->leftJoin('faixas as fn', function ($join) {
                $join->on(DB::raw('LOWER(fn.nome)'), '=', DB::raw('LOWER(a.faixa)'));
            })
            ->selectRaw('COALESCE(ga.faixa_nova_id, a.faixa_inicial_id, fn.id) AS faixa_id, COUNT(*) AS total')
            ->groupBy('faixa_id')
            ->get();

        $faixas = DB::table('faixas')
            ->orderBy('ordem')
            ->get(['id','nome']);

        $labelsFaixas = [];
        $dataFaixas   = [];
        foreach ($faixas as $f) {
            $labelsFaixas[] = $f->nome;
            $match = $distFaixas->firstWhere('faixa_id', $f->id);
            $dataFaixas[] = $match ? (int) $match->total : 0;
        }

        $recentesAlunos = Aluno::ativas()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($a) {
                return [
                    'tipo'      => 'aluno',
                    'iniciais'  => $this->iniciais($a->nome),
                    'titulo'    => $a->nome,
                    'descricao' => 'novo cadastro de aluno.',
                    'quando'    => $a->created_at,
                ];
            });

        $recentesTurmas = Turma::ativas()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($t) {
                return [
                      'tipo'      => 'turma'
                    , 'iniciais'  => $this->iniciais($t->nome)
                    , 'titulo'    => 'Nova turma'
                    , 'descricao' => $t->nome . ' adicionada.'
                    , 'quando'    => $t->created_at
                ];
            });

        $atividades = $recentesAlunos
            ->merge($recentesTurmas)
            ->sortByDesc('quando')
            ->take(10)
            ->values();

        return view('dashboard', [
              'alunosAtivos'           => $alunosAtivos
            , 'proximasAulasHoje'      => $proximasAulasHoje
            , 'novasMatriculasSemana'  => $novasMatriculasSemana
            , 'labelsMeses'            => $labelsMeses
            , 'datasetEvolucao'        => $datasetEvolucao
            , 'labelsFaixas'           => $labelsFaixas
            , 'dataFaixas'             => $dataFaixas 
            , 'atividades'             => $atividades
        ]);
    }

    private function iniciais(string $nome) {
        $partes   = preg_split('/\s+/', trim($nome));
        $iniciais = mb_strtoupper(mb_substr($partes[0] ?? '', 0, 1));
        if (count($partes) > 1) {
            $iniciais .= mb_strtoupper(mb_substr(end($partes), 0, 1));
        }
        return $iniciais ?: '??';
    }
}
