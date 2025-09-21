<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Diseño</title>   
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/style_diseño_pizarra.css') }}">
    <!-- cdns joint.js-->
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/jointjs/3.7.0/joint.min.css"
    />
</head>
<body class="bg-gray-100 min-h-screen">
    <main>
        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Pizarra-D. de Clases</h1>
                            <span class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 text-xs sm:text-sm font-medium rounded-full">
                                conectado
                            </span>
                        </div>
                        
                        <!-- Botón de menú móvil -->
                        <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Botones de acción - Desktop -->
                        <div class="hidden lg:flex items-center space-x-2 xl:space-x-3">
                            <button id="openBtn" class="px-3 xl:px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                <i class="fas fa-folder-open mr-1 xl:mr-2"></i><span class="hidden xl:inline">Abrir</span>
                            </button>
                            <!-- Input oculto para elegir archivo -->
                            <input type="file" id="openFile" accept="application/json" style="display:none" />

                            <button id="saveBtn" class="px-3 xl:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-save mr-1 xl:mr-2"></i><span class="hidden xl:inline">Guardar</span>
                            </button>
                            <button id="cargarImgBtn" class="px-3 xl:px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                <i class="fas fa-file-import mr-1 xl:mr-2"></i><span class="hidden xl:inline">Importar Imagen</span>
                            </button>
                            <!-- Input oculto para elegir archivo -->
                            <input type="file" id="abrirArchivo" accept="image/*" style="display:none" />

                            <!-- Botón para generar backend -->
                            <button id="exportBtn" class="px-3 xl:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-download mr-1 xl:mr-2"></i><span class="hidden xl:inline">Generar backend</span>
                            </button>
                            <!-- Formulario oculto -->
                            <form id="exportForm" method="POST" action="/generar-backend" style="display: none;">
                                @csrf
                                <input type="hidden" name="graph_data" id="graph_data">
                            </form>
                            <!-- Botón para compoartir el link -->
                            <button id="shareBtn" class="px-3 xl:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                <i class="fas fa-share mr-1 xl:mr-2"></i><span class="hidden xl:inline">Compartir</span>
                            </button>
                            <!-- Botón para finalizar -->
                            <a href="{{ route('reunion.finalizar') }}" class="px-3 xl:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                <i class="fas fa-times mr-1 xl:mr-2"></i><span class="hidden xl:inline">Finalizar</span>                                
                            </a>                            
                        </div>
                    </div>
                    
                    <!-- Menú móvil -->
                    <div id="mobileMenu" class="lg:hidden hidden border-t pt-4 pb-2">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <button id="openBtnMobile" class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                <i class="fas fa-folder-open mr-2"></i>Abrir
                            </button>
                            <!-- Input oculto para elegir archivo -->
                            <input type="file" id="openFileMobile" accept="application/json" style="display:none" />

                            <button id="saveBtnMobile" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-save mr-2"></i>Guardar
                            </button>
                            <button id="importBtnMobile" class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                <i class="fas fa-file-import mr-2"></i>Importar Imagen
                            </button>
                            <button id="exportBtnMobile" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-download mr-2"></i>Generar backend
                            </button>
                            <button id="shareBtnMobile" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                <i class="fas fa-share mr-2"></i>Compartir
                            </button>
                            <button id="finishCollaborationBtnMobile" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                <i class="fas fa-times mr-2"></i>Finalizar
                            </button>
                        </div>
                    </div>
                </div>
            </header>
        
            <div class="flex h-screen">

                <!-- Sidebar de Herramientas -->
                <div id="toolsSidebar" class="w-64 lg:w-64 xl:w-72 bg-white shadow-lg border-r transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out fixed lg:relative z-40 h-full">
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Herramientas UML</h3>
                            <button id="closeToolsSidebar" class="lg:hidden p-1 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Componentes UML -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Componentes</h4>
                            <div class="space-y-2">
                                <button id="addClassBtn" class="w-full px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    <i class="fas fa-square"></i> Clase UML
                                </button>
                                
                                <!-- Selección de clases para la relación -->
                                <label class="block text-sm font-medium text-gray-700 mb-1">Clase Origen</label>
                                <select id="sourceClass" class="w-full border rounded px-2 py-1 text-sm mb-2"></select>

                                <label class="block text-sm font-medium text-gray-700 mb-1">Clase Destino</label>
                                <select id="targetClass" class="w-full border rounded px-2 py-1 text-sm mb-4"></select>

                                <!-- Combobox Relaciones -->
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de relación</label>
                                <select id="relationType" class="w-full border rounded px-2 py-1 text-sm mb-4">
                                    <option value="association">Asociación</option>
                                    <option value="aggregation">Agregación</option>
                                    <option value="composition">Composición</option>
                                    <option value="inheritance">Herencia</option>
                                </select>

                                <!-- Combobox Multiplicidad -->
                                <label class="block text-sm font-medium text-gray-700 mb-1">Multiplicidad</label>
                                <select id="multiplicity" class="w-full border rounded px-2 py-1 text-sm mb-4">
                                    <option value="1..1">Uno a Uno</option>
                                    <option value="1..*">Uno a Muchos</option>
                                    <option value="*..*">Muchos a Muchos</option>
                                </select>

                                <!-- Botón Agregar Relación -->
                                <button id="addRelationBtn" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    <i class="fas fa-link"></i> Agregar Relación
                                </button>
                            </div>
                            <hr class="border-gray-200 my-2">
                            <div class="mb-4">                                
                                <form id="textForm" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="miTexto" class="block text-sm font-medium text-gray-700">Texto</label>
                                        <textarea id="miTexto" name="miTexto" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="4" required></textarea>
                                    </div>                                                                    
                                    <button type="submit" id="" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Generar</button>    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        
                <!-- Área de Trabajo -->
                <div class="flex-1 flex flex-col">
                    <!-- Barra de Herramientas Superior -->
                    <!-- <div class="bg-white border-b px-2 sm:px-4 py-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 sm:space-x-4">
                                <button id="toggleToolsBtn" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                    <i class="fas fa-tools"></i>
                                </button>
                                <button id="selectBtn" class="tool-btn active" data-tool="select">
                                    <i class="fas fa-mouse-pointer"></i>
                                    <span class="text-xs hidden sm:inline">Seleccionar</span>
                                </button>
                                <button id="panBtn" class="tool-btn" data-tool="pan">
                                    <i class="fas fa-hand-paper"></i>
                                    <span class="text-xs hidden sm:inline">Mover</span>
                                </button>
                                <button id="zoomInBtn" class="tool-btn">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button id="zoomOutBtn" class="tool-btn">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button id="fitBtn" class="tool-btn">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm text-gray-600">Zoom:</span>
                                <span id="zoomLevel" class="text-xs sm:text-sm font-medium">100%</span>
                            </div>
                        </div>
                    </div> -->
        
                    <!-- Canvas -->
                    <div class="flex-1 bg-gray-100 overflow-hidden">
                        <div id="paper" class="w-full h-full"></div>
                    </div>
                </div>
        
                <!-- Panel de Propiedades -->
                <div id="propertiesSidebar" class="w-64 lg:w-64 xl:w-72 bg-white shadow-lg border-l transform translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out fixed lg:relative z-40 h-full right-0">
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Propiedades</h3>
                            <button id="closePropertiesSidebar" class="lg:hidden p-1 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="propertiesPanel">
                            <form id="propertiesForm" class="space-y-4">
                                <div>
                                    <label for="className" class="block text-sm font-medium text-gray-700">Nombre de la Clase</label>
                                    <input type="text" id="className" name="className" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label for="classAttributes" class="block text-sm font-medium text-gray-700">Atributos</label>
                                    <textarea id="classAttributes" name="classAttributes" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" rows="4"></textarea>
                                </div>
                                                                
                                <button type="button" id="savePropertiesBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Guardar</button>
                            </form>
                        </div>                        
                        </br>                        
                        <button type="button" id="borrarClasesBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Borrar Clases</button>
                    </div>
                </div>
                
                <!-- Overlay para móviles -->
                <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>
            </div>
        </div>
        
        <!-- Modal de Compartir -->
        <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                    <div class="px-4 sm:px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Compartir Pizarra</h3>
                    </div>
                    <div class="px-4 sm:px-6 py-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enlace de colaboración</label>
                            <div class="flex flex-col sm:flex-row">
                                <input type="text" id="shareLink" class="flex-1 border border-gray-300 rounded sm:rounded-l px-3 py-2 text-sm mb-2 sm:mb-0" value="{{ $urlCompleta}}" readonly>
                                <button id="copyLink" class="px-4 py-2 bg-blue-600 text-white rounded sm:rounded-r hover:bg-blue-700 text-sm">
                                    Copiar
                                </button>
                            </div>
                        </div>                        
                    </div>
                    <div class="px-4 sm:px-6 py-4 border-t flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button id="closeShareModal" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">Cancelar</button>                        
                    </div>
                </div>
            </div>
        </div>                  

    </main>

