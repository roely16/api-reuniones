<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use Barryvdh\DomPDF\Facade as PDF;

    use App\Persona;

    class PDFController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }

        public function generar(Request $request){

            $personas = Persona::all();

            $data = [
                "personas" => $personas
            ];

            $pdf = PDF::loadView('pdf', $data);

            return $pdf->stream('prueba.pdf');

        }

        //
    }

?>