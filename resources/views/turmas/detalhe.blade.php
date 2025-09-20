@extends('layouts.app')

@section('title', 'Detalhes da Turma - ' . $aTurmas->nome)

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Detalhes da Turma: <span class="text-bordo-dark">{{ $aTurmas->nome }}</span></h2>
            <p class="text-gray-dark/70 mt-1">Informações completas sobre a turma.</p>
        </div>
        <a href="{{ route('turmas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Voltar às Turmas</span>
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light animate-fade-in animate-delay-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Dados da Turma
                </h4>
                <div class="space-y-2 text-gray-dark">
                    <p><strong>Nome:</strong> {{ $aTurmas->nome }}</p>
                    <p><strong>Instrutor:</strong> {{  $aTurmas->instrutor_nome }}</p>
                    <p><strong>Modalidade:</strong> 
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @php
                                switch ($aTurmas->modalidade) {
                                    case 'gi': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'no-gi': echo 'bg-purple-100 text-purple-800'; break;
                                    case 'mma': echo 'bg-red-100 text-red-800'; break;
                                    case 'defesa-pessoal': echo 'bg-green-100 text-green-800'; break;
                                }
                            @endphp
                        ">{{ ucfirst(str_replace('-', ' ', $aTurmas->modalidade)) }}</span>
                    </p>
                    <p><strong>Nível:</strong> 
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @php
                                switch ($aTurmas->nivel) {
                                    case 'iniciante': echo 'bg-green-100 text-green-800'; 
                                    break;
                                    case 'intermediario': echo 'bg-yellow-100 text-yellow-800'; 
                                    break;
                                    case 'avancado': echo 'bg-red-100 text-red-800'; 
                                    break;
                                    case 'misto': echo 'bg-blue-100 text-blue-800'; 
                                break;
                                }
                            @endphp
                        ">{{ ucfirst($aTurmas->nivel) }}</span>
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @php
                                switch ($aTurmas->status) {
                                    case 'ativa': echo 'bg-green-100 text-green-800'; 
                                    break;
                                    case 'inativa': echo 'bg-red-100 text-red-800'; 
                                    break;
                                    case 'pausada': echo 'bg-yellow-100 text-yellow-800'; 
                                break;
                                }
                            @endphp
                        ">{{ ucfirst($aTurmas->status) }}</span>
                    </p>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Horários e Capacidade
                </h4>
                <div class="space-y-2 text-gray-dark">
                    <p><strong>Horário:</strong> {{ $aTurmas->horario_inicio->format('H:i') }} - {{ $aTurmas->horario_fim->format('H:i') }}</p>
                    <p><strong>Dias:</strong> {{ $aTurmas->dias_semana_formatados }}</p>
                    <p><strong>Capacidade Máxima:</strong> {{ $aTurmas->capacidade_maxima }} alunos</p>
                    <p><strong>Alunos Atuais:</strong> {{ $aTurmas->numero_alunos }}</p>
                    <p><strong>Ocupação:</strong> {{ $aTurmas->ocupacao_percentual }}%</p>
                </div>
            </div>
        </div>
        @if($aTurmas->observacoes)
        <div class="mt-6 border-t pt-6">
            <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Observações
            </h4>
            <p class="text-gray-dark">{{ $aTurmas->observacoes }}</p>
        </div>
        @endif
        <div class="mt-6 border-t pt-6">
            <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Alunos Matriculados ({{ $aTurmas->alunos->count() }})
            </h4>
            @if($aTurmas->alunos->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($aTurmas->alunos as $aluno)
                        <span class="px-3 py-1 bg-bordo-dark/10 text-bordo-dark rounded-full text-sm font-medium">
                            {{ $aluno->nome }}
                            <span class="text-bordo-dark/70 ml-1">
                                ({{ ucfirst($aluno->faixa) }})
                            </span>
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-dark/70 italic">Nenhum aluno matriculado nesta turma.</p>
            @endif
        </div>
        <div class="mt-6 border-t pt-6 text-right">
            <a href="{{ route('turmas.edit', $aTurmas->id) }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Editar Turma</span>
            </a>
        </div>
    </div>
</div>
@endsection
