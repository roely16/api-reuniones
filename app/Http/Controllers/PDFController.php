<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use Barryvdh\DomPDF\Facade as PDF;

    use App\Persona;

    class PDFController extends Controller{
        
        public function generar_vistaprevia(Request $request){

            $meses = [
                "enero",
                "febrero",
                "marzo",
                "abril",
                "mayo",
                "junio",
                "julio",
                "agosto",
                "septiembre",
                "octubre",
                "noviembre",
                "diciembre"
            ];

            $fecha = "Guatemala " . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

            $data_pdf = [
                "content" => $request->content,
                "fecha" => $fecha
            ];

            PDF::setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);
            $pdf = PDF::loadView('pdf', $data_pdf);
            $pdf->save('pdf/temp.pdf');

            $data = [
                "pdf_url" => "pdf/temp.pdf"
            ];
            
            return response()->json($data);

        }

        public function test(){

            PDF::setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);
            $pdf = PDF::loadView('pdf');
            $pdf->setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);

            //return $pdf->stream();

            return response(view('pdf'));

        }   

    }

?>