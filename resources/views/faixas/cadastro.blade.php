@extends('layouts.app')
@section('title','Nova Faixa')
@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-light p-6 max-w-3xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-dark mb-4">Cadastrar Faixa</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ implode(', ', $errors->all()) }}</div>
  @endif

  <form method="POST" action="{{ route('faixas.store') }}" x-data="{ criterios: [{chave:'tempo_meses',operador:'>=',valor:6,peso:1}] }">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm font-medium">Nome *</label>
        <input name="nome" class="w-full border rounded px-3 py-2" required placeholder="Branca/Azul/Roxa…">
      </div>
      <div>
        <label class="text-sm font-medium">Ordem *</label>
        <input type="number" name="ordem" class="w-full border rounded px-3 py-2" required placeholder="1,2,3…">
      </div>
      <div>
        <label class="text-sm font-medium">Cor (hex)</label>
        <input name="cor_hex" class="w-full border rounded px-3 py-2" placeholder="#FFFFFF">
      </div>
    </div>

    <div class="mt-6">
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" name="ativa" value="1" checked>
        <span>Ativa</span>
      </label>
    </div>

    <h3 class="text-lg font-semibold mt-6 mb-2">Critérios de Progressão</h3>
    <template x-for="(c, idx) in criterios" :key="idx">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
        <select class="border rounded px-2 py-2" x-model="c.chave">
          <option value="tempo_meses">Tempo (meses)</option>
          <option value="avaliacao_media">Avaliação média</option>
          <option value="aulas_minimas">Aulas mínimas</option>
        </select>
        <select class="border rounded px-2 py-2" x-model="c.operador">
          <option value=">=">>=</option>
          <option value=">">></option>
          <option value="=">=</option>
        </select>
        <input type="number" step="0.01" class="border rounded px-2 py-2" x-model="c.valor" placeholder="Valor">
        <input type="number" step="0.01" class="border rounded px-2 py-2" x-model="c.peso" placeholder="Peso (opcional)">
        <button type="button" class="text-red-600" @click="criterios.splice(idx,1)">Remover</button>
      </div>
    </template>
    <button type="button" class="text-bordo-dark font-semibold" @click="criterios.push({chave:'tempo_meses',operador:'>=',valor:1,peso:1})">+ Adicionar critério</button>

    <div id="criterios-hidden"></div>

    <div class="flex justify-end mt-6 space-x-3">
      <a href="{{ route('faixas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancelar</a>
      <button class="bg-bordo-dark hover:bg-bordo-hover text-white px-5 py-2 rounded">Salvar</button>
    </div>
  </form>

  <script>
    document.querySelector('form').addEventListener('submit', function(){
      const oWrap = document.getElementById('criterios-hidden');
      oWrap.innerHTML = '';
      const oCriterios = Alpine.$data(document.querySelector('form')).oCriterios || [];
      oCriterios.forEach((aElemento1,iIndex)=>{
        ['chave','operador','valor','peso'].forEach(aDado =>{
          const oInput = document.createElement('input');
          oInput.type  = 'hidden';
          oInput.name  = `criterios[${iIndex}][${aDado}]`;
          oInput.value = aElemento1[aDado] ?? '';
          oWrap.appendChild(oInput);
        });
      });
    });
  </script>
</div>
@endsection