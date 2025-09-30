@extends('layouts.app')

@section('title', 'Editar Vídeo Aula - Sistema de Gestão')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Editar Vídeo Aula: <span class="text-bordo-dark">{{ $oVideoAula->titulo }}</span></h2>
            <p class="text-gray-dark/70 mt-1">Atualize as informações da vídeo aula.</p>
        </div>
        <a href="{{ route('video-aulas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Voltar</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            <h4 class="font-semibold mb-2">Corrija os seguintes erros:</h4>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-light animate-fade-in animate-delay-200">
        <form action="{{ route('video-aulas.update', $oVideoAula->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-1.25-3M15 10V5a3 3 0 00-3-3H9a3 3 0 00-3 3v5m6 4h.01M12 12h.01"></path>
                    </svg>
                    Detalhes da Vídeo Aula
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-dark mb-2">
                            Título da Vídeo Aula <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            value="{{ old('titulo', $oVideoAula->titulo) }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('titulo') border-red-500 @enderror"
                            placeholder="Ex: Fundamentos do Armlock"
                            required
                        >
                        @error('titulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="url_youtube" class="block text-sm font-medium text-gray-dark mb-2">
                            URL do Vídeo (YouTube) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="url" 
                            id="url_youtube" 
                            name="url_youtube" 
                            value="{{ old('url_youtube', 'https://www.youtube.com/watch?v=' . $oVideoAula->youtube_id) }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('url_youtube') border-red-500 @enderror"
                            placeholder="Ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                            required
                        >
                        @error('url_youtube')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="duracao_minutos" class="block text-sm font-medium text-gray-dark mb-2">
                            Duração (minutos)
                        </label>
                        <input 
                            type="number" 
                            id="duracao_minutos" 
                            name="duracao_minutos" 
                            value="{{ old('duracao_minutos', $oVideoAula->duracao_minutos) }}"
                            min="1" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('duracao_minutos') border-red-500 @enderror"
                            placeholder="Ex: 15"
                        >
                        <p class="mt-1 text-xs text-gray-dark/70">Apenas o número de minutos (opcional)</p>
                        @error('duracao_minutos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <label for="descricao" class="block text-sm font-medium text-gray-dark mb-2">
                        Descrição
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('descricao') border-red-500 @enderror"
                        placeholder="Uma breve descrição sobre o conteúdo da vídeo aula."
                    >{{ old('descricao', $oVideoAula->descricao) }}</textarea>
                    <p class="mt-1 text-xs text-gray-dark/70">Máximo de 1000 caracteres (opcional)</p>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Categorização
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="modalidade" class="block text-sm font-medium text-gray-dark mb-2">
                            Modalidade <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="modalidade" 
                            name="modalidade" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('modalidade') border-red-500 @enderror"
                            required
                        >
                            <option value="">Selecione uma modalidade</option>
                            <option value="gi" {{ old('modalidade', $oVideoAula->modalidade) == 'gi' ? 'selected' : '' }}>🥋 Gi (Kimono)</option>
                            <option value="no-gi" {{ old('modalidade', $oVideoAula->modalidade) == 'no-gi' ? 'selected' : '' }}>🤼 No-Gi (Sem Kimono)</option>
                            <option value="mma" {{ old('modalidade', $oVideoAula->modalidade) == 'mma' ? 'selected' : '' }}>🥊 MMA</option>
                            <option value="defesa-pessoal" {{ old('modalidade', $oVideoAula->modalidade) == 'defesa-pessoal' ? 'selected' : '' }}>🛡️ Defesa Pessoal</option>
                        </select>
                        @error('modalidade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nivel" class="block text-sm font-medium text-gray-dark mb-2">
                            Nível <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="nivel" 
                            name="nivel" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('nivel') border-red-500 @enderror"
                            required
                        >
                            <option value="">Selecione o nível</option>
                            <option value="iniciante" {{ old('nivel', $oVideoAula->nivel) == 'iniciante' ? 'selected' : '' }}>🟢 Iniciante</option>
                            <option value="intermediario" {{ old('nivel', $oVideoAula->nivel) == 'intermediario' ? 'selected' : '' }}>🟡 Intermediário</option>
                            <option value="avancado" {{ old('nivel', $oVideoAula->nivel) == 'avancado' ? 'selected' : '' }}>🔴 Avançado</option>
                            <option value="misto" {{ old('nivel', $oVideoAula->nivel) == 'misto' ? 'selected' : '' }}>🔵 Misto</option>
                        </select>
                        @error('nivel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-light">
                <a href="{{ route('video-aulas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Salvar Alterações</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
