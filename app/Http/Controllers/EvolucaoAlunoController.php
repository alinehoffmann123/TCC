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

class EvolucaoAlunoController extends Controller {
    /**
     * Resolve e retorna a faixa inicial de um aluno.
     *
     * A função verifica em diferentes fontes a faixa inicial do aluno
     * e retorna a primeira encontrada, seguindo a ordem de prioridade:
     *
     * 1. Se o aluno tiver o relacionamento `faixaInicial` já carregado,
     *    retorna diretamente essa relação.
     * 2. Se o método `faixaInicial()` existir, busca a primeira faixa
     *    relacionada no banco de dados.
     * 3. Caso o aluno possua o campo `faixa_inicial_id`, busca a faixa
     *    correspondente pelo ID.
     * 4. Se o aluno tiver o campo `faixa` preenchido com o nome da faixa,
     *    faz a busca no banco ignorando maiúsculas/minúsculas e espaços extras.
     * 5. Se nenhuma das verificações anteriores encontrar uma faixa, retorna `null`.
     *
     * Exemplos de casos tratados:
     * - Aluno já tem a faixa carregada na relação (`relationLoaded`).
     * - Aluno tem o ID da faixa salvo, mas não a relação.
     * - Aluno tem apenas o nome da faixa como texto (campo string).
     *
     * @param Aluno $oAluno Instância do aluno a ser avaliado.
     * @return Faixa|null Retorna a faixa inicial encontrada ou null se não houver.
     */
    private function resolveFaixaInicial(Aluno $oAluno): ?Faixa {
        if (method_exists($oAluno, 'faixaInicial') && $oAluno->relationLoaded('faixaInicial')) {
            if ($oAluno->faixaInicial) return $oAluno->faixaInicial;
        } elseif (method_exists($oAluno, 'faixaInicial')) {
            $oRel = $oAluno->faixaInicial()->first();
            if ($oRel) {
                return $oRel;
            }
        }

        if (!empty($oAluno->faixa_inicial_id)) {
            $oId = Faixa::find($oAluno->faixa_inicial_id);
            if ($oId) return $oId;
        }

        if (!empty($oAluno->faixa)) {
            $sNome = Str::lower(trim($oAluno->faixa));
            $oByNome = Faixa::whereRaw('LOWER(TRIM(nome)) = ?', [$sNome])->first();
            if ($oByNome) {
                return $oByNome;
            }
        }

        return null;
    }

