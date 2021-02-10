<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use Barryvdh\DomPDF\Facade as PDF;

    use App\Persona;

    class PDFController extends Controller{
        
        public function generar_vistaprevia(Request $request){

            //PDF::loadHTML($request->content)->setPaper('letter', 'portrait')->setWarnings(false)->save('pdf/temp.pdf');

            $pdf = PDF::loadView('pdf', $request);
            $pdf->save('pdf/temp.pdf');

            $data = [
                "pdf_url" => "pdf/temp.pdf"
            ];

            return response()->json($data);

        }

    }

?>