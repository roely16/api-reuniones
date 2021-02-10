<?php 

    namespace App\Http\Controllers;

    use Laravel\Lumen\Routing\Controller as BaseController;

    use Illuminate\Http\Request;

    use App\Persona;

    class PersonaController extends BaseController{
        
        public function personas_compartir(Request $request){

            $personas = Persona::all();

            foreach ($personas as &$persona) {
                
                $persona->nombre = $persona->nombres . ' ' . $persona->apellidos;

            }

            return response()->json($personas);

        }

    }


?>