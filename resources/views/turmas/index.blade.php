@extends('layouts.app')

@section('title', 'Turmas')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    turmaToDelete: null, 
    turmaNome: '',
    deleteForm: null 
}">
    <div class="flex justify-between items-center mb-6 animate-fade-in">
        <h2 class="text-3xl font-bold text-gray-dark">Gestão de Turmas</h2>
        <div class="flex space-x-4">
            <a href="{{ route('turmas.trash') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Lixeira</span>
            </a>
            <a href="{{ route('turmas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nova Turma</span>
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
        <h3 class="text-xl font-semibold text-gray-dark mb-4">Filtrar Turmas</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-dark mb-1">Buscar por Nome/Instrutor</label>
                <input type="text" id="search" placeholder="Ex: Jiu-Jitsu Iniciante" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
            </div>
            <div>
                <label for="modalidade" class="block text-sm font-medium text-gray-dark mb-1">Modalidade</label>
                <select id="modalidade" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                    <option value="">Todas</option>
                    <option value="gi">Gi</option>
                    <option value="no-gi">No-Gi</option>
                    <option value="mma">MMA</option>
                    <option value="defesa-pessoal">Defesa Pessoal</option>
                </select>
            </div>
            <div>
                <label for="nivel" class="block text-sm font-medium text-gray-dark mb-1">Nível</label>
                <select id="nivel" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                    <option value="">Todos</option>
                    <option value="iniciante">Iniciante</option>
                    <option value="intermediario">Intermediário</option>
                    <option value="avancado">Avançado</option>
                    <option value="misto">Misto</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-dark mb-1">Status</label>
                <select id="status" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                    <option value="">Todos</option>
                    <option value="ativa">Ativa</option>
                    <option value="inativa">Inativa</option>
                    <option value="pausada">Pausada</option>
                </select>
            </div>
        </div>
        <div class="mt-6 text-right">
            <button class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
                Aplicar Filtros
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in animate-delay-300">
        @forelse ($turmas as $turma)
        <div class="bg-white rounded-xl shadow-lg border border-gray-light overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-bordo-dark to-bordo-hover p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">{{ $turma->nome }}</h3>
                        <div class="flex items-center space-x-2">
                            @php
                                $modalidadeIcon = '';
                                $modalidadeColor = '';
                                switch ($turma->modalidade) {
                                    case 'gi': 
                                        $modalidadeIcon = '🥋'; 
                                        $modalidadeColor = 'bg-blue-100 text-blue-800'; 
                                        break;
                                    case 'no-gi': 
                                        $modalidadeIcon = '🤼'; 
                                        $modalidadeColor = 'bg-purple-100 text-purple-800'; 
                                        break;
                                    case 'mma': 
                                        $modalidadeIcon = '🥊'; 
                                        $modalidadeColor = 'bg-red-100 text-red-800'; 
                                        break;
                                    case 'defesa-pessoal': 
                                        $modalidadeIcon = '🛡️'; 
                                        $modalidadeColor = 'bg-green-100 text-green-800'; 
                                        break;
                                }
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $modalidadeColor }}">
                                {{ $modalidadeIcon }} {{ ucfirst(str_replace('-', ' ', $turma->modalidade)) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColor = '';
                            switch ($turma->status) {
                                case 'ativa': $statusColor = 'bg-green-500'; break;
                                case 'inativa': $statusColor = 'bg-red-500'; break;
                                case 'pausada': $statusColor = 'bg-yellow-500'; break;
                            }
                        @endphp
                        <div class="w-3 h-3 rounded-full {{ $statusColor }}"></div>
                        <span class="text-xs text-white/80 mt-1 block">{{ ucfirst($turma->status) }}</span>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-3 mb-4">
                    <div class="flex items-center text-sm text-gray-dark">
                        <svg class="w-4 h-4 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <strong>Instrutor:</strong> {{ $turma->instrutor }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-dark">
                        <svg class="w-4 h-4 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong>Horário:</strong> {{ $turma->horario_inicio->format('H:i') }} - {{ $turma->horario_fim->format('H:i') }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-dark">
                        <svg class="w-4 h-4 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <strong>Dias:</strong> {{ $turma->dias_semana_formatados }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-dark">
                        @php
                            $nivelColor = '';
                            switch ($turma->nivel) {
                                case 'iniciante': $nivelColor = 'bg-green-100 text-green-800'; break;
                                case 'intermediario': $nivelColor = 'bg-yellow-100 text-yellow-800'; break;
                                case 'avancado': $nivelColor = 'bg-red-100 text-red-800'; break;
                                case 'misto': $nivelColor = 'bg-blue-100 text-blue-800'; break;
                            }
                        @endphp
                        <svg class="w-4 h-4 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $nivelColor }}">
                            {{ ucfirst($turma->nivel) }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-dark">Ocupação</span>
                        <span class="text-sm text-gray-dark">{{ $turma->numero_alunos }}/{{ $turma->capacidade_maxima }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $ocupacaoColor = '';
                            if ($turma->ocupacao_percentual <= 50) {
                                $ocupacaoColor = 'bg-green-500';
                            } elseif ($turma->ocupacao_percentual <= 80) {
                                $ocupacaoColor = 'bg-yellow-500';
                            } else {
                                $ocupacaoColor = 'bg-red-500';
                            }
                        @endphp
                        <div class="{{ $ocupacaoColor }} h-2 rounded-full transition-all duration-300" style="width: {{ $turma->ocupacao_percentual }}%"></div>
                    </div>
                    <span class="text-xs text-gray-dark/70 mt-1 block">{{ $turma->ocupacao_percentual }}% ocupada</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-light">
                    <div class="flex space-x-2">
                        <a href="{{ route('turmas.show', $turma->id) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                title="Ver Detalhes">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('turmas.alunos', $turma->id) }}" class="text-green-600 hover:text-green-800 text-sm font-medium" title="Gerenciar Alunos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('turmas.edit', $turma->id) }}" class="text-bordo-dark hover:text-bordo-hover text-sm font-medium" title="Editar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <form action="{{ route('turmas.destroy', $turma->id) }}" method="POST" class="inline-block" id="delete-form-{{ $turma->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                title="Excluir"
                                @click="
                                    turmaToDelete = {{ $turma->id }};
                                    turmaNome = '{{ $turma->nome }}';
                                    deleteForm = document.getElementById('delete-form-{{ $turma->id }}');
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-dark mb-2">Nenhuma turma encontrada</h3>
                <p class="text-gray-dark/70 mb-4">Comece criando sua primeira turma para organizar os treinos.</p>
                <a href="{{ route('turmas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Criar Primeira Turma</span>
                </a>
            </div>
        </div>
        @endforelse
    </div>
    @if($turmas->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $turmas->links() }}
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
                                Tem certeza que deseja enviar a turma <strong x-text="turmaNome"></strong> para a lixeira? 
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
</div>
@endsection