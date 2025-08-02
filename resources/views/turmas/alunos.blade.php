@extends('layouts.app')

@section('title', 'Gerenciar Alunos da Turma')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-dark">Gerenciar Alunos da Turma: <span class="text-bordo-dark">{{ $turma->nome }}</span></h2>
            <p class="text-gray-dark/70 mt-1">Adicione ou remova alunos desta turma.</p>
        </div>
        <a href="{{ route('turmas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
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

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
            {{ session('error') }}
        </div>
    @endif
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light mb-6 animate-fade-in animate-delay-200">
        <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informações da Turma
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-dark">
            <p><strong>Instrutor:</strong> {{ $turma->instrutor }}</p>
            <p><strong>Horário:</strong> {{ $turma->horario_inicio->format('H:i') }} - {{ $turma->horario_fim->format('H:i') }}</p>
            <p><strong>Dias:</strong> {{ $turma->dias_semana_formatados }}</p>
            <p><strong>Capacidade:</strong> {{ $turma->numero_alunos }} / {{ $turma->capacidade_maxima }} ({{ $turma->ocupacao_percentual }}%)</p>
            <p><strong>Modalidade:</strong> {{ ucfirst(str_replace('-', ' ', $turma->modalidade)) }}</p>
            <p><strong>Nível:</strong> {{ ucfirst($turma->nivel) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light mb-6 animate-fade-in animate-delay-300">
        <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Alunos Atuais na Turma ({{ $turma->alunos->count() }})
        </h3>

        @if($turma->alunos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-light">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Nome</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Faixa</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-dark uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-light">
                        @foreach ($turma->alunos as $aluno)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-bordo-dark flex items-center justify-center text-white font-bold text-sm">{{ substr($aluno->nome, 0, 2) }}</div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-dark">{{ $aluno->nome }}</div>
                                        <div class="text-sm text-gray-dark/70">Matrícula: #{{ $aluno->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-dark/80">{{ $aluno->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $beltColorClass = '';
                                    switch ($aluno->faixa) {
                                        case 'branca': $beltColorClass = 'bg-gray-200 text-gray-dark'; break;
                                        case 'azul': $beltColorClass = 'bg-blue-100 text-blue-800'; break;
                                        case 'roxa': $beltColorClass = 'bg-purple-100 text-purple-800'; break;
                                        case 'marrom': $beltColorClass = 'bg-yellow-100 text-yellow-800'; break;
                                        case 'preta': $beltColorClass = 'bg-black text-white'; break;
                                        default: $beltColorClass = 'bg-gray-200 text-gray-dark'; break;
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $beltColorClass }}">{{ ucfirst($aluno->faixa) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('turmas.remover-aluno', ['turma' => $turma->id, 'aluno' => $aluno->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Remover Aluno">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h4 class="text-lg font-medium text-gray-dark mb-2">Nenhum aluno matriculado nesta turma</h4>
                <p class="text-gray-dark/70">Use o formulário abaixo para adicionar alunos.</p>
            </div>
        @endif
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light animate-fade-in animate-delay-400">
        <h3 class="text-xl font-semibold text-gray-dark mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-bordo-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Matricular Novos Alunos
        </h3>

        @if($alunosDisponiveis->count() > 0)
            <form action="{{ route('turmas.matricular', $turma->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="aluno_id" class="block text-sm font-medium text-gray-dark mb-2">
                        Selecionar Aluno <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="aluno_id" 
                        name="aluno_id" 
                        required 
                        class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark @error('aluno_id') border-red-500 @enderror"
                    >
                        <option value="">Selecione um aluno</option>
                        @foreach($alunosDisponiveis as $aluno)
                            <option value="{{ $aluno->id }}">{{ $aluno->nome }} ({{ $aluno->email }})</option>
                        @endforeach
                    </select>
                    @error('aluno_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2 ml-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Matricular Aluno</span>
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                <h4 class="text-lg font-medium text-gray-dark mb-2">Todos os alunos já estão matriculados ou não há alunos disponíveis.</h4>
                <p class="text-gray-dark/70">Cadastre novos alunos para poder adicioná-los às turmas.</p>
                <a href="{{ route('alunos.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 inline-flex items-center space-x-2 mt-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Cadastrar Novo Aluno</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
