<header class="bg-bordo-dark text-white shadow-lg z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <svg class="w-8 h-8 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <h1 class="text-2xl font-bold">FightGym System</h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-white/90">Olá, <span class="font-semibold">{{ Auth::user()->name }}</span>!</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-bordo-hover hover:bg-bordo-dark px-4 py-2 rounded-md transition-colors duration-200 text-white font-semibold shadow-md">
                    Sair
                </button>
            </form>
        </div>
    </div>
</header>