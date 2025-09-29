// Funciones

    function savePizarra() {
        const json = JSON.stringify(graph.toJSON(), null, 2);
        const blob = new Blob([json], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        const fileName = `graph_${new Date().toISOString().replace(/[:.]/g, '-')}.json`;
        a.download = fileName;
        a.click();
        URL.revokeObjectURL(url);                    
    }
    
    //guardar
    function exportPizarra(){        
        // Obtener datos de la pizarra
        const json = JSON.stringify(graph.toJSON());
        
        // Poner los datos en el input oculto
        document.getElementById('graph_data').value = json;
        
        // Enviar el formulario
        document.getElementById('exportForm').submit();        
    }
    
    // Funcionalidad de guardar
    $('#saveBtn').click(savePizarra);

    // Funcionalidad generar backend
    $('#exportBtn').click(exportPizarra);

    // Funcionalidad de compartir link
    $('#shareBtn').click(function() {
        $('#shareModal').removeClass('hidden');
    });

    $('#closeShareModal').click(function() {
        $('#shareModal').addClass('hidden');
    });

    $('#copyLink').click(function() {
        const link = $('#shareLink').val();
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            // ✅ Método moderno (HTTPS o localhost)
            navigator.clipboard.writeText(link).then(function () {
                alert('Enlace copiado al portapapeles');
            }).catch(function () {
                alert('No se pudo copiar automáticamente');
            });
        } else {
            // 🔄 Fallback para HTTP o navegadores antiguos
            const tempInput = document.createElement("input");
            tempInput.value = link;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert('Enlace copiado (modo compatibilidad)');
        }
    });

    // Funcionalidad de abrir    
    document.getElementById('openBtn').addEventListener('click', () => {
        const openFile = document.getElementById('openFile');
        openFile.click();
    });

    // Maneja el cambio en el input de archivo
    document.getElementById('openFile').addEventListener('change', (event) => {
        const file = event.target.files[0];
        
        // Validar si se seleccionó un archivo
        if (!file) {
            console.warn('⚠️ No se seleccionó ningún archivo.');
            return;
        }

        // Validar que el archivo sea JSON
        if (!file.name.endsWith('.json')) {
            console.error('❌ Por favor, selecciona un archivo JSON.');
            alert('Por favor, selecciona un archivo con extensión .json');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const json = JSON.parse(e.target.result);
                console.log('Archivo JSON cargado:', json);

                // Verificar si el objeto 'graph' está definido
                if (typeof graph === 'undefined') {
                    console.warn('⚠️ El objeto "graph" no está definido en este contexto.');
                    alert('Error: No se puede cargar el diagrama porque el objeto "graph" no está definido.');
                    return;
                }

                // Cargar el JSON en el grafo
                graph.clear();
                graph.fromJSON(json);
                console.log('Grafo cargado desde el archivo.');

            } catch (err) {
                console.error('❌ Error al parsear el JSON:', err);
                alert('Error: No se pudo procesar el archivo JSON. Asegúrate de que sea válido.');
            }
        };

        // Leer el archivo como texto
        reader.readAsText(file);

        // Resetear el input para permitir reabrir el mismo archivo
        event.target.value = '';
    });
    

    // Funcionalidad responsive para móviles
    $('#mobileMenuBtn').click(function() {
        $('#mobileMenu').toggleClass('hidden');
    });

    $('#toggleToolsBtn').click(function() {
        $('#toolsSidebar').removeClass('-translate-x-full');
        $('#mobileOverlay').removeClass('hidden');
    });

    $('#closeToolsSidebar').click(function() {
        $('#toolsSidebar').addClass('-translate-x-full');
        $('#mobileOverlay').addClass('hidden');
    });

    $('#closePropertiesSidebar').click(function() {
        $('#propertiesSidebar').addClass('translate-x-full');
        $('#mobileOverlay').addClass('hidden');
    });

    $('#mobileOverlay').click(function() {
        $('#toolsSidebar').addClass('-translate-x-full');
        $('#propertiesSidebar').addClass('translate-x-full');
        $('#mobileOverlay').addClass('hidden');
    });

    // Botones móviles
    $('#openBtnMobile').click(function() {
        $('#openModal').removeClass('hidden');
        $('#mobileMenu').addClass('hidden');
    });

    $('#saveBtnMobile').click(function() {
        savePizarra();
        $('#mobileMenu').addClass('hidden');
    });

    $('#importBtnMobile').click(function() {
        $('#importModal').removeClass('hidden');
        $('#mobileMenu').addClass('hidden');
    });

    $('#exportBtnMobile').click(function() {
        exportPizarra();
        $('#mobileMenu').addClass('hidden');
    });

    $('#shareBtnMobile').click(function() {
        $('#shareModal').removeClass('hidden');
        $('#mobileMenu').addClass('hidden');
    });
