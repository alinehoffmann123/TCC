@extends('layouts.app')

@section('title', 'Lixeira de Alunos')

@section('content')
<div class="flex justify-between items-center mb-6 animate-fade-in">
    <h2 class="text-3xl font-bold text-gray-dark">Lixeira de Alunos</h2>
    <a href="{{ route('alunos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span>Voltar para Alunos</span>
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light overflow-x-auto animate-fade-in animate-delay-200">
    <table class="min-w-full divide-y divide-gray-light">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Nome</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Faixa</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-dark uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-light">
            @forelse ($alunos as $aluno)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm">{{ substr($aluno->nome, 0, 2) }}</div>
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
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Excluído</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <form action="{{ route('alunos.restore', $aluno->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja restaurar este aluno?');">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Restaurar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-dark/70">Nenhum aluno na lixeira.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-6">
        {{ $alunos->links() }}
    </div>
</div>
@endsection