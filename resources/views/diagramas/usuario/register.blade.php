<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    
    @vite('resources/css/app.css')
        
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 flex justify-center">
        <div class="max-w-md w-full bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Registro</h2>
            <form id="formulario" action="{{ route('register') }}" method="post" class="space-y-4">                
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" id="name" placeholder="Name"required class="w-full px-3 py-2 bg-gray-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500">                    
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" required class="w-full px-3 py-2 bg-gray-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="********"  required class="w-full px-3 py-2 bg-gray-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-white" onclick="togglePassword()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223a10.477 10.477 0 0116.04 0M21 12c0 1.657-.336 3.236-.98 4.777M3.98 15.777a10.477 10.477 0 010-7.554M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button id="registerBtn" type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded text-white font-semibold">Registrar</button>

                <p class="text-center text-sm">Don't have an account? <a href="{{ route('login') }}" id="goToRegister" class="text-blue-400 hover:underline">Login</a></p>
                <div id="loginMessage" class="message text-center text-red-500"></div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        document.getElementById('formulario').addEventListener('submit', function () {
            document.getElementById('registerBtn').disabled = true;
        });
    </script>

</body>
</html>