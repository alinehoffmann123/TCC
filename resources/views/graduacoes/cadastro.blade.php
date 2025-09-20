@extends('layouts.app')
@section('title','Registrar Graduação')
@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-light p-6 max-w-2xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-dark mb-4">Registrar Graduação</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ implode(', ', $errors->all()) }}</div>
  @endif

  <form method="POST" action="{{ route('graduacoes.store', $alunoId) }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm font-medium">Faixa atual</label>
        <input class="w-full border rounded px-3 py-2 bg-gray-50" value="{{ optional($aUltima?->faixaNova)->nome ?? '—' }}" disabled>
      </div>
      <div>
        <label class="text-sm font-medium">Nova Faixa *</label>
        <select name="faixa_nova_id" class="w-full border rounded px-3 py-2" required>
          <option value="">Selecione</option>
          @foreach($aFaixas as $aFaixa)
            <option value="{{ $aFaixa->id }}">{{ $aFaixa->ordem }} — {{ $aFaixa->nome }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-sm font-medium">Data *</label>
        <input type="date" name="data_graduacao" class="w-full border rounded px-3 py-2" required value="{{ date('Y-m-d') }}">
      </div>
      <div>
        <label class="text-sm font-medium">Instrutor responsável</label>
        <input name="instrutor_nome" class="w-full border rounded px-3 py-2" placeholder="Nome do instrutor">
      </div>
    </div>

    <div class="mt-4">
      <label class="text-sm font-medium">Observações</label>
      <textarea name="observacoes" class="w-full border rounded px-3 py-2" rows="3"></textarea>
    </div>

    <div class="flex justify-end mt-6 space-x-3">
      <a href="{{ route('graduacoes.index', $alunoId) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancelar</a>
      <button class="bg-bordo-dark hover:bg-bordo-hover text-white px-5 py-2 rounded">Registrar</button>
    </div>
  </form>
</div>
@endsection