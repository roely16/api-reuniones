<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade as PDF;

use App\MetodoReunion;
use App\Usuario;
use App\Persona;
use App\Empleado;
use App\Area;

class VistaPreviaController extends Controller{

    public function procesar(Request $request){

        PDF::setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);

        $encabezado = (object) $request->encabezado;

        // Buscar el método de la reunión
        $metodo = MetodoReunion::find($encabezado->metodo);
        $encabezado->nombre_metodo = $metodo ? $metodo->nombre : null;

        // Buscar el responsable de redactar la minuta
        $persona = Persona::find($encabezado->id_responsable);

        if ($persona->nit) {
            
            $usuario_rrhh = Empleado::find($persona->nit);
            $area = Area::find($usuario_rrhh->codarea);

            $encabezado->seccion = $area->descripcion;
            $encabezado->codarea = $area->codarea;

        }

        $data = [
            'encabezado' => $encabezado,
            'puntos_agenda' => $request->puntos_agenda,
            'pendientes' => $request->pendientes,
            'participantes' => $request->participantes
        ];

        $pdf = PDF::loadView('formato_reunion', $data);

        $pdf->save('pdf/temp.pdf');

        $data = [
            "pdf_url" => "pdf/temp.pdf#toolbar=0"
        ];
        
        return response()->json($data);

    }

}

?>