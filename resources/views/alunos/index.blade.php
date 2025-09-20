@extends('layouts.app')

@section('title', 'Pessoa')

@section('content')
<div
  x-data="{
    showDeleteModal: false,
    studentToDelete: null,
    studentName: '',
    deleteForm: null
  }"
  class="animate-fade-in"
>
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-dark">Gestão de Alunos</h2>
     @php
        $role = auth()->user()->role ?? null;
        $bProfessor = in_array($role, ['professor']);
    @endphp
    <@if($bProfessor)
      <div class="flex space-x-4">
        <a href="{{ route('alunos.create') }}"
          class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          <span>{{ $oPessoa === 'professores' ? 'Adicionar Professor' : 'Adicionar Aluno' }}</span>
        </a>
      </div>
    @endif
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md">
      {{ session('success') }}
    </div>
  @endif

  <div class="mb-4">
    <div class="inline-flex rounded-xl border border-gray-light bg-white p-1 shadow-sm">
      <a href="{{ route('alunos.index', ['tab' => 'alunos']) }}"
         class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $oPessoa === 'alunos' ? 'bg-bordo-dark text-white' : 'text-gray-700 hover:bg-gray-50' }}">
        Alunos
      </a>
      <a href="{{ route('alunos.index', ['tab' => 'professores']) }}"
         class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $oPessoa === 'professores' ? 'bg-bordo-dark text-white' : 'text-gray-700 hover:bg-gray-50' }}">
        Professores
      </a>
    </div>
  </div>

  @php
    $aListaPessoas = $oPessoa === 'professores' ? $aProfessores : $aAlunos;
    $sPessoa = $oPessoa === 'professores' ? 'professor' : 'aluno';
  @endphp

  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-light">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Nome</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Faixa</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Turmas</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-dark uppercase tracking-wider">Ações</th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y divide-gray-light">
        @forelse ($aListaPessoas as $aPessoa)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-bordo-dark flex items-center justify-center text-white font-bold text-sm">
                  {{ strtoupper(substr($aPessoa->nome, 0, 2)) }}
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-dark">{{ $aPessoa->nome }}</div>
                  <div class="text-sm text-gray-dark/70">
                    • #{{ $aPessoa->id }}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-dark/80">
              {{ $aPessoa->email }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              @php
              $sNomeFaixa = $aPessoa->faixa_atual_nome; 
              $sFaixa = $aPessoa->faixa_atual_slug;

              $sCorFaixa = match ($sFaixa) {
                'branca' => 'bg-gray-200 text-gray-800',
                'azul'   => 'bg-blue-100 text-blue-800',
                'roxa'   => 'bg-purple-100 text-purple-800',
                'marrom' => 'bg-yellow-100 text-yellow-800',
                'preta'  => 'bg-black text-white',
                default  => 'bg-gray-100 text-gray-600',
              };
            @endphp
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sCorFaixa }}">
              {{ $sNomeFaixa ?? '—' }}
            </span>

            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              @php
                $sStatusPessoa = match ($aPessoa->status) {
                  'ativo'    => 'bg-green-100 text-green-800',
                  'inativo'  => 'bg-red-100 text-red-800',
                  'trancado' => 'bg-yellow-100 text-yellow-800',
                  default    => 'bg-gray-100 text-gray-800',
                };
              @endphp
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sStatusPessoa }}">
                {{ ucfirst($aPessoa->status) }}
              </span>
            </td>

            <td class="px-6 py-4">
              @php
                $aTurmasVisiveis = $aPessoa->turmas->where('pivot.papel', $sPessoa);
              @endphp

              @if($aTurmasVisiveis->count() > 0)
                <div class="flex flex-wrap gap-1">
                  @foreach($aTurmasVisiveis->take(2) as $aTurma)
                    <span class="px-2 py-1 text-xs bg-bordo-dark/10 text-bordo-dark rounded-full">
                      {{ $aTurma->nome }}
                    </span>
                  @endforeach
                  @if($aTurmasVisiveis->count() > 2)
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                      +{{ $aTurmasVisiveis->count() - 2 }} mais
                    </span>
                  @endif
                </div>
              @else
                <span class="text-sm text-gray-400 italic">Sem turmas</span>
              @endif
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <a href="{{ route('alunos.evolucao', ['aluno' => $aPessoa->id]) }}"
                class="text-bordo-dark hover:text-bordo-hover mr-3 {{ $oPessoa === 'professores' ? 'invisible': ''}}" >
                Evolução
              </a>
              <a href="{{ route('alunos.show', $aPessoa->id) }}"
                 class="text-blue-600 hover:text-blue-800 mr-3"
                 title="Ver Detalhes">Ver Detalhes</a>
              @php($role = auth()->user()?->role)
              @if(in_array($role, ['admin','professor']))
                <a href="{{ route('alunos.edit', $aPessoa->id) }}"
                  class="text-bordo-dark hover:text-bordo-hover mr-3">Editar</a>

                <form action="{{ route('alunos.destroy', $aPessoa->id) }}"
                      method="POST"
                      class="inline-block"
                      id="delete-form-{{ $aPessoa->id }}">
                  @csrf
                  @method('DELETE')

                  <button type="button"
                          class="text-red-600 hover:text-red-900"
                          @click="
                            studentToDelete = {{ $aPessoa->id }};
                            studentName     = '{{ addslashes($aPessoa->nome) }}';
                            deleteForm      = document.getElementById('delete-form-{{ $aPessoa->id }}');
                            showDeleteModal = true;
                          ">
                    Excluir
                  </button>
                @endif
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-dark/70">
              Nenhum {{ $oPessoa === 'professores' ? 'professor' : 'aluno' }} encontrado.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-6">
      {{ $aListaPessoas->appends(['tab' => $oPessoa])->links() }}
    </div>
  </div>

  <template x-teleport="body">
    <div
      x-show="showDeleteModal"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0"
      style="display:none"
      aria-modal="true"
      role="dialog"
    >
      <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>

      <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8">
        <div
          x-show="showDeleteModal"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
          x-transition:leave="transition ease-in duration-200"
          x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
          x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          class="bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6"
        >
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmar Exclusão</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Tem certeza que deseja enviar o registro
                  <strong x-text="studentName"></strong>
                  para a lixeira? Esta ação pode ser desfeita posteriormente.
                </p>
              </div>
            </div>
          </div>

          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button"
                    @click="deleteForm?.submit(); showDeleteModal = false;"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
  </template>
</div>
@endsection
