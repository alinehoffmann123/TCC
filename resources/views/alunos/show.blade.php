@extends('layouts.app')

@section('title', 'Detalhes do Aluno - ' . $aluno->nome)

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Detalhes do Aluno: <span class="text-bordo-dark">{{ $aluno->nome }}</span></h2>
            <p class="text-gray-dark/70 mt-1">Informações completas sobre o aluno.</p>
        </div>
        <a href="{{ route('alunos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Voltar aos Alunos</span>
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
                    <p><strong>Nome:</strong> {{ $aluno->nome }}</p>
                    <p><strong>Email:</strong> {{ $aluno->email }}</p>
                    <p><strong>Telefone:</strong> {{ $aluno->telefone ?? 'N/A' }}</p>
                    <p><strong>Nascimento:</strong> {{ $aluno->data_nascimento ? $aluno->data_nascimento->format('d/m/Y') : 'N/A' }}</p>
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
                    <p><strong>Faixa:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @php
                            switch ($aluno->faixa) {
                                case 'branca': echo 'bg-gray-200 text-gray-dark'; break;
                                case 'azul': echo 'bg-blue-100 text-blue-800'; break;
                                case 'roxa': echo 'bg-purple-100 text-purple-800'; break;
                                case 'marrom': echo 'bg-yellow-100 text-yellow-800'; break;
                                case 'preta': echo 'bg-black text-white'; break;
                                default: echo 'bg-gray-200 text-gray-dark'; break;
                            }
                        @endphp
                    ">{{ ucfirst($aluno->faixa) }}</span></p>
                    <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @php
                            switch ($aluno->status) {
                                case 'ativo': echo 'bg-green-100 text-green-800'; break;
                                case 'inativo': echo 'bg-red-100 text-red-800'; break;
                                case 'trancado': echo 'bg-yellow-100 text-yellow-800'; break;
                                default: echo 'bg-gray-100 text-gray-800'; break;
                            }
                        @endphp
                    ">{{ ucfirst($aluno->status) }}</span></p>
                    <p><strong>Matrícula:</strong> {{ $aluno->data_matricula->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
        <div class="mt-6 border-t pt-6">
            <h4 class="text-lg font-semibold text-bordo-dark mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                </svg>
                Turmas Atuais
            </h4>
            @if($aluno->turmas->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($aluno->turmas as $turma)
                        <span class="px-3 py-1 bg-bordo-dark/10 text-bordo-dark rounded-full text-sm font-medium">
                            {{ $turma->nome }}
                            <span class="text-bordo-dark/70 ml-1">
                                ({{ $turma->dias_semana_formatados }} - {{ $turma->horario_inicio->format('H:i') }})
                            </span>
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-dark/70 italic">Nenhuma turma associada.</p>
            @endif
        </div>
        <div class="mt-6 border-t pt-6 text-right">
            <a href="{{ route('alunos.edit', $aluno->id) }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Editar Aluno</span>
            </a>
        </div>
    </div>
</div>
@endsection
