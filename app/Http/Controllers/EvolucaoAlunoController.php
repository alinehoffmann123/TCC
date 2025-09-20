<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Faixa;
use App\Models\Graduacao;
use App\Models\Grau;
use App\Models\Audit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvolucaoAlunoController extends Controller
{
    /** Resolve a faixa inicial a partir do cadastro (FK, relação ou string). */
    private function resolveFaixaInicial(Aluno $aluno): ?Faixa {
        if (method_exists($aluno, 'faixaInicial') && $aluno->relationLoaded('faixaInicial')) {
            if ($aluno->faixaInicial) return $aluno->faixaInicial;
        } elseif (method_exists($aluno, 'faixaInicial')) {
            $rel = $aluno->faixaInicial()->first();
            if ($rel) return $rel;
        }

        if (!empty($aluno->faixa_inicial_id)) {
            $byId = Faixa::find($aluno->faixa_inicial_id);
            if ($byId) return $byId;
        }

        if (!empty($aluno->faixa)) {
            $nome = Str::lower(trim($aluno->faixa));
            $byNome = Faixa::whereRaw('LOWER(TRIM(nome)) = ?', [$nome])->first();
            if ($byNome) return $byNome;
        }

        return null;
    }

    public function show($alunoId) {
        $aluno = Aluno::findOrFail($alunoId);

        $graduacoes = Graduacao::where('aluno_id', $aluno->id)
            ->with(['faixaAnterior','faixaNova'])
            ->orderBy('data_graduacao')
            ->get();

        $ultima        = Graduacao::where('aluno_id', $aluno->id)->latest('data_graduacao')->first();
        $faixaCadastro = $this->resolveFaixaInicial($aluno);
        $faixaAtual    = $ultima?->faixaNova ?? $faixaCadastro;

        if (!$faixaAtual) {
            return redirect()->route('faixas.index')
                ->with('error', 'Defina a faixa do aluno no cadastro ou cadastre as faixas no sistema.');
        }

        $proximaFaixa = Faixa::where('ordem', '>', $faixaAtual->ordem)->orderBy('ordem')->first();

        $inicioFaixa = $ultima?->data_graduacao ?? ($aluno->data_matricula ?? $aluno->created_at);
        $dtInicio    = $inicioFaixa ? Carbon::parse($inicioFaixa) : null;

        $mesesDecorridos = $dtInicio ? $dtInicio->diffInMonths(now()) : 0;

        if ($dtInicio) {
            $diff     = $dtInicio->diff(now());
            $mesesInt = $diff->y * 12 + $diff->m;
            $diasRest = $diff->d;
            $mesesDecorridosFmt = $mesesInt . ' meses' . ($diasRest ? ' e ' . $diasRest . ' dias' : '');
        } else {
            $mesesDecorridosFmt = '0 meses';
        }

        $grausTotais   = (int)($faixaAtual->graus_totais ?? 4);
        $tempoMinMeses = (int)($faixaAtual->tempo_min_meses ?? 18);

        $grausDados = Grau::where('aluno_id',$aluno->id)
            ->where('faixa_id', $faixaAtual->id)
            ->when($inicioFaixa, fn($q)=>$q->whereDate('data','>=',$inicioFaixa))
            ->orderBy('numero')
            ->get();

        $qtdGraus          = $grausDados->count();
        $proximoGrauNumero = min($grausTotais, $qtdGraus + 1);

        $mesesPorGrau         = max(1, (int)ceil($tempoMinMeses / max(1,$grausTotais)));
        $minMesesParaProxGrau = $proximoGrauNumero * $mesesPorGrau;

        $elegivelProxGrau = $qtdGraus < $grausTotais && $mesesDecorridos >= $minMesesParaProxGrau;
        $dataProxGrauEst  = $inicioFaixa ? Carbon::parse($inicioFaixa)->addMonths($minMesesParaProxGrau) : null;

        $elegivelPromocao = $proximaFaixa && $qtdGraus >= $grausTotais;
        $dataPromocaoEst  = null; 

        [$progresso, $quebra] = $this->calculaProgresso($aluno, $faixaAtual, $proximaFaixa, $ultima);

        return view('alunos.evolucao', compact(
            'aluno','faixaAtual','proximaFaixa','graduacoes',
            'progresso','quebra',
            'grausDados','qtdGraus','grausTotais','mesesDecorridos',
            'elegivelProxGrau','dataProxGrauEst','proximoGrauNumero',
            'elegivelPromocao','dataPromocaoEst','tempoMinMeses','mesesPorGrau',
            'mesesDecorridosFmt'
        ));
    }

    public function storeGrau(Request $request, $alunoId) {
        $aluno  = Aluno::findOrFail($alunoId);
        $ultima = Graduacao::where('aluno_id',$aluno->id)->latest('data_graduacao')->first();

        $faixaCadastro = $this->resolveFaixaInicial($aluno);
        $faixaAtual    = $ultima?->faixaNova ?? $faixaCadastro;
        if (!$faixaAtual) return back()->with('error','Defina a faixa inicial do aluno no cadastro.');

        $inicioFaixa   = $ultima?->data_graduacao ?? ($aluno->data_matricula ?? $aluno->created_at);
        $grausTotais   = (int)($faixaAtual->graus_totais ?? 4);
        $tempoMinMeses = (int)($faixaAtual->tempo_min_meses ?? 18);

        $qtdGraus = Grau::where('aluno_id',$aluno->id)->where('faixa_id',$faixaAtual->id)
            ->when($inicioFaixa, fn($q)=>$q->whereDate('data','>=',$inicioFaixa))
            ->count();

        $proximo         = min($grausTotais, $qtdGraus + 1);
        $mesesDecorridos = $inicioFaixa ? Carbon::parse($inicioFaixa)->diffInMonths(now()) : 0;
        $mesesPorGrau    = max(1, (int)ceil($tempoMinMeses / max(1,$grausTotais)));
        $minMesesProx    = $proximo * $mesesPorGrau;

        $elegivel = $qtdGraus < $grausTotais && $mesesDecorridos >= $minMesesProx;
        $forcar   = $request->boolean('forcar');

        if (!$elegivel && !$forcar) {
            return back()->with('error', 'Ainda não elegível para o próximo grau.');
        }

        $grau = Grau::create([
            'aluno_id'       => $aluno->id,
            'faixa_id'       => $faixaAtual->id,
            'numero'         => $proximo,
            'data'           => now()->toDateString(),
            'instrutor_nome' => $request->input('instrutor_nome'),
            'observacoes'    => $request->input('observacoes'),
        ]);

        Audit::create([
            'user_id'    => Auth::id(),
            'aluno_id'   => $aluno->id,
            'acao'       => $forcar ? 'grau_forcado' : 'grau_concedido',
            'detalhes'   => [
                'faixa'                 => $faixaAtual->nome,
                'grau_numero'           => $proximo,
                'elegivel'              => $elegivel,
                'qtd_graus'             => $qtdGraus,
                'graus_totais'          => $grausTotais,
                'meses_decorridos'      => $mesesDecorridos,
                'min_meses_para_grau'   => $minMesesProx,
            ],
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $msg = $forcar ? "Grau {$proximo} concedido (forçado)." : "Grau {$proximo} concedido!";
        return back()->with($forcar ? 'error' : 'success', $msg);
    }

    public function promover(Request $request, $alunoId) {
        $aluno  = Aluno::findOrFail($alunoId);
        $ultima = Graduacao::where('aluno_id',$aluno->id)->latest('data_graduacao')->first();

        $faixaCadastro = $this->resolveFaixaInicial($aluno);
        $faixaAtual    = $ultima?->faixaNova ?? $faixaCadastro;
        if (!$faixaAtual) return back()->with('error','Defina a faixa inicial do aluno no cadastro.');

        $proximaFaixa = Faixa::where('ordem','>',$faixaAtual->ordem)->orderBy('ordem')->first();
        if (!$proximaFaixa) return back()->with('error','Já está na última faixa.');

        $inicioFaixa   = $ultima?->data_graduacao ?? ($aluno->data_matricula ?? $aluno->created_at);
        $grausTotais   = (int)($faixaAtual->graus_totais ?? 4);
        $tempoMinMeses = (int)($faixaAtual->tempo_min_meses ?? 18);

        $qtdGraus        = Grau::where('aluno_id',$aluno->id)->where('faixa_id',$faixaAtual->id)
            ->when($inicioFaixa, fn($q)=>$q->whereDate('data','>=',$inicioFaixa))
            ->count();

        if ($qtdGraus < $grausTotais) {
            return back()->with('error', "Só é possível promover após completar todos os {$grausTotais} graus da faixa atual.");
        }

        $grad = Graduacao::create([
            'aluno_id'          => $aluno->id,
            'faixa_anterior_id' => $faixaAtual->id,
            'faixa_nova_id'     => $proximaFaixa->id,
            'data_graduacao'    => now()->toDateString(),
            'instrutor_nome'    => $request->input('instrutor_nome'),
            'observacoes'       => $request->input('observacoes'),
        ]);

        Audit::create([
            'user_id'  => Auth::id(),
            'aluno_id' => $aluno->id,
            'acao'     => 'promocao',
            'detalhes' => [
                'de' => $faixaAtual->nome, 'para' => $proximaFaixa->nome,
                'qtd_graus' => $qtdGraus, 'graus_totais' => $grausTotais,
            ],
            'ip'        => $request->ip(),
            'user_agent'=> $request->userAgent(),
        ]);

        return back()->with('success', "Aluno promovido para {$proximaFaixa->nome}!");
    }

    private function calculaProgresso($aluno, $faixaAtual, $proximaFaixa, $ultimaGraduacao) {
        if (!$faixaAtual)   return [0, []];
        if (!$proximaFaixa) return [100, []];

        $inicio = $ultimaGraduacao?->data_graduacao ?? ($aluno->data_matricula ?? $aluno->created_at);
        $meses  = $inicio ? Carbon::parse($inicio)->diffInMonths(now()) : 0;

        $grausTotais   = (int)($faixaAtual->graus_totais ?? 4);
        $tempoMinMeses = (int)($faixaAtual->tempo_min_meses ?? 18);

        $qtdGraus = Grau::where('aluno_id', $aluno->id)
            ->where('faixa_id', $faixaAtual->id)
            ->when($inicio, fn($q) => $q->whereDate('data', '>=', $inicio))
            ->count();

        $ratioGraus = $grausTotais   > 0 ? min(1, $qtdGraus / $grausTotais) : 0;
        $ratioTempo = $tempoMinMeses > 0 ? min(1, $meses    / $tempoMinMeses) : 0;

        $progresso = round((($ratioGraus * 0.6) + ($ratioTempo * 0.4)) * 100);
        return [$progresso, []];
    }
}
