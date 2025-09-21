<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReunionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * muestra la vista de reuniones
     */
    public function index()
    {
        return view('diagramas.reunion.reunion');
    }

    /**
     * crea una nueva reunion y redirige a la pizarra
     *
     * @return \Illuminate\Http\Response
     */    
    public function create()
    {
        // Generar un código único y el enlace
        $codigoUnico = Str::uuid();
        $link = route('pizarra', [$codigoUnico]);

        // Crear la reunión
        $reunion = new Reunion();
        $reunion->fecha = Carbon::now()->toDateString();
        $reunion->id_usuario = auth()->user()->id;
        $reunion->link = $link;
        $reunion->rol_acceso = 'Administrador';
        $reunion->estado = 'valido';

        try {
            $reunion->save();
            return redirect()->route('pizarra', [$codigoUnico])->with('success', 'Reunión creada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la reunión: ' . $e->getMessage());
        }
    }

    /**
     * Permite unirse a una reunion existente
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function join(Request $request)
    {
        $link = $request->get('collaborationId');   
        $divLink = explode("/",$link);
        $codigoUnico = end($divLink);   
        $reuniones = Reunion::where('link',$link)->get();  
        
        if(empty( trim($link)) || count($reuniones) === 0 ){//no hay link o no existe ese link
            return redirect('/reunion');
        }else{                                              
            foreach($reuniones as $reunion){
                if($reunion->rol_acceso === "Administrador" && $reunion->estado === "no valido"){
                    return redirect('/reunion');
                }else{
                    if($reunion->id_usuario === auth()->user()->id && $link === $reunion->link){
                        //ya existe admin o invitado y es valido entonces redirige                        
                        return redirect()->route('pizarra',[$codigoUnico]);
                    }
                }
            }            
            $reunion = new Reunion();
            $reunion->fecha = Carbon::now()->toDateString();
            $reunion->id_usuario = auth()->user()->id;
            $reunion->link = $link;
            $reunion->rol_acceso = 'Invitado';
            $reunion->estado = 'valido';
            $reunion->save();                
            return redirect()->route('pizarra',[$codigoUnico]);                                
        }
    }
    

    /**
     * Finaliza la reunion y cambia su estado a no valido
     *
     * @return \Illuminate\Http\Response
     */    
    function finalizar()
    {
        $url = url()->previous();
        $userActual = auth()->user();

        // Verificar si el usuario tiene permisos de administrador
        $permiso = Reunion::where('id_usuario', $userActual->id)
                        ->where('link', $url)
                        ->first();

        if (!$permiso || $permiso->rol_acceso !== "Administrador") {            
            return redirect('/reunion')->with('error', 'No tienes permiso para finalizar esta reunión.');
        }

        // Actualizar todas las reuniones asociadas al enlace
        Reunion::where('link', $url)->update(['estado' => 'no valido']);

        return redirect('/reunion')->with('success', 'Reunión finalizada correctamente.');
    }


}
