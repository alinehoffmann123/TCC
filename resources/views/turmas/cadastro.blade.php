@extends('layouts.app')

@section('title', 'Nova Turma - Sistema de Gestão')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Nova Turma</h2>
            <p class="text-gray-dark/70 mt-1">Cadastre uma nova turma para organizar os treinos</p>
        </div>
        <a href="{{ route('turmas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Voltar</span>
        </a>
    </div>

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
        <form action="{{ route('turmas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informações Básicas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-dark mb-2">
                            Nome da Turma <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="{{ old('nome') }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('nome') border-red-500 @enderror"
                            placeholder="Ex: Jiu-Jitsu Iniciante - Manhã"
                            required
                        >
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="instrutor_id" class="block text-sm font-medium text-gray-dark mb-2">
                            Instrutor <span class="text-red-500">*</span>
                        </label>

                        @if(($aProfessores ?? collect())->count() > 0)
                            <select
                                id="instrutor_id"
                                name="instrutor_id"
                                required
                                class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('instrutor_id') border-red-500 @enderror"
                            >
                                <option value="">Selecione um professor</option>
                                @foreach($aProfessores as $prof)
                                    <option value="{{ $prof->id }}" {{ old('instrutor_id') == $prof->id ? 'selected' : '' }}>
                                        {{ $prof->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instrutor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                Nenhum professor cadastrado ainda.
                                <a href="{{ route('alunos.create') }}" class="underline font-medium">Cadastrar professor</a>
                            </div>
                        @endif
                    </div>
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
                            <option value="gi" {{ old('modalidade') == 'gi' ? 'selected' : '' }}>Gi (Kimono)</option>
                            <option value="no-gi" {{ old('modalidade') == 'no-gi' ? 'selected' : '' }}>No-Gi (Sem Kimono)</option>
                            <option value="gracie" {{ old('modalidade') == 'gracie' ? 'selected' : '' }}>Gracie Jiu Jitsu</option>
                            <option value="luta-livre" {{ old('modalidade') == 'luta-livre' ? 'selected' : '' }}>Luta Livre</option>
                            <option value="combate" {{ old('modalidade') == 'combate' ? 'selected' : '' }}>Jiu Jitsu Combate</option>
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
                            <option value="iniciante" {{ old('nivel') == 'iniciante' ? 'selected' : '' }}>🟢 Iniciante</option>
                            <option value="intermediario" {{ old('nivel') == 'intermediario' ? 'selected' : '' }}>🟡 Intermediário</option>
                            <option value="avancado" {{ old('nivel') == 'avancado' ? 'selected' : '' }}>🔴 Avançado</option>
                            <option value="misto" {{ old('nivel') == 'misto' ? 'selected' : '' }}>🔵 Misto</option>
                        </select>
                        @error('nivel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Horários e Dias
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="horario_inicio" class="block text-sm font-medium text-gray-dark mb-2">
                            Horário de Início <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            id="horario_inicio" 
                            name="horario_inicio" 
                            value="{{ old('horario_inicio') }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('horario_inicio') border-red-500 @enderror"
                            required
                        >
                        @error('horario_inicio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="horario_fim" class="block text-sm font-medium text-gray-dark mb-2">
                            Horário de Fim <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            id="horario_fim" 
                            name="horario_fim" 
                            value="{{ old('horario_fim') }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('horario_fim') border-red-500 @enderror"
                            required
                        >
                        @error('horario_fim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-3">
                        Dias da Semana <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @php
                            $aDias = [
                                  'segunda' => 'Segunda'
                                , 'terca'   => 'Terça'
                                , 'quarta'  => 'Quarta'
                                , 'quinta'  => 'Quinta'
                                , 'sexta'   => 'Sexta'
                                , 'sabado'  => 'Sábado'
                                , 'domingo' => 'Domingo'
                            ];
                        @endphp
                        @foreach($aDias as $sValor => $sNome)
                        <label class="flex items-center p-3 border border-gray-light rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ in_array($sValor, old('dias_semana', [])) ? 'bg-bordo-dark/10 border-bordo-dark' : '' }}">
                            <input 
                                type="checkbox" 
                                name="dias_semana[]" 
                                value="{{ $sValor }}"
                                class="mr-2 text-bordo-dark focus:ring-bordo-dark border-gray-300 rounded"
                                {{ in_array($sValor, old('dias_semana', [])) ? 'checked' : '' }}
                            >
                            <span class="text-sm font-medium text-gray-dark">{{ $sNome }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('dias_semana')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configurações
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="capacidade_maxima" class="block text-sm font-medium text-gray-dark mb-2">
                            Capacidade Máxima <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="capacidade_maxima" 
                            name="capacidade_maxima" 
                            value="{{ old('capacidade_maxima', 20) }}"
                            min="1" 
                            max="50"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('capacidade_maxima') border-red-500 @enderror"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-dark/70">Número máximo de alunos na turma (1-50)</p>
                        @error('capacidade_maxima')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-dark mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('status') border-red-500 @enderror"
                            required
                        >
                            <option value="ativa" {{ old('status', 'ativa') == 'ativa' ? 'selected' : '' }}>🟢 Ativa</option>
                            <option value="inativa" {{ old('status') == 'inativa' ? 'selected' : '' }}>🔴 Inativa</option>
                            <option value="pausada" {{ old('status') == 'pausada' ? 'selected' : '' }}>🟡 Pausada</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Observações
                </h3>

                <div>
                    <label for="observacoes" class="block text-sm font-medium text-gray-dark mb-2">
                        Observações Adicionais
                    </label>
                    <textarea 
                        id="observacoes" 
                        name="observacoes" 
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('observacoes') border-red-500 @enderror"
                        placeholder="Informações adicionais sobre a turma, requisitos especiais, etc."
                    >{{ old('observacoes') }}</textarea>
                    <p class="mt-1 text-xs text-gray-dark/70">Máximo de 1000 caracteres</p>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-light">
                <a href="{{ route('turmas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Criar Turma</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validação em tempo real dos horários
document.getElementById('horario_inicio').addEventListener('change', function() {
    const sInicio = this.value;
    const sFimInput = document.getElementById('horario_fim');
    
    if (sInicio && sFimInput.value && sInicio >= sFimInput.value) {
        alert('O horário de início deve ser anterior ao horário de fim.');
        this.focus();
    }
});

document.getElementById('horario_fim').addEventListener('change', function() {
    const sFim = this.value;
    const sInicioInput = document.getElementById('horario_inicio');
    
    if (sInicioInput.value && sFim && sInicioInput.value >= sFim) {
        alert('O horário de fim deve ser posterior ao horário de início.');
        this.focus();
    }
});

// Validação dos dias da semana
document.addEventListener('DOMContentLoaded', function() {
    const oCheckbox = document.querySelectorAll('input[name="dias_semana[]"]');
    
    oCheckbox.forEach(aCheckbox => {
        aCheckbox.addEventListener('change', function() {
            const oCheckboxes = document.querySelectorAll('input[name="dias_semana[]"]:checked');
            if (oCheckboxes.length === 0) {
                alert('Selecione pelo menos um dia da semana.');
                this.checked = true;
            }
        });
    });
});
</script>
@endsection
