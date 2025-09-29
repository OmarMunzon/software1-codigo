<!-- Modal para Unirse a Reunión -->
<div id="joinModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-500/20 rounded-lg">
                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Unirse a Reunión</h3>
                    <p class="text-sm text-gray-400">Ingresa el enlace de la reunión</p>
                </div>
            </div>
            <button onclick="closeJoinModal()" class="text-gray-400 hover:text-gray-300 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenido del Modal -->
        <div class="p-6">
            <form id="joinForm" class="space-y-6" method="POST" action="{{ route('reunion.join') }}">
                @csrf
                <div>
                    <label for="collaborationId" class="block text-sm font-medium text-gray-300 mb-2">
                        Enlace de la Reunión
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <input 
                            type="url" 
                            id="collaborationId" 
                            name="collaborationId"
                            placeholder="https://ejemplo.com/reunion/abc123"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-400">
                        Copia y pega el enlace de la reunión que te compartieron
                    </p>
                </div>

                <!-- Mensaje de error -->
                <div id="errorMessage" class="hidden bg-red-900/50 border border-red-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-red-200" id="errorText"></span>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex space-x-3 pt-4">
                    <button 
                        type="button" 
                        onclick="closeJoinModal()"
                        class="flex-1 px-4 py-3 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center justify-center"
                        id="joinBtn"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Unirse
                    </button>
                </div>
            </form>

            
        </div>
    </div>
</div>

<!-- Script para el modal -->
<script>
function openJoinModal() {
    const modal = document.getElementById('joinModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Animación de entrada
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Enfocar el input
    setTimeout(() => {
        document.getElementById('collaborationId').focus();
    }, 300);
}

function closeJoinModal() {
    const modal = document.getElementById('joinModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animación de salida
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        // Limpiar formulario
        document.getElementById('joinForm').reset();
        document.getElementById('errorMessage').classList.add('hidden');
    }, 300);
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeJoinModal();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('joinModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeJoinModal();
    }
});

// Manejar envío del formulario
document.getElementById('joinForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const link = document.getElementById('collaborationId').value.trim();
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    const joinButton = document.getElementById('joinBtn');
    
    // Validación básica
    if (!link) {
        showError('Por favor, ingresa el enlace de la reunión');
        return;
    }
    
    if (!link.startsWith('http://') && !link.startsWith('https://')) {
        showError('Por favor, ingresa un enlace válido que comience con http:// o https://');
        return;
    }
    
    // Mostrar carga
    joinButton.disabled = true;
    joinButton.innerHTML = `
        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Uniéndose...
    `;
    
    // Enviar formulario al servidor
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')        
        }
    })
    .then(response => {
        if (response.redirected) {
            // Si hay redirección, seguirla
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.errors) {
            // Mostrar errores de validación
            const errorMessages = Object.values(data.errors).flat();
            showError(errorMessages[0]);
        } else {
            // Éxito - cerrar modal
            closeJoinModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al procesar la solicitud. Inténtalo de nuevo.');
    })
    .finally(() => {
        // Restaurar botón
        joinButton.disabled = false;
        joinButton.innerHTML = `
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
            Unirse
        `;
    });
});

function showError(message) {
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    
    errorText.textContent = message;
    errorMessage.classList.remove('hidden');
    
    // Ocultar error después de 5 segundos
    setTimeout(() => {
        errorMessage.classList.add('hidden');
    }, 5000);
}
</script> 