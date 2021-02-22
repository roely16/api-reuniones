<?php 

    namespace App\Http\Controllers;

    use Laravel\Lumen\Routing\Controller as BaseController;

    use Illuminate\Http\Request;

    use App\Persona;
    use App\Usuario;
    use App\Rol;

    class PersonaController extends BaseController{
        
        public function personas_compartir(Request $request){

            $usuario = usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->admin) {
                
                $personas = app('db')->select(" SELECT t1.*
                                                FROM persona t1
                                                INNER JOIN usuario t2
                                                ON t1.id = t2.id_persona
                                                WHERE t2.id_rol = 1");

                foreach ($personas as &$persona) {
                                
                    $persona->nombre = $persona->nombres . ' ' . $persona->apellidos;
                    $persona->group = "Administradores";

                }

                // Colocar al inicio
                $header = [
                    "header" => "Administradores"
                ];

                array_unshift($personas, $header);

                // Colocar al final
                $divider = [
                    "divider" => true
                ];

                array_push($personas, $divider);

                $header = [
                    "header" => "Asesores"
                ];

                array_push($personas, $header);

                $items = app('db')->select("    SELECT t1.*
                                                FROM persona t1
                                                INNER JOIN usuario t2
                                                ON t1.id = t2.id_persona
                                                WHERE t2.id_rol = 2");

                foreach ($items as &$item) {

                    $item->nombre = $item->nombres . ' ' . $item->apellidos;
                    $item->group = "Asesores";

                    array_push($personas, $item);

                }


            }

            return response()->json($personas);

        }

    }


?>