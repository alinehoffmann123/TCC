@extends('layouts.app')

@section('title', 'Cadastrar Pessoa')

@push('styles')
<style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
<div class="animate-fade-in" x-data="{ tipo: '{{ old('tipo','aluno') }}' }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">
                Cadastrar <span x-text="tipo === 'aluno' ? 'Aluno' : 'Professor'"></span>
            </h2>
            <p class="text-gray-dark/70 mt-1">Adicione um novo <span x-text="tipo"></span> ao sistema</p>
        </div>
        <a href="{{ route('alunos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Voltar</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md">
            <h4 class="font-semibold mb-2">Corrija os seguintes erros:</h4>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error) <li class="text-sm">{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-light">
        <form action="{{ route('alunos.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="tipo" :value="tipo">
            <div class="pb-2">
                <div class="inline-flex rounded-xl border border-gray-light overflow-hidden shadow-sm">
                    <button type="button" class="px-4 py-2 text-sm font-semibold"
                        :class="tipo==='aluno' ? 'bg-bordo-dark text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        @click="tipo='aluno'">🥋 Aluno</button>
                    <button type="button" class="px-4 py-2 text-sm font-semibold border-l border-gray-light"
                        :class="tipo==='professor' ? 'bg-bordo-dark text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        @click="tipo='professor'">👨‍🏫 Professor</button>
                </div>
                <p class="text-xs text-gray-dark/70 mt-2">Para Professor, faixa/turmas são opcionais — mas você também pode preencher.</p>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informações Pessoais
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-dark mb-2">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('nome') border-red-500 @enderror"
                            placeholder="Ex: João Silva Santos">
                        @error('nome') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-dark mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('email') border-red-500 @enderror"
                            placeholder="Ex: joao@email.com">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-dark mb-2">
                            Data de Nascimento
                        </label>
                        <input type="date" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('data_nascimento') border-red-500 @enderror">
                        @error('data_nascimento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-dark mb-2">
                            Telefone
                        </label>
                        <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('telefone') border-red-500 @enderror"
                            placeholder="Ex: (11) 99999-9999">
                        @error('telefone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.835 4.697..."/></svg>
                    Informações do Jiu-Jitsu
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">
                            Faixa inicial
                            </label>
                            <select name="faixa_inicial_id"
                                    class="w-full px-3 py-2 border rounded-md"
                                    required>
                            <option value="">Selecione...</option>
                            @foreach($aFaixas as $aFaixa)
                                <option value="{{ $aFaixa->id }}"
                                {{ old('faixa_inicial_id', $aAluno->faixa_inicial_id ?? null) == $aFaixa->id ? 'selected' : '' }}>
                                {{ $aFaixa->ordem }} — {{ ucfirst($aFaixa->nome) }}
                                </option>
                            @endforeach
                            </select>
                        @error('faixa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-dark mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('status') border-red-500 @enderror">
                            <option value="ativo"    {{ old('status','ativo')=='ativo' ? 'selected' : '' }}>🟢 Ativo</option>
                            <option value="inativo"  {{ old('status')=='inativo' ? 'selected' : '' }}>🔴 Inativo</option>
                            <option value="trancado" {{ old('status')=='trancado' ? 'selected' : '' }}>🟡 Trancado</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="data_matricula" class="block text-sm font-medium text-gray-dark mb-2">
                            Data de Matrícula <span class="text-red-500" x-show="tipo==='aluno'">*</span>
                        </label>
                        <input type="date" id="data_matricula" name="data_matricula"
                            value="{{ old('data_matricula', date('Y-m-d')) }}" :required="tipo==='aluno'"
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('data_matricula') border-red-500 @enderror">
                        @error('data_matricula') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div x-cloak>
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                    </svg>
                    Turmas (Opcional)
                </h3>

                @if($aTurmas->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($aTurmas as $aTurma)
                        <label class="flex items-start p-4 border border-gray-light rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ in_array($aTurma->id, old('turmas', [])) ? 'bg-bordo-dark/10 border-bordo-dark' : '' }}">
                            <input type="checkbox" name="turmas[]" value="{{ $aTurma->id }}"
                                class="mt-1 mr-3 text-bordo-dark focus:ring-bordo-dark border-gray-300 rounded"
                                {{ in_array($aTurma->id, old('turmas', [])) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="font-medium text-gray-dark">{{ $aTurma->nome }}</div>
                                <div class="text-sm text-gray-dark/70">
                                    {{ $aTurma->instrutor }} • {{ $aTurma->dias_semana_formatados }}
                                </div>
                                <div class="text-sm text-gray-dark/70">
                                    {{ $aTurma->horario_inicio->format('H:i') }} - {{ $aTurma->horario_fim->format('H:i') }}
                                </div>
                                <div class="flex items-center mt-2">
                                    @php
                                        $sModalidadeIcone = match ($aTurma->modalidade) {
                                            'gi' => '🥋', 'no-gi' => '🤼', 'mma' => '🥊', 'defesa-pessoal' => '🛡️', default => '🏷️'
                                        };
                                    @endphp
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full mr-2">
                                        {{ $sModalidadeIcone }} {{ ucfirst(str_replace('-', ' ', $aTurma->modalidade)) }}
                                    </span>
                                    <span class="text-xs text-gray-dark/70">
                                        {{ $aTurma->numero_alunos }}/{{ $aTurma->capacidade_maxima }} alunos
                                    </span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <p class="mt-3 text-sm text-gray-dark/70">
                💡    Para <strong>Aluno</strong>, turmas em que será matriculado. Para <strong>Professor</strong>, turmas que irá ministrar.
                    </p>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path></svg>
                        <h4 class="text-lg font-medium text-gray-dark mb-2">Nenhuma turma disponível</h4>
                        <p class="text-gray-dark/70 mb-4">Crie algumas turmas primeiro para poder matricular alunos.</p>
                        <a href="{{ route('turmas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Criar Turma</span>
                        </a>
                    </div>
                @endif
                @error('turmas') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-light">
                <a href="{{ route('alunos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">Cancelar</a>
                <button type="submit" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Cadastrar</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
