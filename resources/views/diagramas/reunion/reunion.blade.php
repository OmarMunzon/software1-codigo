<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <title>Collaboration</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="min-h-screen bg-gray-900 text-white ">
        <!-- Header -->
    <header class="bg-gray-800 shadow-lg border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
                        <p class="text-sm text-gray-400">Panel de Reuniones</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notificaciones -->
                    <button class="relative p-2 text-gray-400 hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-gray-800"></span>
                    </button>
                    
                    <!-- Perfil del usuario -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white font-medium text-sm">{{ substr(auth()->user()->email, 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block text-gray-300">{{ auth()->user()->email }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Cerrar sesión -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
<!-- Contenido principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="w-full max-w-2xl p-6 bg-gray-800 rounded-lg shadow-lg mx-auto">   
            <div class="mb-6 text-center">
                <div class="grid md:grid- gap-6 max-w-4xl mx-auto">
                    <!-- Botón Crear Reunión -->
                    <div class="group">
                        <a id="createBtn" href="{{ route('reunion.create') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white p-6 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border border-blue-500/20">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="p-3 bg-blue-500/20 rounded-full">
                                    <svg class="h-8 w-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold mb-2">Crear Reunión</h3>
                                    <p class="text-blue-200 text-sm">Inicia una nueva sesión colaborativa</p>
                                </div>
                                <div class="flex items-center text-blue-300 text-sm">
                                    <span>Haz clic para comenzar</span>
                                    <svg class="h-4 w-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>      
                    
                    <!-- Botón Unirse a Reunión -->
                    <div class="group">
                        <button onclick="openJoinModal()" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white p-6 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border border-green-500/20">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="p-3 bg-green-500/20 rounded-full">
                                    <svg class="h-8 w-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold mb-2">Unirse a Reunión</h3>
                                    <p class="text-green-200 text-sm">Participa en una sesión existente</p>
                                </div>
                                <div class="flex items-center text-green-300 text-sm">
                                    <span>Ingresa con enlace</span>
                                    <svg class="h-4 w-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>              
        </div>

        <!-- Incluir el modal de unirse a reunión -->
        @include('diagramas.reunion.join-modal')
    </main>
    </div>
    <script>
        document.getElementById('createBtn').addEventListener('click', function (e) {
            this.style.pointerEvents = 'none'; // No permite más clics
            this.style.opacity = 0.5;          // Efecto visual opcional
        });        
    </script>
</body>
</html>