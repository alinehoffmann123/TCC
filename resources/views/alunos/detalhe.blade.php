@extends('layouts.app')

@php
    $sTipo         = $aAluno->tipo ?? 'aluno';
    $sAluno        = $sTipo === 'aluno';
    $sTituloPagina = $sAluno ? 'Aluno' : 'Professor';
@endphp

@section('title', 'Detalhes do ' . $sTituloPagina . ' - ' . $aAluno->nome)

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">
                Detalhes do {{ $sTituloPagina }}: <span class="text-bordo-dark">{{ $aAluno->nome }}</span>
            </h2>
            <p class="text-gray-dark/70 mt-1">Informações completas sobre o {{ strtolower($sTituloPagina) }}.</p>
        </div>
        <a href="{{ route('alunos.index', ['tab' => $sAluno ? 'alunos' : 'professores']) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Voltar à Listagem</span>
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light animate-fade-in animate-delay-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Dados Pessoais
                </h4>
                <div class="space-y-2 text-gray-dark">
                    <p><strong>Tipo:</strong> {{ ucfirst($sTipo) }}</p>
                    <p><strong>Nome:</strong> {{ $aAluno->nome }}</p>
                    <p><strong>Email:</strong> {{ $aAluno->email }}</p>
                    <p><strong>Telefone:</strong> {{ $aAluno->telefone ?: 'N/A' }}</p>
                    <p><strong>Nascimento:</strong>
                        {{ optional($aAluno->data_nascimento)->format('d/m/Y') ?? 'N/A' }}
                    </p>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Jiu-Jitsu
                </h4>
                <div class="space-y-2 text-gray-dark">
                    @php
                        $sFaixa = $aAluno->faixa;
                        $sCorFaixa = match ($sFaixa) {
                            'branca' => 'bg-gray-200 text-gray-800',
                            'azul'   => 'bg-blue-100 text-blue-800',
                            'roxa'   => 'bg-purple-100 text-purple-800',
                            'marrom' => 'bg-yellow-100 text-yellow-800',
                            'preta'  => 'bg-black text-white',
                            default  => 'bg-gray-100 text-gray-600',
                        };
                    @endphp

                    <p><strong>Faixa:</strong>
                        @if($sFaixa)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sCorFaixa }}">
                                {{ ucfirst($sFaixa) }}
                            </span>
                        @else
                            — 
                        @endif
                    </p>

                    @php
                        $sStatusPessoa = match ($aAluno->status) {
                            'ativo'    => 'bg-green-100 text-green-800',
                            'inativo'  => 'bg-red-100 text-red-800',
                            'trancado' => 'bg-yellow-100 text-yellow-800',
                            default    => 'bg-gray-100 text-gray-800',
                        };
                    @endphp

                    <p><strong>Status:</strong>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sStatusPessoa }}">
                            {{ ucfirst($aAluno->status) }}
                        </span>
                    </p>

                    <p><strong>Matrícula:</strong>
                        {{ optional($aAluno->data_matricula)->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
            </div>
        </div>
        @php
            $aTurmasAluno      = $aAluno->turmas->where('pivot.papel', 'aluno');
            $aTurmasProfessor  = $aAluno->turmas->where('pivot.papel', 'professor');
        @endphp

        <div class="mt-6 border-t pt-6">
            <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                </svg>
                Turmas
            </h4>

            @if($aTurmasAluno->count() || $aTurmasProfessor->count())
                @if($aTurmasAluno->count())
                    <div class="mb-4">
                        <div class="text-sm font-semibold text-gray-600 mb-2">Como Aluno</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($aTurmasAluno as $aTurma)
                                <span class="px-3 py-1 bg-bordo-dark/10 text-bordo-dark rounded-full text-sm font-medium">
                                    {{ $aTurma->nome }}
                                    <span class="text-bordo-dark/70 ml-1">
                                        ({{ $aTurma->dias_semana_formatados }} • {{ $aTurma->horario_inicio->format('H:i') }})
                                    </span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($aTurmasProfessor->count())
                    <div>
                        <div class="text-sm font-semibold text-gray-600 mb-2">Como Professor</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($aTurmasProfessor as $aTurma)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                    {{ $aTurma->nome }}
                                    <span class="text-gray-500 ml-1">
                                        ({{ $aTurma->dias_semana_formatados }} • {{ $aTurma->horario_inicio->format('H:i') }})
                                    </span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <p class="text-gray-dark/70 italic">Nenhuma turma associada.</p>
            @endif
        </div>

        @php($role = auth()->user()?->role)
        @if(in_array($role, ['admin','professor']))
            <div class="mt-6 border-t pt-6 text-right">
                <a href="{{ route('alunos.edit', $aAluno->id) }}"
                class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Editar {{ $sTituloPagina }}</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
