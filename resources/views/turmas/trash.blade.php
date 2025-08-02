@extends('layouts.app')

@section('title', 'Lixeira - Turmas')

@section('content')
<div class="flex justify-between items-center mb-6 animate-fade-in">
    <div>
        <h2 class="text-3xl font-bold text-gray-dark">Lixeira - Turmas</h2>
        <p class="text-gray-dark/70 mt-1">Turmas excluídas que podem ser restauradas</p>
    </div>
    <a href="{{ route('turmas.index') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span>Voltar às Turmas</span>
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in animate-delay-200">
    @forelse ($turmas as $turma)
    <div class="bg-white rounded-xl shadow-lg border border-gray-light overflow-hidden opacity-75 hover:opacity-100 transition-opacity duration-300">
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">{{ $turma->nome }}</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        🗑️ Excluída
                    </span>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="space-y-3 mb-4">
                <div class="flex items-center text-sm text-gray-dark">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <strong>Instrutor:</strong> {{ $turma->instrutor }}
                </div>
                
                <div class="flex items-center text-sm text-gray-dark">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Horário:</strong> {{ $turma->horario_inicio->format('H:i') }} - {{ $turma->horario_fim->format('H:i') }}
                </div>
                
                <div class="flex items-center text-sm text-gray-dark">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <strong>Dias:</strong> {{ $turma->dias_semana_formatados }}
                </div>
            </div>
            <div class="flex justify-center pt-4 border-t border-gray-light">
                <form action="{{ route('turmas.restore', $turma->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Restaurar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white p-12 rounded-xl shadow-lg border border-gray-light text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Lixeira vazia</h3>
            <p class="text-gray-dark/70">Não há turmas excluídas no momento.</p>
        </div>
    </div>
    @endforelse
</div>

@if($turmas->hasPages())
<div class="mt-8 flex justify-center">
    {{ $turmas->links() }}
</div>
@endif
@endsection
