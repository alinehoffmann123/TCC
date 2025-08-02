@extends('layouts.app')

@section('title', 'Alunos')

@section('content')
<div x-data="{
  showDeleteModal: false,
  studentToDelete: null,
  studentName: '',
  deleteForm: null
}">
  <div class="flex justify-between items-center mb-6 animate-fade-in">
      <h2 class="text-3xl font-bold text-gray-dark">Gestão de Alunos</h2>
      <div class="flex space-x-4">
          <a href="{{ route('alunos.trash') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              <span>Lixeira</span>
          </a>
          <a href="{{ route('alunos.create') }}" class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span>Adicionar Aluno</span>
          </a>
      </div>
  </div>

  @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md animate-fade-in animate-delay-100">
          {{ session('success') }}
      </div>
  @endif

  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light mb-6 animate-fade-in animate-delay-200">
      <h3 class="text-xl font-semibold text-gray-dark mb-4">Filtrar Alunos</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
              <label for="search" class="block text-sm font-medium text-gray-dark mb-1">Buscar por Nome/Email</label>
              <input type="text" id="search" placeholder="Ex: João Silva" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
          </div>
          <div>
              <label for="belt" class="block text-sm font-medium text-gray-dark mb-1">Faixa</label>
              <select id="belt" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                  <option value="">Todas</option>
                  <option value="branca">Branca</option>
                  <option value="azul">Azul</option>
                  <option value="roxa">Roxa</option>
                  <option value="marrom">Marrom</option>
                  <option value="preta">Preta</option>
              </select>
          </div>
          <div>
              <label for="status" class="block text-sm font-medium text-gray-dark mb-1">Status</label>
              <select id="status" class="w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark">
                  <option value="">Todos</option>
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                  <option value="trancado">Trancado</option>
              </select>
          </div>
      </div>
      <div class="mt-6 text-right">
          <button class="bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-2 px-6 rounded-md shadow-md transition-colors duration-200">
              Aplicar Filtros
          </button>
      </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-light overflow-x-auto animate-fade-in animate-delay-300">
      <table class="min-w-full divide-y divide-gray-light">
          <thead class="bg-gray-50">
              <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Nome</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Faixa</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Status</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-dark uppercase tracking-wider">Turmas</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-dark uppercase tracking-wider">Ações</th>
              </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-light">
              @forelse ($alunos as $aluno)
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
                  <td class="px-6 py-4 whitespace-nowrap">
                      @php
                          $statusColorClass = '';
                          switch ($aluno->status) {
                              case 'ativo': $statusColorClass = 'bg-green-100 text-green-800'; break;
                              case 'inativo': $statusColorClass = 'bg-red-100 text-red-800'; break;
                              case 'trancado': $statusColorClass = 'bg-yellow-100 text-yellow-800'; break;
                              default: $statusColorClass = 'bg-gray-100 text-gray-800'; break;
                          }
                      @endphp
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColorClass }}">{{ ucfirst($aluno->status) }}</span>
                  </td>
                  <td class="px-6 py-4">
                      @if($aluno->turmas->count() > 0)
                          <div class="flex flex-wrap gap-1">
                              @foreach($aluno->turmas->take(2) as $turma)
                                  <span class="px-2 py-1 text-xs bg-bordo-dark/10 text-bordo-dark rounded-full">
                                      {{ $turma->nome }}
                                  </span>
                              @endforeach
                              @if($aluno->turmas->count() > 2)
                                  <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                      +{{ $aluno->turmas->count() - 2 }} mais
                                  </span>
                              @endif
                          </div>
                      @else
                          <span class="text-sm text-gray-400 italic">Sem turmas</span>
                      @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <a style="padding-right: 14px;" href="{{ route('alunos.show', $aluno->id) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                title="Ver Detalhes">
                            Ver Detalhes
                        </a>
                      <a href="{{ route('alunos.edit', $aluno->id) }}" class="text-bordo-dark hover:text-bordo-hover mr-3">Editar</a>
                      <form action="{{ route('alunos.destroy', $aluno->id) }}" method="POST" class="inline-block" id="delete-form-{{ $aluno->id }}">
                          @csrf
                          @method('DELETE')
                          <button type="button"
                                  class="text-red-600 hover:text-red-900"
                                  @click="
                                      studentToDelete = {{ $aluno->id }};
                                      studentName = '{{ $aluno->nome }}';
                                      deleteForm = document.getElementById('delete-form-{{ $aluno->id }}');
                                      showDeleteModal = true;
                                  ">
                              Excluir
                          </button>
                      </form>
                  </td>
              </tr>
              @empty
              <tr>
                  <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-dark/70">Nenhum aluno encontrado.</td>
              </tr>
              @endforelse
          </tbody>
      </table>
      <div class="mt-6">
          {{ $alunos->links() }}
      </div>
  </div>

  <!-- Modal de Confirmação de Exclusão (existente) -->
  <div x-show="showDeleteModal" 
       x-transition:enter="transition ease-out duration-300" 
       x-transition:enter-start="opacity-0" 
       x-transition:enter-end="opacity-100" 
       x-transition:leave="transition ease-in duration-200" 
       x-transition:leave-start="opacity-100" 
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 overflow-y-auto" 
       style="display: none;">
      
      <!-- Overlay -->
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
      
      <!-- Modal -->
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <div x-show="showDeleteModal"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
              
              <!-- Ícone e Título -->
              <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                      <h3 class="text-lg leading-6 font-medium text-gray-900">
                          Confirmar Exclusão
                      </h3>
                      <div class="mt-2">
                          <p class="text-sm text-gray-500">
                              Tem certeza que deseja enviar o aluno <strong x-text="studentName"></strong> para a lixeira? 
                              Esta ação pode ser desfeita posteriormente.
                          </p>
                      </div>
                  </div>
              </div>
              
              <!-- Botões -->
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                  <button type="button" 
                          @click="deleteForm.submit(); showDeleteModal = false;"
                          class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
</div>
@endsection
