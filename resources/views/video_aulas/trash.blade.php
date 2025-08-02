@extends('layouts.app')

@section('title', 'Lixeira - Vídeo Aulas')

@section('content')
<div class="flex justify-between items-center mb-6 animate-fade-in">
    <div>
        <h2 class="text-3xl font-bold text-gray-dark">Lixeira - Vídeo Aulas</h2>
        <p class="text-gray-dark/70 mt-1">Vídeo aulas excluídas que podem ser restauradas</p>
    </div>
    <a href="{{ route('video-aulas.index') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span>Voltar às Vídeo Aulas</span>
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in animate-delay-200">
    @forelse ($videoAulas as $videoAula)
    <div class="bg-white rounded-xl shadow-lg border border-gray-light overflow-hidden opacity-75 hover:opacity-100 transition-opacity duration-300">
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">{{ $videoAula->titulo }}</h3>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-1.25-3M15 10V5a3 3 0 00-3-3H9a3 3 0 00-3 3v5m6 4h.01M12 12h.01"></path>
                    </svg>
                    <strong>ID YouTube:</strong> {{ $videoAula->youtube_id }}
                </div>
                
                <div class="flex items-center text-sm text-gray-dark">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Duração:</strong> {{ $videoAula->duracao_formatada }}
                </div>
                
                <div class="flex items-center text-sm text-gray-dark">
                    @php
                        $modalidadeColor = '';
                        switch ($videoAula->modalidade) {
                            case 'gi': $modalidadeColor = 'bg-blue-100 text-blue-800'; break;
                            case 'no-gi': $modalidadeColor = 'bg-purple-100 text-purple-800'; break;
                            case 'mma': $modalidadeColor = 'bg-red-100 text-red-800'; break;
                            case 'defesa-pessoal': $modalidadeColor = 'bg-green-100 text-green-800'; break;
                        }
                    @endphp
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $modalidadeColor }}">
                        {{ ucfirst(str_replace('-', ' ', $videoAula->modalidade)) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-center pt-4 border-t border-gray-light">
                <form action="{{ route('video-aulas.restore', $videoAula->id) }}" method="POST" class="inline-block">
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
            <p class="text-gray-dark/70">Não há vídeo aulas excluídas no momento.</p>
        </div>
    </div>
    @endforelse
</div>

@if($videoAulas->hasPages())
<div class="mt-8 flex justify-center">
    {{ $videoAulas->links() }}
</div>
@endif
@endsection
