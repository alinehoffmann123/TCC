@extends('layouts.app')
@section('title','Graduações')

@section('content')
<div class="flex justify-between items-center mb-6">
  <div>
    <h2 class="text-3xl font-bold text-gray-dark">Graduações do Aluno #{{ $iCodigoAluno }}</h2>
    <p class="text-gray-dark/70">Histórico de faixas</p>
  </div>
  <a href="{{ route('graduacoes.create', $iCodigoAluno) }}"
     class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md">
     Registrar Graduação
  </a>
</div>

@if(session('success'))
  <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-lg border border-gray-light p-6">
  @forelse($graduacoes as $g)
    <div class="flex items-start py-3 border-b last:border-0">
      <div class="w-40 text-gray-600">{{ \Carbon\Carbon::parse($g->data_graduacao)->format('d/m/Y') }}</div>
      <div class="flex-1">
        <div class="font-semibold text-gray-900">
          {{ optional($g->faixaAnterior)->nome ?? '—' }} → {{ optional($g->faixaNova)->nome }}
        </div>
        <div class="text-sm text-gray-600">
          Instrutor: {{ $g->instrutor_nome ?? '—' }}
        </div>
        @if($g->observacoes)
          <div class="text-sm text-gray-700 mt-1">{{ $g->observacoes }}</div>
        @endif
      </div>
    </div>
  @empty
    <p class="text-gray-600">Nenhuma graduação registrada ainda.</p>
  @endforelse
</div>
@endsection