<!-- Scripts joint.js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.4.0/backbone-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jointjs/3.7.7/joint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

<script src="{{ asset('js/script_header.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    // Inicializar el graph y el paper
    let graph = new joint.dia.Graph();

    let paper = new joint.dia.Paper({
        el: document.getElementById('paper'),
        model: graph,
        width: '100%',
        height: '100%',
        gridSize: 10,
        drawGrid: true,
        background: { color: '#f9fafb' }
    });
    
    // Colaboracion en tiempo real websockets
    window.Echo.channel('componente')
    .listen('ColaboracionAddClase', (e) => {
        const data = JSON.parse(e.data);   
        const clase = graph.getCell(data.id);
        if (!clase){                            
                let newClass = new joint.shapes.uml.Class(data);
                graph.addCell(newClass);
                console.log('Clase añadida por el servidor:', newClass.get('name'));                        
                updateClassSelectors();
        }else{
            console.log('ID de componente no encontrado');        
        }
    }).listen('ColaboracionClaseMovido', (e) => {
        const data = JSON.parse(e.data);   
        const clase = graph.getCell(data.id);  
        if(clase){
            clase.set('position', data.position);  
        }else{
            console.log('ID de componente no encontrado');     
        }
    }).listen('ColaboracionAddRelacion', (e) => {
        const data = JSON.parse(e.data);             
        console.log('recibido ', data);

        if(data.type === 'uml.Class'){//tabla intermedia muchos a muchos
            let intermediateClass = new joint.shapes.uml.Class(data);
            graph.addCell(intermediateClass);
            updateClassSelectors();

            let link1 = new joint.shapes.uml.Association({
                source: { id: intermediateClass.get('relaciones').source },
                target: { id: intermediateClass.id },
                labels: [
                { position: 0, attrs: { text: { text: '1' } } },
                { position: 1, attrs: { text: { text: '*' } } }
                ]
            });

            let link2 = new joint.shapes.uml.Association({
                source: { id: intermediateClass.get('relaciones').target },
                target: { id: intermediateClass.id },
                labels: [
                { position: 0, attrs: { text: { text: '1' } } },
                { position: 1, attrs: { text: { text: '*' } } }
                ]
            });

            graph.addCells([link1, link2]);  
        }else{
            let labels = [];
            if (data.type !== 'Inheritance') {
                    let mult = separarMultiplicidad(data.multiplicity.multiplicity);
                    // Etiqueta en el origen
                    labels.push({ position: 0, attrs: { text: { text: mult['source'] } } });
                    // Etiqueta en el destino
                    labels.push({ position: 1, attrs: { text: { text: mult['target'] } } });        
            }

            let link;
            switch (data.type) {
                case 'uml.Association':
                    link = new joint.shapes.uml.Association({
                        source: { id: data.source.id },
                        target: { id: data.target.id },
                        labels: data.labels
                    });
                    break;
                case 'uml.Aggregation':
                    link = new joint.shapes.uml.Aggregation({
                        source: { id: data.source.id },
                        target: { id: data.target.id },           
                    });
                    break;
                case 'uml.Composition':
                    link = new joint.shapes.uml.Composition({
                        source: { id: data.source.id },
                        target: { id: data.target.id },
                    });
                    break;
                case 'uml.Generalization':
                    link = new joint.shapes.uml.Generalization({
                        source: { id: data.source.id },
                        target: { id: data.target.id },
                    });
                    break;
            }
            if (link) graph.addCell(link);
        }                                  
    }).listen('ColaboracionGuardarCambios', (e) => {
        const data = JSON.parse(e.data);             
        console.log('Dato de Clase Recibido', data);
        const clase = graph.getCell(data.id);  
        if(clase){
            clase.set('name', data.name);
            clase.set('attributes', data.attributes);   
            updateClassSelectors()         
        }else{
            console.log('ID de componente no encontrado');     
        }
    }).listen('ColaboracionClasesBorradas',(e)=>{                
        graph.clear();
        updateClassSelectors();
    });



    // === Botones de Sidebar  (adicionar Diagrama de clases) ===
    document.getElementById('addClassBtn').addEventListener('click', () => {
        var clase = new joint.shapes.uml.Class({
            position: { x: 100 + Math.random() * 200, y: 100 + Math.random() * 200 },
            size: { width: 150, height: 100 },
            name: 'NuevaClase',
            attributes: [],
            methods: [],
            attrs: {
                '.uml-class-name-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                '.uml-class-attrs-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                '.uml-class-methods-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                '.uml-class-name-text': { fill: '#000' }
            }
        });
        graph.addCell(clase);
        enviarColaboracion('/colaboracion-add-clase',clase);
        console.log('Clase añadida:', clase.get('name'));
        updateClassSelectors();
    });

    // Agregar relación con multiplicidad en ambos extremos
    document.getElementById('addRelationBtn').addEventListener('click', () => {
        const sourceId = document.getElementById('sourceClass').value;
        const targetId = document.getElementById('targetClass').value;
        const type = document.getElementById('relationType').value;
        const multiplicity = document.getElementById('multiplicity').value;

        if (!sourceId || !targetId) {
            alert('Selecciona ambas clases.');
            return;
        }
        if (sourceId === targetId) {
            alert('La clase origen y destino no pueden ser la misma.');
            return;
        }

        let source = graph.getCell(sourceId);
        let target = graph.getCell(targetId);

        if (multiplicity === '*..*' && type !== 'inheritance') {
            // Crear tabla intermedia para relación muchos a muchos
            let intermediateClassName = `${source.attributes.name}_${target.attributes.name}`;
            let intermediateClass = new joint.shapes.uml.Class({
                position: { x: (source.position().x + target.position().x) / 2, y: (source.position().y + target.position().y) / 2 },
                size: { width: 150, height: 100 },
                name: intermediateClassName,
                attributes: [],
                methods: []                
            });
            intermediateClass.set('relaciones',{source:sourceId,target:targetId})            
            graph.addCell(intermediateClass);
            enviarColaboracion('/colaboracion-add-relacion',intermediateClass);
            updateClassSelectors();

            // Crear relaciones 1..* desde cada clase a la tabla intermedia
            let link1 = new joint.shapes.uml.Association({
                source: { id: source.id },
                target: { id: intermediateClass.id },
                labels: [
                { position: 0, attrs: { text: { text: '1' } } },
                { position: 1, attrs: { text: { text: '*' } } }
                ]
            });

            let link2 = new joint.shapes.uml.Association({
                source: { id: target.id },
                target: { id: intermediateClass.id },
                labels: [
                { position: 0, attrs: { text: { text: '1' } } },
                { position: 1, attrs: { text: { text: '*' } } }
                ]
            });

            graph.addCells([link1, link2]);
        } else {
            let labels = [];
            if (type !== 'inheritance') {
                    let mult = separarMultiplicidad(multiplicity);
                    // Etiqueta en el origen
                    labels.push({ position: 0, attrs: { text: { text: mult['source'] } } });
                    // Etiqueta en el destino
                    labels.push({ position: 1, attrs: { text: { text: mult['target'] } } });        
            }

            let link;
            switch (type) {
                case 'association':
                link = new joint.shapes.uml.Association({
                    source: { id: source.id },
                    target: { id: target.id },
                    labels: labels
                });
                break;
                case 'aggregation':
                link = new joint.shapes.uml.Aggregation({
                    source: { id: source.id },
                    target: { id: target.id },            
                });
                break;
                case 'composition':
                link = new joint.shapes.uml.Composition({
                    source: { id: source.id },
                    target: { id: target.id },            
                });
                break;
                case 'inheritance':
                link = new joint.shapes.uml.Generalization({
                    source: { id: source.id },
                    target: { id: target.id }
                });
                break;
            }

            link.set('multiplicity',{multiplicity: multiplicity});
            if (link) graph.addCell(link);
            enviarColaboracion('/colaboracion-add-relacion',link);
        }        
    });


    // === Panel de Propiedades ===
    document.addEventListener('DOMContentLoaded', () => {
        const propertiesSidebar = document.getElementById('propertiesSidebar');
        const propertiesForm = document.getElementById('propertiesForm');
        const savePropertiesBtn = document.getElementById('savePropertiesBtn');

        paper.on('cell:pointerclick', (cellView, evt, x, y) => {
        if (cellView.model.isElement() && cellView.model instanceof joint.shapes.uml.Class) {
            const clase = cellView.model;
            const className = clase.get('name') || '';
            const classAttributes = clase.get('attributes').join('\n') || '';            

            propertiesSidebar.classList.remove('translate-x-full');
            propertiesForm.className.value = className;
            propertiesForm.classAttributes.value = classAttributes;
            
            //Guardar referencia a la clase seleccionada para el guardado posterior
            propertiesForm.dataset.selectedClassId = clase.id;
        }
    });

    //Guardar cambios
    savePropertiesBtn.addEventListener('click', () => {        
        const updatedClassName = document.getElementById('className').value;
        const updatedAttributes = document.getElementById('classAttributes').value;


        console.log('Guardando cambios:', {
            updatedClassName,
            updatedAttributes,
        });
        const selectedClassId = propertiesForm.dataset.selectedClassId; 
        if (selectedClassId) {
            const clase = graph.getCell(selectedClassId);
            if (clase && clase instanceof joint.shapes.uml.Class) {
                clase.set('name', updatedClassName);
                clase.set('attributes', updatedAttributes.split('\n').filter(attr => attr.trim() !== ''));
                clase.resize(clase.size().width, 100 + Math.max(clase.get('attributes').length, clase.get('methods').length) * 20);
                console.log('Clase actualizada:', clase.toJSON());
                updateClassSelectors();
                enviarColaboracion('/colaboracion-guardar-cambios',clase);
            }
        }       

        });
    }); 

    // Detectar movimiento de elementos
    paper.on('element:pointerup', function(cellView) {
        console.log('elemento movido');
        const clase = cellView.model;
        enviarColaboracion('/colaboracion-clase-movido', clase);
    });

    //Borrar todas las clases
    document.getElementById('borrarClasesBtn').addEventListener('click', () => {        
        graph.clear();
        updateClassSelectors();
        enviarColaboracion('/colaboracion-clases-borradas',{});
    });

    // cargar Imagen
    document.getElementById('cargarImgBtn').addEventListener('click', () => {
        document.getElementById('abrirArchivo').click();
    });

    document.getElementById('abrirArchivo').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                console.log('Archivo imagen:', file);                
                enviarImagen("/cargarImagen", file);
            } else {
                console.error('El archivo seleccionado no es una imagen.');
                alert('Por favor, selecciona un archivo de imagen válido.');
            }
        } else {
            console.log('No se seleccionó ningún archivo.');
        }
    });

    //prompt
    document.getElementById('textForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío del formulario por defecto

        const prompt = document.getElementById('miTexto').value.trim();
        if (prompt === '') {
            alert('Por favor, ingresa un prompt válido.');
            return;
        }
        document.getElementById('miTexto').value = ''; // Limpiar el textarea

        // Enviar el prompt al servidor
        fetch('/addTexto', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(prompt)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data.respuesta);
            crearDiseño(data.respuesta);          
        })
        .catch(error => {
            console.error('Error al enviar el prompt:', error);           
        });
    });

    //funciones
    function updateClassSelectors() {
        var classes = graph.getElements();
        const sourceSelect = document.getElementById('sourceClass');
        const targetSelect = document.getElementById('targetClass');
        sourceSelect.innerHTML = '';
        targetSelect.innerHTML = '';

        classes.forEach(cls => {
            let option1 = document.createElement('option');
            option1.value = cls.id;
            option1.text = cls.attributes.name;
            sourceSelect.appendChild(option1);

            let option2 = document.createElement('option');
            option2.value = cls.id;
            option2.text = cls.attributes.name;
            targetSelect.appendChild(option2);
        });
    }

    function separarMultiplicidad( multiplicity ) {
        const parts = multiplicity.split('..');
        // Ejemplo de multiplicidad: "1..*", "*..*"
        if (parts.length === 2) {
            switch(multiplicity){
              case '1..1':                
                parts[0] = '1..1';
                parts[1] = '1..1'; 
                break;
              case '1..*':
                parts[0] = '1..1';
                parts[1] = '1..*'; 
                break;
              default:
                parts[0] = '1..*';
                parts[1] = '1..*'; 
            }
            return { source: parts[0], target: parts[1] };
        }
        //return { source: '1', target: '1' }; // Valor por defecto        
        return null;
    }

    function enviarColaboracion(url, clase){
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            //body: JSON.stringify({ element: element.toJSON() })
            //body: JSON.stringify(clase.toJSON()) estaba usando antes del borrar clases
            body: JSON.stringify(clase)
        })
        .then(response => response.json())
        .then(data => {
            console.log('SendColaboración:', data);
        })
        .catch(error => {
            console.error('Error al enviar colaboración:', error);
        });
    };

    //enviar boceto al servidor
    function enviarImagen(route,file){
        const formData = new FormData();
        formData.append('image', file);   
        fetch(route, {    
            method: 'POST',
            headers: {  
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        })
        .then(response => response.json())            
        .then(data => { 
            console.log('Image processed successfully:', data.respuesta);            
            crearDiseño(data.respuesta);
        })
        .catch(error => {             
            console.error('Error processing image:', error);            
        });
    }

    function crearDiseño(data){
        data.cells.forEach((item) => {        
            //crear las clases
            if(item.type === 'uml.Class'){
                let clase = new joint.shapes.uml.Class({                    
                    id: item.id,
                    position: item.position || { x: 100, y: 100 },
                    size: item.size || { width: 150, height: 100 },
                    name: item.name || 'NuevaClase',
                    attributes: item.attributes || [],
                    methods: [],
                    attrs: {
                        '.uml-class-name-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                        '.uml-class-attrs-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                        '.uml-class-methods-rect': { fill: '#bfdbfe', stroke: '#000', 'stroke-width': 1 },
                        '.uml-class-name-text': { fill: '#000' }
                    }
                });
                graph.addCell(clase);                
                console.log('Clase añadida:', clase.get('name'));
                updateClassSelectors();                
            }
            else{
                let labels = [];                             
                let link;
                switch (item.type) {
                    case 'uml.Association':
                        link = new joint.shapes.uml.Association({
                            id: item.id,
                            source: { id: item.source.id },
                            target: { id: item.target.id },
                            labels: item.labels                            
                        });                        
                        break;
                    case 'uml.Aggregation':
                        link = new joint.shapes.uml.Aggregation({
                            id: item.id,
                            source: { id: item.source.id },
                            target: { id: item.target.id },
                        });
                        break;
                    case 'uml.Composition':
                        link = new joint.shapes.uml.Composition({
                            id: item.id,
                            source: { id: item.source.id },
                            target: { id: item.target.id },
                        });
                        break;
                    case 'uml.Generalization':
                        link = new joint.shapes.uml.Generalization({
                            id: item.id,
                            source: { id: item.source.id },
                            target: { id: item.target.id },
                        });
                        break;
                }
                if (link) graph.addCell(link);
            }               
        });
    }

</script>


</body>
</html>