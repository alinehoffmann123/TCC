@extends('layouts.app')
@section('title','Faixas & Critérios')

@section('content')
<div class="flex justify-between items-center mb-6">
  <div>
    <h2 class="text-3xl font-bold text-gray-dark">Faixas & Critérios</h2>
    <p class="text-gray-dark/70">Gerencie as faixas e critérios de progressão</p>
  </div>
  <a href="{{ route('faixas.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md">Nova Faixa</a>
</div>

@if(session('success'))
  <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-lg border border-gray-light overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50 text-gray-600">
      <tr>
        <th class="text-left px-4 py-3">Ordem</th>
        <th class="text-left px-4 py-3">Nome</th>
        <th class="text-left px-4 py-3">Cor</th>
        <th class="text-left px-4 py-3">Ativa</th>
        <th class="text-left px-4 py-3">Critérios</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @foreach($aFaixas as $aFaixa)
      <tr>
        <td class="px-4 py-3">{{ $aFaixa->ordem }}</td>
        <td class="px-4 py-3">{{ $aFaixa->nome }}</td>
        <td class="px-4 py-3">
          @if($aFaixa->cor_hex)
            <span class="inline-flex items-center">
              <span class="w-4 h-4 rounded-full mr-2" style="background: {{ $aFaixa->cor_hex }}"></span>{{ $aFaixa->cor_hex }}
            </span>
          @else — @endif
        </td>
        <td class="px-4 py-3">{{ $aFaixa->ativa ? 'Sim' : 'Não' }}</td>
        <td class="px-4 py-3">{{ $aFaixa->criterios->count() }}</td>
        <td class="px-4 py-3 text-right space-x-2">
          <a href="{{ route('faixas.edit', $aFaixa) }}" class="text-bordo-dark hover:underline">Editar</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
