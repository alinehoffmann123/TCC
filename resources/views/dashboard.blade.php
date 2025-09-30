@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-3xl font-bold text-gray-dark mb-8 animate-fade-in">Bem-vindo!</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light transform hover:scale-105 transition-all duration-300 animate-fade-in animate-delay-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-dark">Alunos Ativos</h3>
                <div class="p-2 rounded-full bg-bordo-dark/10 text-bordo-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-5xl font-extrabold text-bordo-dark">{{ number_format($iAlunosAtivos, 0, ',', '.') }}</p>
            <p class="text-gray-dark/70 mt-2">Total de alunos ativos.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light transform hover:scale-105 transition-all duration-300 animate-fade-in animate-delay-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-dark">Próximas Aulas</h3>
                <div class="p-2 rounded-full bg-bordo-dark/10 text-bordo-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 13a2 2 0 002 2h8a2 2 0 002-2L16 7"></path>
                    </svg>
                </div>
            </div>
            <p class="text-5xl font-extrabold text-bordo-dark">{{ str_pad($iProximasAulas, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-gray-dark/70 mt-2">Aulas agendadas para hoje.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light transform hover:scale-105 transition-all duration-300 animate-fade-in animate-delay-400">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-dark">Novas Matrículas</h3>
                <div class="p-2 rounded-full bg-bordo-dark/10 text-bordo-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-5xl font-extrabold text-bordo-dark">{{ str_pad($iNovasMatriculasSemana, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-gray-dark/70 mt-2">Novos alunos na última semana.</p>
        </div>
    </div>

   <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light">
            <h3 class="text-xl font-semibold text-gray-dark mb-4">
            Evolução de Alunos ({{ now()->year }})
            </h3>
            <div id="hcEvolucao" class="h-64 md:h-72" style="min-height:16rem"></div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light">
            <h3 class="text-xl font-semibold text-gray-dark mb-4">
            Distribuição de Faixas
            </h3>
            <div id="hcFaixas" class="h-64 md:h-72" style="min-height:16rem"></div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light animate-fade-in animate-delay-400">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-dark">Atividades Recentes</h3>
            <div class="flex space-x-2">
                <a href="{{ route('alunos.create') }}"
                class="inline-flex items-center px-3 py-2 bg-bordo-dark text-white text-sm font-medium rounded-lg shadow hover:bg-bordo-dark/90 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Aluno
                </a>
                <a href="{{ route('turmas.create') }}"
                class="inline-flex items-center px-3 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg shadow hover:bg-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m-6 8h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                    </svg>
                    Turma
                </a>
            </div>
        </div>

        <ul class="space-y-4">
            @forelse($aAtividadesRecentes as $aAtividade)
                <li class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full {{ $aAtividade['tipo']==='aluno' ? 'bg-bordo-dark' : 'bg-gray-600' }} flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ $aAtividade['iniciais'] }}
                    </div>
                    <div>
                        <p class="text-gray-dark font-medium">
                            {{ $aAtividade['titulo'] }}
                            <span class="text-gray-dark/70 font-normal">{{ ' '.$aAtividade['descricao'] }}</span>
                        </p>
                        <p class="text-gray-dark/50 text-xs mt-1">{{ $aAtividade['quando']->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="text-gray-dark/60">Sem atividades recentes.</li>
            @endforelse
        </ul>
    </div>
    </div>
@endsection

@push('scripts')
  <script src="https://code.highcharts.com/highcharts.js" defer></script>
  <script src="https://code.highcharts.com/modules/exporting.js" defer></script>
  <script src="https://code.highcharts.com/modules/accessibility.js" defer></script>
  <script id="dashboard-json" type="application/json">
  {!! json_encode([
      'aLabelsMeses'  => array_values($aLabelMeses ?? []),
      'aDataEvolucao' => array_values($aDataEvolucao ?? []),
      'aLabelsFaixa'  => array_values($aLabelsFaixa ?? []),
      'aDatasFaixa'   => array_values($aDataFaixas ?? []),
  ], JSON_UNESCAPED_UNICODE) !!}
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const oElemento = document.getElementById('dashboard-json');
      let oData = {aLabelsMeses:[],aDataEvolucao:[],aLabelsFaixa:[],aDatasFaixa:[]};
      try { 
        if (oElemento) {
         oData = JSON.parse(oElemento.textContent);   
        } 
      } catch(oExept) { 
        console.error('JSON do dashboard inválido', oExept); 
      }

      const aCores = ['#4B001E','#73002A','#6B7280','#9CA3AF','#111827','#374151'];

      const OEvolucao = document.getElementById('hcEvolucao');
      if (OEvolucao) {
        Highcharts.chart('hcEvolucao', {
          chart: { 
            type: 'line'
            , height: 256 
          },
          title: { 
            text: null 
          },
          xAxis: { 
            categories: oData.aLabelsMeses
            , tickLength: 0 
          },
          yAxis: { 
            title: { 
                text: 'Matrículas' }
            , allowDecimals: false 
          },
          legend: { 
            enabled: false 
          },
          series: [{
            name: 'Matrículas',
            oData: (oData.aDataEvolucao || []).map(n => Number(n) || 0),
            color: aCores[0],
          }],
          tooltip: { 
            shared: true
            , valueDecimals: 0
          },
          credits: { 
            enabled: false 
          }
        });
      }

    const oFaixas = document.getElementById('hcFaixas');
    if (oFaixas) {
        Highcharts.chart('hcFaixas', {
            chart: { 
                type: 'column'
                , height: 256 
            },
            title: { 
                text: null 
            },
            xAxis: {
                categories: oData.aLabelsFaixa
                , tickLength: 0
                , crosshair: true
            },
            yAxis: {
                min: 0
                , allowDecimals: false
                , title: { 
                    text: 'Alunos' 
                }
            },
            legend: { 
                enabled: false 
            },
            tooltip: { 
                pointFormat: '<b>{point.y}</b> alunos'
            },
            plotOptions: {
            column: {
                borderRadius: 4
                , pointPadding: 0.1
                , groupPadding: 0.2
                , dataLabels: { 
                    enabled: true 
                }
            },
            series: {
                animation: { 
                    duration: 300 
                } 
            }
            },
            series: [{
                name: 'Alunos'
                , oData: (data.aDatasFaixa || []).map(n => Number(n) || 0)
                , colorByPoint: true
            }],
            colors: ['#4B001E','#73002A','#6B7280','#9CA3AF','#111827','#374151'],
            credits: { 
                enabled: false 
            }
        });
    }
    });
  </script>
@endpush



