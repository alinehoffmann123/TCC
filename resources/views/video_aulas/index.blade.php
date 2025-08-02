@extends('layouts.app')

@section('title', 'Vídeo Aulas')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    videoAulaToDelete: null, 
    videoAulaTitulo: '',
    deleteForm: null,

    showVideoModal: false,
    currentVideoEmbedUrl: '',
    loadingVideo: false,

    openVideoModal: function(youtubeId) {
        this.currentVideoEmbedUrl = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
        this.showVideoModal = true;
    },
    closeVideoModal: function() {
        this.showVideoModal = false;
        this.currentVideoEmbedUrl = ''; // Stop video playback
    },
    applyFilters: function() {
        const search = document.getElementById('search').value;
        const modalidade = document.getElementById('modalidade').value;
        const nivel = document.getElementById('nivel').value;
        
        let url = '{{ route('video-aulas.index') }}';
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (modalidade) params.append('modalidade', modalidade);
        if (nivel) params.append('nivel', nivel);
        
        window.location.href = url + '?' + params.toString();
    }
}">
    <div class="flex justify-between items-center mb-6 animate-fade-in">
        <h2 class="text-3xl font-bold text-gray-dark">Gestão de Vídeo Aulas</h2>
        <div class="flex space-x-4">
            <a href="{{ route('video-aulas.trash') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Lixeira</span>
            </a>
            <a href="{{ route('video-aulas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nova Vídeo Aula</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            {{ session('error') }}
        </div>
    @endif
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light mb-6 animate-fade-in animate-delay-200">
        <h3 class="text-xl font-semibold text-gray-dark mb-4">Filtrar Vídeo Aulas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-dark mb-1">Buscar por Título/Descrição</label>
                <input type="text" id="search" name="search" placeholder="Ex: Defesa Pessoal" 
                       class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark"
                       value="{{ request('search') }}">
            </div>
            <div>
                <label for="modalidade" class="block text-sm font-medium text-gray-dark mb-1">Modalidade</label>
                <select id="modalidade" name="modalidade" 
                        class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                    <option value="">Todas</option>
                    <option value="gi" {{ request('modalidade') == 'gi' ? 'selected' : '' }}>Gi</option>
                    <option value="no-gi" {{ request('modalidade') == 'no-gi' ? 'selected' : '' }}>No-Gi</option>
                    <option value="mma" {{ request('modalidade') == 'mma' ? 'selected' : '' }}>MMA</option>
                    <option value="defesa-pessoal" {{ request('modalidade') == 'defesa-pessoal' ? 'selected' : '' }}>Defesa Pessoal</option>
                </select>
            </div>
            <div>
                <label for="nivel" class="block text-sm font-medium text-gray-dark mb-1">Nível</label>
                <select id="nivel" name="nivel" 
                        class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                    <option value="">Todos</option>
                    <option value="iniciante" {{ request('nivel') == 'iniciante' ? 'selected' : '' }}>Iniciante</option>
                    <option value="intermediario" {{ request('nivel') == 'intermediario' ? 'selected' : '' }}>Intermediário</option>
                    <option value="avancado" {{ request('nivel') == 'avancado' ? 'selected' : '' }}>Avançado</option>
                    <option value="misto" {{ request('nivel') == 'misto' ? 'selected' : '' }}>Misto</option>
                </select>
            </div>
        </div>
        <div class="mt-6 text-right">
            <button type="button" @click="applyFilters()" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
                Aplicar Filtros
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in animate-delay-300">
        @forelse ($videoAulas as $videoAula)
        <div class="bg-white rounded-xl shadow-lg border border-gray-light overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="relative w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                <img src="{{ $videoAula->thumbnail_url }}" alt="{{ $videoAula->titulo }}" class="w-full h-full object-cover">
                <button @click="openVideoModal('{{ $videoAula->youtube_id }}')" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 hover:bg-opacity-75 transition-opacity duration-200">
                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <h3 class="text-xl font-bold text-gray-dark mb-2">{{ $videoAula->titulo }}</h3>
                <p class="text-sm text-gray-dark/70 mb-3 line-clamp-2">{{ $videoAula->descricao ?? 'Sem descrição.' }}</p>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    @php
                        $modalidadeColor = '';
                        switch ($videoAula->modalidade) {
                            case 'gi': $modalidadeColor = 'bg-blue-100 text-blue-800'; break;
                            case 'no-gi': $modalidadeColor = 'bg-purple-100 text-purple-800'; break;
                            case 'mma': $modalidadeColor = 'bg-red-100 text-red-800'; break;
                            case 'defesa-pessoal': $modalidadeColor = 'bg-green-100 text-green-800'; break;
                        }
                        $nivelColor = '';
                        switch ($videoAula->nivel) {
                            case 'iniciante': $nivelColor = 'bg-green-100 text-green-800'; break;
                            case 'intermediario': $nivelColor = 'bg-yellow-100 text-yellow-800'; break;
                            case 'avancado': $nivelColor = 'bg-red-100 text-red-800'; break;
                            case 'misto': $nivelColor = 'bg-blue-100 text-blue-800'; break;
                        }
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $modalidadeColor }}">
                        {{ ucfirst(str_replace('-', ' ', $videoAula->modalidade)) }}
                    </span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $nivelColor }}">
                        {{ ucfirst($videoAula->nivel) }}
                    </span>
                    @if($videoAula->duracao_minutos)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        {{ $videoAula->duracao_formatada }}
                    </span>
                    @endif
                </div>
                <div class="flex justify-end space-x-2 pt-4 border-t border-gray-light">
                    <a href="{{ route('video-aulas.edit', $videoAula->id) }}" class="text-bordo-dark hover:text-bordo-hover text-sm font-medium" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('video-aulas.destroy', $videoAula->id) }}" method="POST" class="inline-block" id="delete-form-{{ $videoAula->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                title="Excluir"
                                @click="
                                    videoAulaToDelete = {{ $videoAula->id }};
                                    videoAulaTitulo = '{{ $videoAula->titulo }}';
                                    deleteForm = document.getElementById('delete-form-{{ $videoAula->id }}');
                                    showDeleteModal = true;
                                ">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white p-12 rounded-xl shadow-lg border border-gray-light text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4-4m0 0l-4-4m4 4H7a4 4 0 000 8h10m-4-4l4 4m0 0l-4 4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-dark mb-2">Nenhuma vídeo aula encontrada</h3>
                <p class="text-gray-dark/70 mb-4">Comece cadastrando sua primeira vídeo aula para os alunos.</p>
                <a href="{{ route('video-aulas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Cadastrar Primeira Vídeo Aula</span>
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if($videoAulas->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $videoAulas->links() }}
    </div>
    @endif

    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Confirmar Exclusão
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Tem certeza que deseja enviar a vídeo aula <strong x-text="videoAulaTitulo"></strong> para a lixeira? 
                                Esta ação pode ser desfeita posteriormente.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="deleteForm.submit(); showDeleteModal = false;"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Confirmar Exclusão
                    </button>
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bordo-dark sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div x-show="showVideoModal" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="closeVideoModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0 w-full">
            <div x-show="showVideoModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="p-4">
                    <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <iframe 
                            :src="currentVideoEmbedUrl" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen 
                            class="absolute top-0 left-0 w-full h-full">
                        </iframe>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="closeVideoModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bordo-dark sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
