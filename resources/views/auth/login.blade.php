<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bordo-dark': '#4B001E',
                        'bordo-hover': '#73002A',
                        'gray-light': '#CCCCCC',
                        'gray-dark': '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-bordo-dark { background-color: #4B001E; }
        .bg-bordo-hover { background-color: #73002A; }
        .hover\:bg-bordo-hover:hover { background-color: #73002A; }
        .text-bordo-dark { color: #4B001E; }
        .text-bordo-hover { color: #73002A; }
        .hover\:text-bordo-hover:hover { color: #73002A; }
        .border-bordo-dark { border-color: #4B001E; }
        .focus\:border-bordo-dark:focus { border-color: #4B001E; }
        .focus\:ring-bordo-dark:focus { --tw-ring-color: #4B001E; }
        .bg-gray-light { background-color: #CCCCCC; }
        .border-gray-light { border-color: #CCCCCC; }
        .text-gray-dark { color: #333333; }
        .shadow-bordo-dark\/10 { box-shadow: 0 25px 50px -12px rgba(75, 0, 30, 0.1); }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #4B001E;
        }
        
        input[type="checkbox"]:checked {
            background-color: #4B001E;
            border-color: #4B001E;
        }
    </style>
</head>
<body class="min-h-screen bg-white flex">
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-bordo-dark via-bordo-dark to-black text-white p-12 flex-col justify-center relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-white/5"></div>
        <div class="absolute inset-0" style="background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.1), transparent 50%);"></div>
        <div class="absolute top-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute bottom-20 left-10 w-24 h-24 bg-bordo-hover/30 rounded-full blur-lg"></div>
        <img href=""></img>

        <div class="max-w-lg relative z-10">
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-6 shadow-lg backdrop-blur-sm border border-white/20">
                    <svg class="w-8 h-8 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 drop-shadow-lg">Fightgym System</h1>
                <h2 class="text-2xl text-white/90 mb-6 drop-shadow-md">Sistema para Academia de Jiu-Jitsu</h2>
            </div>

            <div class="mb-8">
                <p class="text-lg text-white/80 leading-relaxed mb-6 drop-shadow-sm">
                    Plataforma completa para gerenciamento de academias de Jiu-Jitsu, oferecendo controle total sobre alunos,
                    instrutores, aulas e graduações.
                </p>
            </div>
            <div class="space-y-4 bg-black/10 p-6 rounded-xl backdrop-blur-sm border border-white/10 shadow-inner">
                <h3 class="text-xl font-semibold mb-4 drop-shadow-md">Principais Funcionalidades:</h3>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/5 transition-all duration-200">
                    <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="text-white/90 drop-shadow-sm">Gestão completa de alunos e instrutores</span>
                </div>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/5 transition-all duration-200">
                    <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 13a2 2 0 002 2h8a2 2 0 002-2L16 7"></path>
                        </svg>
                    </div>
                    <span class="text-white/90 drop-shadow-sm">Controle de horários e agendamentos</span>
                </div>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/5 transition-all duration-200">
                    <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <span class="text-white/90 drop-shadow-sm">Sistema de graduações e faixas</span>
                </div>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/5 transition-all duration-200">
                    <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-white/90 drop-shadow-sm">Acompanhamento de evolução técnica</span>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-white/20">
                <p class="text-white/60 text-sm drop-shadow-sm">
                    Desenvolvido especialmente para academias de Jiu-Jitsu
                </p>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-br from-gray-50 to-white relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-bordo-dark/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-gray-light/30 rounded-full blur-2xl"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-bordo-dark rounded-full mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-dark mb-2">Sistema de Gestão</h1>
                <p class="text-gray-dark/70">Academia de Jiu-Jitsu</p>
            </div>
            <div class="bg-white border border-gray-light shadow-2xl shadow-bordo-dark/10 backdrop-blur-sm rounded-lg">
                <div class="text-center py-6 px-6 border-b border-gray-light">
                    <h2 class="text-2xl text-gray-dark font-semibold">Login</h2>
                    <p class="text-gray-dark/70 mt-1">Entre com suas credenciais para acessar a plataforma</p>
                </div>
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="text-red-600 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label for="email" class="text-gray-dark font-medium block">Email</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-3 h-4 w-4 text-gray-dark/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    value="{{ old('email') }}"
                                    placeholder="seu@email.com"
                                    class="pl-10 w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark"
                                    required
                                    autofocus
                                >
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="password" class="text-gray-dark font-medium block">Senha</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-3 h-4 w-4 text-gray-dark/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    placeholder="Digite sua senha"
                                    class="pl-10 pr-10 w-full px-3 py-2 border border-gray-light rounded-md focus:outline-none focus:ring-2 focus:ring-bordo-dark focus:border-bordo-dark text-gray-dark"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="jVisualizarDadosSenhas('password')"
                                    class="absolute right-3 top-3 text-gray-dark/50 hover:text-bordo-dark transition-colors"
                                >
                                    <svg id="eye-open-password" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eye-closed-password" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button 
                            type="submit"
                            class="w-full bg-bordo-dark hover:bg-bordo-hover text-white font-semibold py-3 px-4 rounded-md transition-colors duration-200"
                        >
                            Entrar
                        </button>
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-light"></div>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-dark/70">
                            Não tem uma conta?
                            <a href="{{ route('cadastro') }}" class="text-bordo-dark hover:text-bordo-hover hover:underline font-medium transition-colors ml-1">
                                Cadastre-se
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-8 text-gray-dark/50 text-sm">
                <p>© {{ date('Y') }} Fightgym System. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>