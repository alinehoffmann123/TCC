<aside
  x-cloak
  class="fixed left-0 top-0 bottom-0 z-30 flex flex-col justify-between py-6 px-2 shadow-xl hidden md:flex glassmorphism text-white sidebar-transition"
  :class="sidebarOpen ? 'w-64' : 'w-16 items-center'"
>
  <nav class="space-y-4 flex-1">
    <div :class="sidebarOpen ? 'text-right pr-4' : 'text-center'">
      <button
        @click="sidebarOpen = !sidebarOpen"
        class="p-2 rounded-full hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200"
        :aria-expanded="sidebarOpen"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
    <a
      href="{{ route('dashboard') }}"
      class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium {{ request()->routeIs('dashboard') ? 'bg-bordo-dark shadow-md' : '' }}"
      :class="{ 'justify-center': !sidebarOpen }"
      x-bind:title="!sidebarOpen ? 'Dashboard' : null"
    >
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m10-11v11a1 1 0 01-1 1h-5"></path>
      </svg>
      <span x-show="sidebarOpen" class="sidebar-item-transition">Dashboard</span>
    </a>
    <a
      href="{{ route('alunos.index') }}"
      class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium {{ request()->routeIs('alunos.*') ? 'bg-bordo-dark shadow-md' : '' }}"
      :class="{ 'justify-center': !sidebarOpen }"
      x-bind:title="!sidebarOpen ? 'Alunos' : null"
    >
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a4 4 0 00-5-3.87M12 12a4 4 0 100-8 4 4 0 000 8zm-6 8v-2a4 4 0 015-3.87"></path>
      </svg>
      <span x-show="sidebarOpen" class="sidebar-item-transition">Alunos</span>
    </a>

    <a
      href="{{ route('turmas.index') }}"
      class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium {{ request()->routeIs('turmas.*') ? 'bg-bordo-dark shadow-md' : '' }}"
      :class="{ 'justify-center': !sidebarOpen }"
      x-bind:title="!sidebarOpen ? 'Turmas' : null"
    >
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 5h16M4 5v14a2 2 0 002 2h12a2 2 0 002-2V5M4 5l8 7 8-7"></path>
      </svg>
      <span x-show="sidebarOpen" class="sidebar-item-transition">Turmas</span>
    </a>

    <a
      href="{{ route('video-aulas.index') }}"
      class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium {{ request()->routeIs('video-aulas.*') ? 'bg-bordo-dark shadow-md' : '' }}"
      :class="{ 'justify-center': !sidebarOpen }"
      x-bind:title="!sidebarOpen ? 'Vídeo Aulas' : null"
    >
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 10l4 2-4 2V10zm-7-5h14a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
      </svg>
      <span x-show="sidebarOpen" class="sidebar-item-transition">Vídeo Aulas</span>
    </a>

  </nav>
</aside>
