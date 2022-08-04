<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade as PDF;

use App\MetodoReunion;
use App\Usuario;
use App\Persona;
use App\Empleado;
use App\Area;

use App\ReunionEnvio;
use App\ReunionEnvioDetalle;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShareMail;
use App\Reunion;

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

        // Participantes obtener el área o coordinación 
        $participantes = $request->participantes;

        foreach ($participantes as &$participante) {
            
            $area = Area::find($participante['codarea']);

            $participante['area'] = $area->descripcion;

        }

        $data = [
            'encabezado' => $encabezado,
            'puntos_agenda' => $request->puntos_agenda,
            'pendientes' => $request->pendientes,
            'participantes' => $participantes
        ];

        $pdf = PDF::loadView('formato_reunion', $data);

        $pdf->save('pdf/temp.pdf');

        // Si se debe compartir
        if ($request->share) {
            
            // Guardar el archivo que se enviara 
            $uniqid = uniqid();

            $pdf->save('pdf/' . $uniqid . '.pdf');
            $filename = $uniqid . '.pdf';

            $destinos = $request->destinos;

            // Enviar a todos los destinos
            foreach ($destinos as &$destino) {
                
                $destino = (object) $destino;

                $data = [
                    'filename' => $filename,
                    'encabezado' => $encabezado,
                    'destino' => $destino
                ];

                Mail::to(trim($destino->email))->send(new ShareMail($data));

            }

            // Registra el envio
            $envio = new ReunionEnvio();
            $envio->id_reunion = $encabezado->id;
            $envio->documento = $filename;
            $envio->enviado_por = $encabezado->id_responsable;
            $envio->save();

            // Registrar los destinatarios 
            foreach ($destinos as &$destino) {
                
                $destino = (object) $destino;

                $envio_detalle = new ReunionEnvioDetalle();
                $envio_detalle->id_envio = $envio->id;
                $envio_detalle->id_persona = $destino->id;
                $envio_detalle->email = $destino->email;
                $envio_detalle->save();

            }


            return response()->json('share');

        }

        $data = [
            "pdf_url" => "pdf/temp.pdf#toolbar=0"
        ];
        
        return response()->json($data);

    }

}

?>