    /**
     * Exibe a evolução de um aluno em relação às suas faixas e graus.
     *
     * Fluxo principal:
     *  - Busca o aluno pelo ID.
     *  - Recupera suas graduações (histórico de trocas de faixa).
     *  - Define a faixa atual:
     *      • Se o aluno tem graduações → usa a última faixaNova.
     *      • Caso contrário → usa a faixa inicial cadastrada.
     *  - Se não houver faixa definida em nenhum lugar, redireciona pedindo configuração.
     *  - Identifica a próxima faixa (com ordem maior que a atual).
     *  - Define a data de início da faixa atual:
     *      • Data da última graduação, ou
     *      • Data de matrícula, ou
     *      • Data de criação do cadastro.
     *  - Calcula o tempo decorrido desde o início da faixa (em meses e dias).
     *  - Recupera os graus já conquistados nessa faixa.
     *  - Calcula:
     *      • Quantos graus o aluno já possui.
     *      • Qual será o próximo grau.
     *      • Tempo mínimo necessário para cada grau.
     *      • Se o aluno já é elegível para o próximo grau.
     *      • Data estimada para o próximo grau.
     *  - Verifica se o aluno está apto à promoção de faixa:
     *      • Deve existir uma próxima faixa.
     *      • O aluno precisa ter conquistado todos os graus da faixa atual.
     *  - Calcula o progresso do aluno (percentual/visão gráfica).
     *
     * Retorna a view `alunos.evolucao` com todos os dados calculados.
     *
     * @param  int  $iCodigoAluno  ID do aluno a ser exibido.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($iCodigoAluno) {
        $oAluno = Aluno::findOrFail($iCodigoAluno);

        $aGraduacoes = Graduacao::where('aluno_id', $oAluno->id)
            ->with(['faixaAnterior','faixaNova'])
            ->orderBy('data_graduacao')
            ->get();

        $oUltima        = Graduacao::where('aluno_id', $oAluno->id)->latest('data_graduacao')->first();
        $oFaixaCadastro = $this->resolveFaixaInicial($oAluno);
        $oFaixaAtual    = $oUltima?->faixaNova ?? $oFaixaCadastro;

        if (!$oFaixaAtual) {
            return redirect()->route('faixas.index')
                ->with('error', 'Defina a faixa do aluno no cadastro ou cadastre as faixas no sistema.');
        }

        $oProximaFaixa = Faixa::where('ordem', '>', $oFaixaAtual->ordem)->orderBy('ordem')->first();

        $oInicioFaixa = $oUltima?->data_graduacao ?? ($oAluno->data_matricula ?? $oAluno->created_at);
        $sDatInicio    = $oInicioFaixa ? Carbon::parse($oInicioFaixa) : null;

        $iMesesDecorridos = $sDatInicio ? $sDatInicio->diffInMonths(now()) : 0;

        if ($sDatInicio) {
            $oDiferenca     = $sDatInicio->diff(now());
            $iMeses = $oDiferenca->y * 12 + $oDiferenca->m;
            $iDiasRestantes = $oDiferenca->d;
            $iMesesDecorridosFmt = $iMeses . ' meses' . ($iDiasRestantes ? ' e ' . $iDiasRestantes . ' dias' : '');
        } else {
            $iMesesDecorridosFmt = '0 meses';
        }

        $iGrausTotais   = (int)($oFaixaAtual->graus_totais ?? 4);
        $iTempoMinMeses = (int)($oFaixaAtual->tempo_min_meses ?? 18);

        $iGrausDados = Grau::where('aluno_id',$oAluno->id)
            ->where('faixa_id', $oFaixaAtual->id)
            ->when($oInicioFaixa, fn($oQuery)=>$oQuery->whereDate('data','>=',$oInicioFaixa))
            ->orderBy('numero')
            ->get();

        $iQuantidadeGraus          = $iGrausDados->count();
        $iProximoGrau = min($iGrausTotais, $iQuantidadeGraus + 1);

        $iMesesPorGrau         = max(1, (int)ceil($iTempoMinMeses / max(1,$iGrausTotais)));
        $iMinMesesProximoGrau = $iProximoGrau * $iMesesPorGrau;

        $bElegivelProximoGrau = $iQuantidadeGraus < $iGrausTotais && $iMesesDecorridos >= $iMinMesesProximoGrau;
        $sDataProximoGrau  = $oInicioFaixa ? Carbon::parse($oInicioFaixa)->addMonths($iMinMesesProximoGrau) : null;

        $bElegivelPromocao = $oProximaFaixa && $iQuantidadeGraus >= $iGrausTotais;
        $sDataPromocaoEst  = null; 

        [$iProgresso, $aQuebras] = $this->calculaProgresso($oAluno, $oFaixaAtual, $oProximaFaixa, $oUltima);

        return view('alunos.evolucao', compact(
              'oAluno'
            , 'oFaixaAtual'
            , 'oProximaFaixa'
            , 'aGraduacoes'
            , 'iProgresso'
            , 'aQuebras'
            , 'iGrausDados'
            , 'iQuantidadeGraus'
            , 'iGrausTotais'
            , 'iMesesDecorridos'
            , 'bElegivelProximoGrau'
            , 'sDataProximoGrau'
            , 'iProximoGrau'
            , 'bElegivelPromocao'
            , 'sDataPromocaoEst'
            , 'iTempoMinMeses'
            , 'iMesesPorGrau'
            , 'iMesesDecorridosFmt'
        ));
    }

    /**
     * Registra a concessão de um novo grau para o aluno.
     *
     * A função valida se o aluno está elegível para receber um próximo grau
     * baseado em:
     * - Faixa atual e total de graus possíveis.
     * - Tempo mínimo em meses exigido para cada grau.
     * - Meses decorridos desde a última graduação ou início da faixa.
     *
     * Caso o aluno não seja elegível, o sistema só permite a concessão
     * se for usada a opção de "forçar".
     *
     * Além de registrar o novo grau, também cria um log na tabela `Audit`.
     *
     * @param \Illuminate\Http\Request $oRequest       Requisição HTTP contendo dados adicionais.
     * @param int                      $iCodigoAluno   Código identificador do aluno.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeGrau(Request $oRequest, $iCodigoAluno) {
        $oAluno  = Aluno::findOrFail($iCodigoAluno);
        $aUltima = Graduacao::where('aluno_id',$oAluno->id)->latest('data_graduacao')->first();

        $sFaixaCadastro = $this->resolveFaixaInicial($oAluno);
        $sFaixaAtual    = $aUltima?->faixaNova ?? $sFaixaCadastro;
        if (!$sFaixaAtual) return back()->with('error','Defina a faixa inicial do aluno no cadastro.');

        $sInicioFaixa   = $aUltima?->data_graduacao ?? ($oAluno->data_matricula ?? $oAluno->created_at);
        $iGrausTotais   = ($sFaixaAtual->graus_totais ?? 4);
        $iTempoMinMeses = ($sFaixaAtual->tempo_min_meses ?? 18);

        $iQuantidadeGraus = Grau::where('aluno_id',$oAluno->id)->where('faixa_id',$sFaixaAtual->id)
            ->when($sInicioFaixa, fn($oQuery)=>$oQuery->whereDate('data', '>=', $sInicioFaixa))
            ->count();

        $iProximo         = min($iGrausTotais, $iQuantidadeGraus + 1);
        $iMesesDecorridos = $sInicioFaixa ? Carbon::parse($sInicioFaixa)->diffInMonths(now()) : 0;
        $iMesesPorGrau    = max(1, (int)ceil($iTempoMinMeses / max(1,$iGrausTotais)));
        $iMinMesesProx    = $iProximo * $iMesesPorGrau;

        $iElegivel = $iQuantidadeGraus < $iGrausTotais && $iMesesDecorridos >= $iMinMesesProx;
        $bForcar    = $oRequest->boolean('forcar');

        if (!$iElegivel && !$bForcar) {
            return back()->with('error', 'Ainda não elegível para o próximo grau.');
        }

        $oGrau = Grau::create([
            'aluno_id'       => $oAluno->id,
            'faixa_id'       => $sFaixaAtual->id,
            'numero'         => $iProximo,
            'data'           => now()->toDateString(),
            'instrutor_nome' => $oRequest->input('instrutor_nome'),
            'observacoes'    => $oRequest->input('observacoes'),
        ]);

        Audit::create([
            'user_id'    => Auth::id(),
            'aluno_id'   => $oAluno->id,
            'acao'       => $bForcar ? 'grau_forcado' : 'grau_concedido',
            'detalhes'   => [
                'faixa'                 => $sFaixaAtual->nome,
                'grau_numero'           => $iProximo,
                'elegivel'              => $iElegivel,
                'qtd_graus'             => $iQuantidadeGraus,
                'graus_totais'          => $iGrausTotais,
                'meses_decorridos'      => $iMesesDecorridos,
                'min_meses_para_grau'   => $iMinMesesProx,
            ],
            'ip'         => $oRequest->ip(),
            'user_agent' => $oRequest->userAgent(),
        ]);

        $sMensagem = $bForcar ? "Grau {$iProximo} concedido (forçado)." : "Grau {$iProximo} concedido!";
        return back()->with($bForcar ? 'error' : 'success', $sMensagem);
    }

    /**
     * Promove um aluno para a próxima faixa.
     *
     * A função verifica se o aluno já completou todos os graus da faixa atual.
     * Caso positivo, registra uma nova graduação e cria um log na tabela `Audit`.
     *
     * Regras principais:
     * - O aluno deve ter uma faixa inicial definida.
     * - Não pode estar na última faixa.
     * - Deve ter alcançado todos os graus exigidos na faixa atual.
     *
     * @param \Illuminate\Http\Request $oRequest      Requisição HTTP com os dados adicionais.
     * @param int                      $iCodigoAluno  Código identificador do aluno.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promover(Request $oRequest, $iCodigoAluno) {
        $oAluno  = Aluno::findOrFail($iCodigoAluno);
        $sUltimaGraduacao = Graduacao::where('aluno_id',$oAluno->id)->latest('data_graduacao')->first();

        $sFaixaCadastro = $this->resolveFaixaInicial($oAluno);
        $sFaixaAtual    = $sUltimaGraduacao?->faixaNova ?? $sFaixaCadastro;
        if (!$sFaixaAtual) return back()->with('error','Defina a faixa inicial do aluno no cadastro.');

        $sProximaFaixa = Faixa::where('ordem','>',$sFaixaAtual->ordem)->orderBy('ordem')->first();
        if (!$sProximaFaixa) return back()->with('error','Já está na última faixa.');

        $sInicioFaixa   = $sUltimaGraduacao?->data_graduacao ?? ($oAluno->data_matricula ?? $oAluno->created_at);
        $iGrausTotais   = ($sFaixaAtual->graus_totais ?? 4);
        $iTempoMinMeses = ($sFaixaAtual->tempo_min_meses ?? 18);

        $iQuantidadeGraus        = Grau::where('aluno_id',$oAluno->id)->where('faixa_id',$sFaixaAtual->id)
            ->when($sInicioFaixa, fn($oQuery)=>$oQuery->whereDate('data', '>=', $sInicioFaixa))
            ->count();

        if ($iQuantidadeGraus < $iGrausTotais) {
            return back()->with('error', "Só é possível promover após completar todos os {$iGrausTotais} graus da faixa atual.");
        }

        $oGraducao = Graduacao::create([
            'aluno_id'          => $oAluno->id,
            'faixa_anterior_id' => $sFaixaAtual->id,
            'faixa_nova_id'     => $sProximaFaixa->id,
            'data_graduacao'    => now()->toDateString(),
            'instrutor_nome'    => $oRequest->input('instrutor_nome'),
            'observacoes'       => $oRequest->input('observacoes'),
        ]);

        Audit::create([
            'user_id'  => Auth::id(),
            'aluno_id' => $oAluno->id,
            'acao'     => 'promocao',
            'detalhes' => [
                'de' => $sFaixaAtual->nome, 'para' => $sProximaFaixa->nome,
                'qtd_graus' => $iQuantidadeGraus, 'graus_totais' => $iGrausTotais,
            ],
            'ip'        => $oRequest->ip(),
            'user_agent'=> $oRequest->userAgent(),
        ]);

        return back()->with('success', "Aluno promovido para {$sProximaFaixa->nome}!");
    }

    /**
     * Calcula o progresso do aluno na faixa atual.
     *
     * O cálculo leva em consideração:
     * - Quantidade de graus já conquistados (peso 60%).
     * - Tempo decorrido desde o início da faixa (peso 40%).
     *
     * Se o aluno não tiver faixa atual, retorna progresso 0.
     * Se já estiver na última faixa, retorna progresso 100.
     *
     * @param \App\Models\Aluno       $oAluno
     * @param \App\Models\Faixa|null  $oFaixaAtual
     * @param \App\Models\Faixa|null  $oProximaFaixa
     * @param \App\Models\Graduacao|null $oUltimaGraduacao
     * @return array [int $iProgresso, array $aQuebra]
     */
    private function calculaProgresso($oAluno, $sFaixaAtual, $oProximaFaixa, $ultimaGraduacao) {
        if (!$sFaixaAtual)   {
            return [0, []];
        }

        if (!$oProximaFaixa) {
            return [100, []];
        }

        $sInicio = $ultimaGraduacao?->data_graduacao ?? ($oAluno->data_matricula ?? $oAluno->created_at);
        $iMeses  = $sInicio ? Carbon::parse($sInicio)->diffInMonths(now()) : 0;

        $iGrausTotais   = (int)($sFaixaAtual->graus_totais ?? 4);
        $iTempoMinMeses = (int)($sFaixaAtual->tempo_min_meses ?? 18);

        $iQuantidadeGraus = Grau::where('aluno_id', $oAluno->id)
            ->where('faixa_id', $sFaixaAtual->id)
            ->when($sInicio, fn($oQuery) => $oQuery->whereDate('data', '>=', $sInicio))
            ->count();

        $iGraus = $iGrausTotais   > 0 ? min(1, $iQuantidadeGraus / $iGrausTotais) : 0;
        $iTempo = $iTempoMinMeses > 0 ? min(1, $iMeses    / $iTempoMinMeses) : 0;

        $aProgresso = round((($iGraus * 0.6) + ($iTempo * 0.4)) * 100);
        return [$aProgresso, []];
    }
}
