<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bordo-dark': '#4B001E',
                        'bordo-hover': '#73002A',
                        'white': '#FFFFFF',
                        'gray-light': '#CCCCCC',
                        'gray-dark': '#333333',
                        'black': '#000000',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
        .animate-delay-300 { animation-delay: 0.3s; }
        .animate-delay-400 { animation-delay: 0.4s; }
        .animate-delay-500 { animation-delay: 0.5s; }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .sidebar-transition {
            transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        }
        .sidebar-item-transition {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        .glassmorphism {
            background-color: rgba(51, 51, 51, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
@stack('scripts')
<body class="bg-gray-100 font-sans antialiased text-gray-dark">
    <div class="min-h-screen flex flex-col">
        @include('layouts.header')
        <div class="flex flex-1" x-data="{ sidebarOpen: false }">
            @include('layouts.sidebar')
            <main class="flex-1 p-6 bg-gray-100 overflow-auto" 
                  :class="sidebarOpen ? 'md:ml-64' : 'md:ml-16'">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>