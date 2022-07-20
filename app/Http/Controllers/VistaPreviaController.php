<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade as PDF;

class VistaPreviaController extends Controller{

    public function procesar(Request $request){

        PDF::setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);

        $data = [
            'encabezado' => $request->encabezado,
            'puntos_agenda' => $request->puntos_agenda,
            'pendientes' => $request->pendientes,
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