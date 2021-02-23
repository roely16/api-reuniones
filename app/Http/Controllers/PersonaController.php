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


            }else{

                // Validar si tengo participación pero no de grupo

                $hoy = date('Y-m-d');

                $evento = app('db')->select("   SELECT *
                                                FROM calendario
                                                WHERE fecha = '$hoy'
                                                AND id_persona = $usuario->id_persona
                                                AND grupo IS NULL");

                $participacion = $evento ? true : false;

                // Si se tiene participación obtener los administradores y asesores
                if ($participacion) {
                    
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

                    // Agregar las personas del grupo

                    // Colocar al final
                    $divider = [
                        "divider" => true
                    ];

                    array_push($personas, $divider);

                    $header = [
                        "header" => "Grupo"
                    ];

                    array_push($personas, $header);

                    // Integrantes del grupo
                    $items = app('db')->select("    SELECT t2.*, CONCAT(t2.nombres, ' ', t2.apellidos) as nombre
                                                    FROM grupo_participante t1
                                                    INNER JOIN persona t2
                                                    ON t1.id_persona = t2.id
                                                    WHERE t1.id_grupo IN (
                                                        SELECT id_grupo
                                                        FROM grupo_participante
                                                        WHERE id_persona = $usuario->id_persona
                                                    )
                                                    AND t2.deleted_at IS NULL"
                                            );

                    foreach ($items as &$item) {

                        $item->group = "Grupo";

                        array_push($personas, $item);

                    }

                }else{

                    // Integrantes del grupo
                    $personas = app('db')->select("     SELECT t2.*, CONCAT(t2.nombres, ' ', t2.apellidos) as nombre
                                                        FROM grupo_participante t1
                                                        INNER JOIN persona t2
                                                        ON t1.id_persona = t2.id
                                                        WHERE t1.id_grupo IN (
                                                            SELECT id_grupo
                                                            FROM grupo_participante
                                                            WHERE id_persona = $usuario->id_persona
                                                        )
                                                        AND t2.deleted_at IS NULL"
                                                );

                    foreach ($personas as &$item) {

                        $item->group = "Grupo";

                    }

                    // Colocar al inicio
                    $header = [
                        "header" => "Grupo"
                    ];

                    array_unshift($personas, $header);

                    // Colocar al final
                    $divider = [
                        "divider" => true
                    ];

                    array_push($personas, $divider);

                }

            }

            return response()->json($personas);

        }

    }


?>