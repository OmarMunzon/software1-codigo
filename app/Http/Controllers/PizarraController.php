<?php

namespace App\Http\Controllers;

use App\Events\ColaboracionAddClase;
use App\Events\ColaboracionAddRelacion;
use App\Events\ColaboracionClaseMovido;
use App\Events\ColaboracionClasesBorradas;
use App\Events\ColaboracionGuardarCambios;
use App\Models\Pizarra;
use App\Models\Reunion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;

use Illuminate\Support\Facades\File;
use ZipArchive;

class PizarraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra la vista de la pizarra
     *
     * @return \Illuminate\View\View
     */
    function index()
    {
        $urlCompleta = request()->url();//ruta actual        
        return view('diagramas.pizarra.pizarra',compact('urlCompleta'));        
    }
    
    /**
     * Guarda el estado de la pizarra en la base de datos
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardar(Request $request){

        $data = $request->json()->all();
        $url = url()->previous();
        $reunion = Reunion::where('link',$url)->get();

        $pizarra = new Pizarra();
        $pizarra->namefile = $data['nameFile'];
        $pizarra->fecha = Carbon::now()->toDateString();
        $pizarra->id_reunion =$reunion[0]->id;        
        return response()->json(['status' => true]);
    }
    
    /**
     * Maneja la adición de una clase en colaboración
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function colaboracionAddClase(Request $request)
    {
        $data = $request->all();
        $data = json_encode($data);  
        broadcast(new ColaboracionAddClase($data))->toOthers();
        return response()->json(['status' => 'success']);
    }

    public function colaboracionClaseMovido(Request $request)
    {
        $data = $request->all();
        $data = json_encode($data);  
        broadcast(new ColaboracionClaseMovido($data))->toOthers();
        return response()->json(['status' => 'success']);
    }
    
    public function colaboracionAddRelacion(Request $request)
    {
        $data = $request->all();
        $data = json_encode($data);  
        broadcast(new ColaboracionAddRelacion($data))->toOthers();
        return response()->json(['status' => 'success']);
    }
    
    public function colaboracionGuardarCambios(Request $request)
    {
        $data = $request->all();
        $data = json_encode($data);  
        broadcast(new ColaboracionGuardarCambios($data))->toOthers();
        return response()->json(['status' => 'success']);
    }
    
    public function colaboracionClasesBorradas(Request $request)
    {
        $data = $request->all();
        $data = json_encode($data);  
        broadcast(new ColaboracionClasesBorradas($data))->toOthers();
        return response()->json(['status' => 'success']);
    }

    /**
     * Carga una imagen, la envía a la API de Gemini para generar un JSON en formato JointJS
     * y devuelve el JSON generado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cargarImagen(Request $request)
    {
        // Validar si llegó la imagen
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json(['error' => 'No se recibió una imagen válida'], 400);
        }

        // Obtener la imagen y codificarla a base64
        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));
        $mimeType = $image->getMimeType(); // ej: 'image/png'

        // Construir payload
        $apiKey = env('GEMINI_API_KEY');
        
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [                                                
                        ['text' => 'generar un json de la imagen con el formato de JointJS para un diagrama de clases (uml.Class),las relaciones usan (uml.Association,uml.Aggregation,uml.Composition,uml.Generalization) para la multiplicidad usar (1,*) y ambos deben tener un campo "id" con un UUID v4 único alfanumérico.'],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Enviar petición
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        
         // Procesar respuesta
        if ($response->successful()) {
            $data = $response->json();

            // Verificar que existan candidatos
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json(['error' => 'Respuesta vacía de Gemini'], 500);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'];

            // Extraer JSON (soporta objeto { } o array [ ])
            if (preg_match('/(\{(?:[^{}]|(?R))*\}|\[.*\])/s', $texto, $matches)) {
                $soloJson = $matches[0];
            } else {
                $soloJson = $texto; // Si no encuentra, devuelve todo
            }

            // Validar JSON
            $decoded = json_decode($soloJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'JSON inválido en respuesta de Gemini',
                    'texto' => $texto
                ], 500);
            }

            return response()->json([
                'respuesta' => $decoded
            ]);
        }

        return response()->json([
            'error' => 'Error de API',
            'detalle' => $response->body()
        ], 500); 
    }

    /**
     * Genera un diagrama de clases en formato JointJS basado en una descripción textual
     * usando la API de Gemini.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTexto(Request $request)
    {                
        $prompt = $request->all();
        $texto = $prompt[0];        
        
        //Construir payload
        $apiKey = env('GEMINI_API_KEY');
        
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [                                                
                        [
                            'text' => 'El diagrama de clases: '.$texto .'Generar un json con el formato de JointJS para un diagrama de clases (type,uml.Class,name,attributes),las relaciones usan (uml.Association,uml.Aggregation,uml.Composition,uml.Generalization,source,target) para la multiplicidad usar (labels,1,*) y ambos deben tener un campo "id" con un UUID v4 único alfanumérico.'                            
                        ],                        
                    ]
                ]
            ]
        ];

        // Enviar petición
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        
         // Procesar respuesta
        if ($response->successful()) {
            $data = $response->json();

            // Verificar que existan candidatos
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json(['error' => 'Respuesta vacía de Gemini'], 500);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'];

            // Extraer JSON (soporta objeto { } o array [ ])
            if (preg_match('/(\{(?:[^{}]|(?R))*\}|\[.*\])/s', $texto, $matches)) {
                $soloJson = $matches[0];
            } else {
                $soloJson = $texto; // Si no encuentra, devuelve todo
            }

            // Validar JSON
            $decoded = json_decode($soloJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'JSON inválido en respuesta de Gemini',
                    'texto' => $texto
                ], 500);
            }

            return response()->json([
                'respuesta' => $decoded
            ]);
        }

        return response()->json([
            'error' => 'Error de API',
            'detalle' => $response->body()
        ], 500); 
    }

    /**
     * Genera el backend (spring boot) en base al diagrama de clases actual.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarBackend(Request $request)
    {
        //$data = $request->all();
        $graphData = $request->input('graph_data'); // JSON recibido
        $data = json_decode($graphData,true);  
        //dd(gettype($data),$data["cells"]);
        $resultado= $this->filterUmlData($data);
        //dd($resultado);
        // Directorio temporal para el proyecto
        $projectName = 'SpringBootProject_' . time();
        $tempDir = storage_path('app/temp/' . $projectName);
        File::makeDirectory($tempDir, 0755, true, true);

        // Estructura de directorios de Spring Boot
        $directories = [
            $tempDir . '/src/main/java/com/example/demo',
            $tempDir . '/src/main/java/com/example/demo/controller',
            $tempDir . '/src/main/java/com/example/demo/model',
            $tempDir . '/src/main/java/com/example/demo/repository',
            $tempDir . '/src/main/java/com/example/demo/service',
            $tempDir . '/src/main/resources',
            $tempDir . '/src/test/java/com/example/demo',
        ];

        foreach ($directories as $dir) {
            File::makeDirectory($dir, 0755, true, true);
        }

        // Crear archivo pom.xml
        $pomContent = $this->getPomXmlContent();
        File::put($tempDir . '/pom.xml', $pomContent);

        // Crear application.properties
        $appPropertiesContent = $this->getApplicationPropertiesContent();
        File::put($tempDir . '/src/main/resources/application.properties', $appPropertiesContent);

        // Crear clase principal de Spring Boot
        $mainClassContent = $this->getMainClassContent();
        File::put($tempDir . '/src/main/java/com/example/demo/DemoApplication.java', $mainClassContent);

        foreach ($resultado['classes'] as $class) {
            // Crear entidad
            //$entityContent = $this->getEntityContent($class,$resultado['relationships']);
            $entityContent = $this->getEntityContent($class);
            File::put($tempDir . '/src/main/java/com/example/demo/model/'.(ucfirst($class['name'])).'.java', $entityContent);

            // Crear repositorio
            $repositoryContent = $this->getRepositoryContent($class);
            File::put($tempDir . '/src/main/java/com/example/demo/repository/'.(ucfirst($class['name'])).'Repository.java', $repositoryContent);

            // Crear servicio
            $serviceContent = $this->getServiceContent($class);
            File::put($tempDir . '/src/main/java/com/example/demo/service/'.(ucfirst($class['name'])).'Service.java', $serviceContent);

            // Crear controlador REST
            $controllerContent = $this->getControllerContent($class);
            File::put($tempDir . '/src/main/java/com/example/demo/controller/'.ucfirst($class['name']).'Controller.java', $controllerContent);
        }

        // Crear archivo ZIP
        $zipFile = storage_path('app/temp/' . $projectName . '.zip');
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $this->addFilesToZip($tempDir, $zip, $projectName);
            $zip->close();
        }

        // Descargar el archivo ZIP
        return response()->download($zipFile, $projectName . '.zip')->deleteFileAfterSend(true);

    }

    private function addFilesToZip($dir, $zip, $baseDir)
    {
        $files = File::allFiles($dir);
        foreach ($files as $file) {
            $relativePath = str_replace($dir, $baseDir, $file->getPathname());
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }

    private function getPomXmlContent()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <project xmlns="http://maven.apache.org/POM/4.0.0"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 https://maven.apache.org/xsd/maven-4.0.0.xsd">
            <modelVersion>4.0.0</modelVersion>
            <parent>
                <groupId>org.springframework.boot</groupId>
                <artifactId>spring-boot-starter-parent</artifactId>
                <version>3.3.4</version>
                <relativePath/>
            </parent>
            <groupId>com.example</groupId>
            <artifactId>demo</artifactId>
            <version>0.0.1-SNAPSHOT</version>
            <name>demo</name>
            <description>Demo project for Spring Boot</description>
            <properties>
                <java.version>17</java.version>
            </properties>
            <dependencies>
                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-web</artifactId>
                </dependency>
                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-data-jpa</artifactId>
                </dependency>
                <dependency>
                    <groupId>org.postgresql</groupId>
                    <artifactId>postgresql</artifactId>
                    <scope>runtime</scope>
                </dependency>
                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-test</artifactId>
                    <scope>test</scope>
                </dependency>
            </dependencies>
            <build>
                <plugins>
                    <plugin>
                        <groupId>org.springframework.boot</groupId>
                        <artifactId>spring-boot-maven-plugin</artifactId>
                    </plugin>
                </plugins>
            </build>
        </project>';
    }

    private function getApplicationPropertiesContent()
    {
        return 'spring.datasource.url=jdbc:postgresql://localhost:5432/db_spring_boot
        spring.datasource.username=postgres
        spring.datasource.password=root
        spring.jpa.hibernate.ddl-auto=update
        spring.jpa.show-sql=true
        spring.jpa.properties.hibernate.dialect=org.hibernate.dialect.PostgreSQLDialect';
    }

    private function getMainClassContent()
    {
        return 'package com.example.demo;

        import org.springframework.boot.SpringApplication;
        import org.springframework.boot.autoconfigure.SpringBootApplication;

        @SpringBootApplication
        public class DemoApplication {
            public static void main(String[] args) {
                SpringApplication.run(DemoApplication.class, args);
            }
        }';
    }

    private function getEntityContent($class)
    {
        $className = $class['name'];
        $attributes = $class['attributes'];
               
        $nuevaClase = 'package com.example.demo.model;
        
        import jakarta.persistence.*;
        import java.util.List;

        @Entity
        public class '.ucfirst($className).' {
            @Id
            @GeneratedValue(strategy = GenerationType.IDENTITY)';
        $nuevaClase .= "\n";
        
            // Procesar cada atributo -> crear atributos privados
            foreach ($attributes as $attr) {
                list($name, $type) = explode(":", $attr);
                $name = trim($name);
                switch (trim($type)) {
                    case "int":
                        $javaType = "int";
                        break;
                    case "string":
                        $javaType = "String";
                        break;                    
                    case "float":
                        $javaType = "float";
                        break;
                    case "double":
                        $javaType = "double";
                        break;
                    case "boolean":
                        $javaType = "boolean";
                        break;                                        
                    default:
                        $javaType = "String";
                }

                // Definir atributo privado
                $nuevaClase .= "    private $javaType $name;\n";
            }

            $nuevaClase .= "\n";

            // Procesar cada atributo -> crear getters y setters
            foreach ($attributes as $attr) {
                list($name, $type) = explode(":", $attr);
                $name = trim($name);
                
                switch (trim($type)) {
                    case "int":
                        $javaType = "int";
                        break;
                    case "string":
                        $javaType = "String";
                        break;                    
                    case "float":
                        $javaType = "float";
                        break;
                    case "double":
                        $javaType = "double";
                        break;
                    case "boolean":
                        $javaType = "boolean";
                        break;                                        
                    default:
                        $javaType = "String";
                }

                // Nombre del método con la primera letra en mayúscula
                $methodName = ucfirst($name);

                 // Getter
                $nuevaClase .= "    public $javaType get$methodName() {\n";
                $nuevaClase .= "        return $name;\n";
                $nuevaClase .= "    }\n\n";

                // Setter
                $nuevaClase .= "    public void set$methodName($javaType $name) {\n";
                $nuevaClase .= "        this.$name = $name;\n";
                $nuevaClase .= "    }\n\n";
            }
        $nuevaClase .='}';

        return $nuevaClase;
    }

    private function getRepositoryContent($class)
    {
        return 'package com.example.demo.repository;
        import com.example.demo.model.'.ucfirst($class['name']).';
        import org.springframework.data.jpa.repository.JpaRepository;

        public interface '.ucfirst($class['name']).'Repository extends JpaRepository<'.ucfirst($class['name']).', Integer> {
        }';
    }

     private function getServiceContent($class)
    {
        $className = $class['name'];
        
        $newService = 'package com.example.demo.service;
        
        import org.springframework.stereotype.Service;
        import com.example.demo.repository.'.ucfirst($className).'Repository;
        import com.example.demo.model.'.ucfirst($className).';

        import java.util.List;
        import java.util.Optional;

        @Service
        public class '.ucfirst($className).'Service {
            
            private final '.ucfirst($className).'Repository repository;

            public '.ucfirst($className).'Service('.ucfirst($className).'Repository repository) {
                this.repository = repository;
            }

            public List<'.ucfirst($className).'> getAll() {
                return repository.findAll();
            }
            
            public Optional<'.ucfirst($className).'> getById(Integer id) {
                return repository.findById(id);
            }
            
            public '.ucfirst($className).' save('.ucfirst($className).' '.$className.') {
                return repository.save('.$className.');
            }
            
            public void delete(Integer id) {
                repository.deleteById(id);
            }
        }';
        return $newService;
    }

    private function getControllerContent($class)
    {
        $className = $class['name'];

        return 'package com.example.demo.controller;
        
        import org.springframework.web.bind.annotation.*;
        import com.example.demo.service.'.ucfirst($className).'Service;
        import com.example.demo.model.'.ucfirst($className).';
        import org.springframework.http.ResponseEntity;
        import java.util.List;

        @RestController
        @RequestMapping("/'.$className.'s")
        public class '.ucfirst($className).'Controller {

            private final '.ucfirst($className).'Service service;

            public '.ucfirst($className).'Controller('.ucfirst($className).'Service service) {
                this.service = service;
            }

            @GetMapping
            public List<'.ucfirst($className).'> getAll() {
                return service.getAll();
            }

            @GetMapping("/{id}")
            public ResponseEntity<'.ucfirst($className).'> getById(@PathVariable Integer id) {
                return service.getById(id)
                        .map(ResponseEntity::ok)
                        .orElse(ResponseEntity.notFound().build());
            }

            @PostMapping
            public '.ucfirst($className).' create(@RequestBody '.ucfirst($className).' '.$className.') {                
                return service.save('.$className.');
            }

            @PutMapping("/{id}")
            public ResponseEntity<'.ucfirst($className).'> update(@PathVariable Integer id, @RequestBody '.ucfirst($className).' '.$className.') {
                 return service.getById(id)
                        .map(existing -> {
                            
                            return ResponseEntity.ok(service.save(existing));
                        })
                        .orElse(ResponseEntity.notFound().build());
            }

            @DeleteMapping("/{id}")
            public ResponseEntity<Void> delete(@PathVariable Integer id) {
                if (service.getById(id).isPresent()) {
                    service.delete(id);
                    return ResponseEntity.noContent().build();
                }
                return ResponseEntity.notFound().build();
            }
        }';
    }
 
    

     public function filterUmlData($data)
    {
        
        $filteredData = [
            'classes' => [],
            'relationships' => []
        ];

        // Mapa para traducir IDs a nombres de clases
        $classIdToName = [];

        // Procesar cada celda del diagrama
        foreach ($data['cells'] as $cell) {
            // Filtrar clases
            if ($cell['type'] === 'uml.Class') {
                $classIdToName[$cell['id']] = $cell['name'];
                $filteredData['classes'][] = [
                    'name' => $cell['name'],
                    'attributes' => $cell['attributes'] ?? []
                ];
            }
            // Filtrar relaciones
            elseif (in_array($cell['type'], ['uml.Association', 'uml.Aggregation', 'uml.Composition','uml.Generalization'])) {
                $relationship = [
                    'type' => $cell['type'],
                    'source' => isset($cell['source']['id']) ? ($cell['source']['id'] ?? 'Unknown') : 'Unknown',
                    'target' => isset($cell['target']['id']) ? ($cell['target']['id'] ?? 'Unknown') : 'Unknown',
                    // 'source' => isset($cell['source']['id']) ? ($classIdToName[$cell['source']['id']] ?? 'Unknown') : 'Unknown',
                    // 'target' => isset($cell['target']['id']) ? ($classIdToName[$cell['target']['id']] ?? 'Unknown') : 'Unknown',
                    'multiplicity' => $cell['multiplicity']['multiplicity'] ?? null,
                    'labels' => $cell['labels'] ?? []
                ];
                $filteredData['relationships'][] = $relationship;
            }
        }

        return $filteredData;
    }
}
