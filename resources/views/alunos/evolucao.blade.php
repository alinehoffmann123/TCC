@extends('layouts.app')

@section('title', 'Evolução do Aluno')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="animate-fade-in" x-data="{ showForceModal: false }">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-3xl font-bold text-gray-dark">
        Evolução — {{ $aluno->nome ?? ('Aluno #'.$aluno->id) }}
      </h2>
      <p class="text-gray-dark/70 mt-1">
        Faixa atual: <strong>{{ $faixaAtual->nome ?? '—' }}</strong>
        @if($proximaFaixa)
          • Próxima: <strong>{{ $proximaFaixa->nome }}</strong>
        @endif
      </p>
    </div>
    <a href="{{ route('alunos.index') }}"
       class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200">
      Voltar
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
  @endif

  <div class="bg-white rounded-xl shadow-lg border border-gray-light p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <div class="text-lg font-semibold text-gray-900">
          Faixa atual: {{ $faixaAtual->nome ?? '—' }}
        </div>
        <div class="text-gray-600 text-sm">
          Graus: <strong>{{ $qtdGraus }}</strong> / {{ $grausTotais }}
          • Tempo decorrido: <strong>{{ $mesesDecorridosFmt }}</strong>
          • Tempo mínimo: <strong>{{ $tempoMinMeses }}</strong> meses
        </div>
        <div class="text-gray-600 text-sm">
          Próximo grau: <strong>#{{ $proximoGrauNumero }}</strong>
          @if($dataProxGrauEst)
            • Elegível em: <strong>{{ $dataProxGrauEst->format('d/m/Y') }}</strong>
          @endif
        </div>
        @if($proximaFaixa)
          <div class="text-gray-600 text-sm">
            Próxima faixa: <strong>{{ $proximaFaixa->nome }}</strong>
            @if($dataPromocaoEst)
              • Elegível em: <strong>{{ $dataPromocaoEst->format('d/m/Y') }}</strong>
            @endif
          </div>
        @endif
      </div>

      @php($role = auth()->user()?->role)
      @if(in_array($role, ['admin','professor']))
        <div class="flex gap-3">
          {{-- Conceder Grau (sempre habilitado) --}}
          <form method="POST" action="{{ route('alunos.evolucao.grau.store', $aluno->id) }}">
            @csrf
            <button type="submit"
                    class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md">
              Conceder Grau
            </button>
          </form>

          {{-- Forçar Grau (abre modal) --}}
          <button type="button"
                  @click="showForceModal = true"
                  class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md">
            Forçar Grau
          </button>

          @if($proximaFaixa)
            @if($elegivelPromocao)
              <form method="POST" action="{{ route('alunos.evolucao.promover', $aluno->id) }}">
                @csrf
                <button type="submit"
                        class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-md">
                  Promover para {{ $proximaFaixa->nome }}
                </button>
              </form>
            @else
              <button type="button"
                      class="bg-gray-400 text-white font-semibold py-2 px-4 rounded-md cursor-not-allowed"
                      title="Complete todos os graus para promover"
                      disabled>
                Promover para {{ $proximaFaixa->nome }}
              </button>
            @endif
          @endif
        </div>
      @endif
    </div>
  </div>

  <!-- Progresso para próxima faixa -->
  <div class="bg-white rounded-xl shadow-lg border border-gray-light p-6 mb-6">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-xl font-semibold text-gray-dark">Progresso para a próxima faixa</h3>
      <span class="text-gray-600">{{ $progresso }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
      <div class="bg-bordo-dark h-3 rounded-full" style="width: {{ $progresso }}%"></div>
    </div>
    @if(!empty($quebra))
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        @foreach($quebra as $q)
          <div class="p-3 rounded-lg border border-gray-light bg-gray-50">
            <div class="font-medium text-gray-800">{{ ucwords(str_replace('_',' ', $q['chave'])) }}</div>
            <div class="text-gray-600">Atingido: <strong>{{ $q['atingido'] }}</strong> / Exigido: <strong>{{ $q['exigido'] }}</strong></div>
            <div class="text-gray-600">Peso: {{ $q['peso'] }}</div>
            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
              <div class="bg-bordo-dark h-2 rounded-full" style="width: {{ $q['percent'] }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- Timeline de graduações -->
  <div class="bg-white rounded-xl shadow-lg border border-gray-light p-6">
    <h3 class="text-xl font-semibold text-gray-dark mb-4">Histórico de Graduações</h3>
    @if($graduacoes->isEmpty())
      <p class="text-gray-600">Nenhuma graduação registrada.</p>
    @else
      <ol class="relative border-l border-gray-200">
        @foreach($graduacoes as $g)
          <li class="mb-6 ml-6">
            <span class="absolute -left-1.5 flex h-3 w-3 rounded-full bg-bordo-dark"></span>
            <time class="mb-1 text-sm leading-none text-gray-500">
              {{ \Carbon\Carbon::parse($g->data_graduacao)->format('d/m/Y') }}
            </time>
            <h4 class="text-base font-semibold text-gray-900">
              {{ optional($g->faixaAnterior)->nome ?? '—' }} → {{ optional($g->faixaNova)->nome }}
            </h4>
            @if($g->instrutor_nome)
              <p class="text-sm text-gray-600">Instrutor: {{ $g->instrutor_nome }}</p>
            @endif
            @if($g->observacoes)
              <p class="text-sm text-gray-700 mt-1">{{ $g->observacoes }}</p>
            @endif
          </li>
        @endforeach
      </ol>
    @endif
  </div>

  {{-- Modal: Forçar concessão de grau (fica DENTRO do mesmo x-data) --}}
  <template x-teleport="body">
    <div x-show="showForceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50"
         style="display: none;"
         aria-modal="true" role="dialog">

      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black bg-opacity-50"></div>

      <!-- Dialog -->
      <div class="fixed inset-0 flex items-center justify-center px-4 py-8">
        <div x-show="showForceModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6"
             x-cloak>
          <div class="sm:flex sm:items-start">
            <div class="mx-auto sm:mx-0 flex-shrink-0 h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M4.93 19.07A10 10 0 1119.07 4.93 10 10 0 014.93 19.07z"/>
              </svg>
            </div>

            <div class="mt-3 sm:mt-0 sm:ml-4 text-left">
              <h3 class="text-lg leading-6 font-semibold text-gray-900">
                Forçar concessão de grau
              </h3>
              <div class="mt-2 text-sm text-gray-600 space-y-2">
                <p>Esta ação será <strong>auditada</strong> e permite conceder o próximo grau mesmo sem atender aos critérios.</p>
                <ul class="list-disc list-inside">
                  <li>Aluno: <strong>{{ $aluno->nome }}</strong></li>
                  <li>Faixa atual: <strong>{{ $faixaAtual->nome ?? '—' }}</strong></li>
                  <li>Próximo grau: <strong>#{{ $proximoGrauNumero }}</strong></li>
                  <li>Graus obtidos: <strong>{{ $qtdGraus }}</strong> / {{ $grausTotais }}</li>
                  <li>Tempo decorrido: <strong>{{ $mesesDecorridosFmt }}</strong></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="mt-6 sm:mt-5 sm:flex sm:flex-row-reverse gap-3">
            {{-- Confirma e envia com forcar=1 --}}
            <form method="POST" action="{{ route('alunos.evolucao.grau.store', $aluno->id) }}">
              @csrf
              <input type="hidden" name="forcar" value="1">
              <button type="submit"
                      class="inline-flex justify-center w-full sm:w-auto px-4 py-2 rounded-md bg-red-600 text-white font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Confirmar Força
              </button>
            </form>

            {{-- Cancelar --}}
            <button type="button"
                    @click="showForceModal = false"
                    class="inline-flex justify-center w-full sm:w-auto px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bordo-dark">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
@endsection
