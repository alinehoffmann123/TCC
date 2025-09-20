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
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">o -->
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
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7 7-7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
      </svg>
      <span x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-4"
            class="sidebar-item-transition">Dashboard</span>
    </a>
    <a
      href="{{ route('alunos.index') }}"
      class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium {{ request()->routeIs('alunos.*') ? 'bg-bordo-dark shadow-md' : '' }}"
      :class="{ 'justify-center': !sidebarOpen }"
      x-bind:title="!sidebarOpen ? 'Alunos' : null"
    >
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
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
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.653-.146-1.28-.42-1.857M7 20v-2c0-.653.146-1.28.42-1.857M7 20h10m0 0h2.5M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0h10a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
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
              d="M15 10l4-4m0 0l-4-4m4 4H7a4 4 0 000 8h10m-4-4l4 4m0 0l-4 4"></path>
      </svg>
      <span x-show="sidebarOpen" class="sidebar-item-transition">Vídeo Aulas</span>
    </a>
  </nav>
</aside>
