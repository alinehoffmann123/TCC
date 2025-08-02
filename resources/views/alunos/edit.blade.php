@extends('layouts.app')

@section('title', 'Editar Aluno')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Editar Aluno: {{ $aluno->nome }}</h2>
            <p class="text-gray-dark/70 mt-1">Atualize as informações do aluno</p>
        </div>
        <a href="{{ route('alunos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
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
        <form action="{{ route('alunos.update', $aluno->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
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
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="{{ old('nome', $aluno->nome) }}" 
                            required 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('nome') border-red-500 @enderror"
                            placeholder="Ex: João Silva Santos"
                        >
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-dark mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $aluno->email) }}" 
                            required 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('email') border-red-500 @enderror"
                            placeholder="Ex: joao@email.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-dark mb-2">
                            Data de Nascimento
                        </label>
                        <input 
                            type="date" 
                            id="data_nascimento" 
                            name="data_nascimento" 
                            value="{{ old('data_nascimento', $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : '') }}" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('data_nascimento') border-red-500 @enderror"
                        >
                        @error('data_nascimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-dark mb-2">
                            Telefone
                        </label>
                        <input 
                            type="text" 
                            id="telefone" 
                            name="telefone" 
                            value="{{ old('telefone', $aluno->telefone) }}" 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('telefone') border-red-500 @enderror"
                            placeholder="Ex: (11) 99999-9999"
                        >
                        @error('telefone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-light pb-6">
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Informações do Jiu-Jitsu
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="faixa" class="block text-sm font-medium text-gray-dark mb-2">
                            Faixa <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="faixa" 
                            name="faixa" 
                            required 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('faixa') border-red-500 @enderror"
                        >
                            <option value="">Selecione a Faixa</option>
                            <option value="branca" {{ old('faixa', $aluno->faixa) == 'branca' ? 'selected' : '' }}>⚪ Branca</option>
                            <option value="azul" {{ old('faixa', $aluno->faixa) == 'azul' ? 'selected' : '' }}>🔵 Azul</option>
                            <option value="roxa" {{ old('faixa', $aluno->faixa) == 'roxa' ? 'selected' : '' }}>🟣 Roxa</option>
                            <option value="marrom" {{ old('faixa', $aluno->faixa) == 'marrom' ? 'selected' : '' }}>🟤 Marrom</option>
                            <option value="preta" {{ old('faixa', $aluno->faixa) == 'preta' ? 'selected' : '' }}>⚫ Preta</option>
                        </select>
                        @error('faixa')
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
                            required 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('status') border-red-500 @enderror"
                        >
                            <option value="ativo" {{ old('status', $aluno->status) == 'ativo' ? 'selected' : '' }}>🟢 Ativo</option>
                            <option value="inativo" {{ old('status', $aluno->status) == 'inativo' ? 'selected' : '' }}>🔴 Inativo</option>
                            <option value="trancado" {{ old('status', $aluno->status) == 'trancado' ? 'selected' : '' }}>🟡 Trancado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="data_matricula" class="block text-sm font-medium text-gray-dark mb-2">
                            Data de Matrícula <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="data_matricula" 
                            name="data_matricula" 
                            value="{{ old('data_matricula', $aluno->data_matricula ? $aluno->data_matricula->format('Y-m-d') : '') }}" 
                            required 
                            class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('data_matricula') border-red-500 @enderror"
                        >
                        @error('data_matricula')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                    </svg>
                    Turmas
                </h3>

                @if($turmas->count() > 0)
                    @if($aluno->turmas->count() > 0)
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Turmas Atuais do Aluno
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($aluno->turmas as $turmaAtual)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        {{ $turmaAtual->nome }}
                                        <span class="text-blue-600 ml-1">
                                            ({{ $turmaAtual->dias_semana_formatados }} - {{ $turmaAtual->horario_inicio->format('H:i') }})
                                        </span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $turmasAtuaisIds = $aluno->turmas->pluck('id')->toArray();
                            $turmasSelecionadas = old('turmas', $turmasAtuaisIds);
                        @endphp
                        
                        @foreach($turmas as $turma)
                        <label class="flex items-start p-4 border border-gray-light rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ in_array($turma->id, $turmasSelecionadas) ? 'bg-bordo-dark/10 border-bordo-dark' : '' }}">
                            <input 
                                type="checkbox" 
                                name="turmas[]" 
                                value="{{ $turma->id }}"
                                class="mt-1 mr-3 text-bordo-dark focus:ring-bordo-dark border-gray-300 rounded"
                                {{ in_array($turma->id, $turmasSelecionadas) ? 'checked' : '' }}
                            >
                            <div class="flex-1">
                                <div class="font-medium text-gray-dark">{{ $turma->nome }}</div>
                                <div class="text-sm text-gray-dark/70">
                                    {{ $turma->instrutor }} • {{ $turma->dias_semana_formatados }}
                                </div>
                                <div class="text-sm text-gray-dark/70">
                                    {{ $turma->horario_inicio->format('H:i') }} - {{ $turma->horario_fim->format('H:i') }}
                                </div>
                                <div class="flex items-center mt-2">
                                    @php
                                        $modalidadeIcon = '';
                                        switch ($turma->modalidade) {
                                            case 'gi': $modalidadeIcon = '🥋'; break;
                                            case 'no-gi': $modalidadeIcon = '🤼'; break;
                                            case 'mma': $modalidadeIcon = '🥊'; break;
                                            case 'defesa-pessoal': $modalidadeIcon = '🛡️'; break;
                                        }
                                    @endphp
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full mr-2">
                                        {{ $modalidadeIcon }} {{ ucfirst(str_replace('-', ' ', $turma->modalidade)) }}
                                    </span>
                                    <span class="text-xs text-gray-dark/70">
                                        {{ $turma->numero_alunos }}/{{ $turma->capacidade_maxima }} alunos
                                    </span>
                                    @if($turma->numero_alunos >= $turma->capacidade_maxima && !in_array($turma->id, $turmasAtuaisIds))
                                        <span class="text-xs text-red-600 ml-2 font-medium">LOTADA</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">💡 Dicas importantes:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Desmarque as turmas das quais deseja remover o aluno</li>
                                    <li>Marque as novas turmas nas quais deseja matricular o aluno</li>
                                    <li>Turmas lotadas só podem ser selecionadas se o aluno já estiver matriculado</li>
                                    <li>As alterações serão aplicadas imediatamente após salvar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
                        </svg>
                        <h4 class="text-lg font-medium text-gray-dark mb-2">Nenhuma turma disponível</h4>
                        <p class="text-gray-dark/70 mb-4">Crie algumas turmas primeiro para poder matricular alunos.</p>
                        <a href="{{ route('turmas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Criar Turma</span>
                        </a>
                    </div>
                @endif

                @error('turmas')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-light">
                <a href="{{ route('alunos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
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
