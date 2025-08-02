@extends('layouts.guest')

@section('title', 'Recuperar Senha')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-bordo-dark via-bordo-hover to-gray-dark"></div>
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full blur-xl"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white bg-opacity-5 rounded-full blur-2xl"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white bg-opacity-10 rounded-full blur-lg"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-white">
                Recuperar Senha
            </h2>
            <p class="mt-2 text-sm text-white/80">
                Digite seu email para receber as instruções
            </p>
        </div>

        <div class="glassmorphism rounded-2xl shadow-2xl p-8 border border-white/20">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-white/30 placeholder-gray-400 text-gray-dark focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark focus:z-10 sm:text-sm bg-white/90 backdrop-blur-sm"
                        placeholder="Digite seu email"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-bordo-dark hover:bg-bordo-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bordo-dark transition-colors duration-200 shadow-lg"
                    >
                        Enviar Instruções
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-sm font-medium underline">
                        Voltar ao Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
