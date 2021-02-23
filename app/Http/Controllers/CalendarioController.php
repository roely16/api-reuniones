<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Calendario;
    use App\Persona;
    use App\Usuario;
    use App\Rol;

    class CalendarioController extends Controller{
        
        public function obtener_calendario(Request $request){

            $usuario = Usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->admin) {
                
                $calendarios = app('db')->select("  SELECT *
                                                    FROM calendario
                                                    WHERE grupo IS NULL");

                foreach ($calendarios as $calendario) {
                    
                    $persona = Persona::find($calendario->id_persona);

                    $calendario->name = $persona->nombres . ' ' . $persona->apellidos;
                    $calendario->start = $calendario->fecha;
                    $calendario->start = $calendario->fecha;

                    $calendario->editable = true;

                }

            }else{

                // Buscar el calendario del grupo
                $id_grupo = app('db')->select(" SELECT id_grupo
                                                FROM grupo_participante
                                                WHERE id_persona = $usuario->id_persona");


                $id_grupo = $id_grupo[0]->id_grupo;

                $calendarios = app('db')->select("  SELECT *
                                                    FROM calendario
                                                    WHERE id_persona IN (
                                                        SELECT id_persona
                                                        FROM grupo_participante
                                                        WHERE id_grupo = $id_grupo
                                                    )");

                foreach ($calendarios as $calendario) {
                                    
                    $persona = Persona::find($calendario->id_persona);

                    $calendario->name = $persona->nombres . ' ' . $persona->apellidos;
                    $calendario->start = $calendario->fecha;
                    $calendario->start = $calendario->fecha;

                    $calendario->color = $calendario->grupo ? $calendario->color : 'red darken-1';

                    $calendario->editable = $calendario->grupo ? true : false;

                }

            }

            return response()->json($calendarios);

        }
        public function registrar_calendario(Request $request){

            // Usuario que esta registrando
            $usuario = Usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            $calendario = new Calendario();

            $calendario->fecha = $request->fecha;
            $calendario->id_persona = $request->id_persona;
            $calendario->color = $request->color;
            $calendario->grupo = !$rol->admin ? 'S' : null; 

            $result = $calendario->save();

            if (!$result) {
                
                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Se ha presentado un problema al procesar su solicitud",
                    "type" => "error"
                ];

                return response()->json($data);

            }

            // TODO
            // - Notificar por correo electronico

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "La participación a sido agregada al calendario exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }
        public function editar_calendario(Request $request){

            $evento = Calendario::find($request->id);

            $evento->id_persona = $request->id_persona;
            $result = $evento->save();

            if (!$result) {
                
                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Se ha presentado un problema al procesar su solicitud",
                    "type" => "error"
                ];

                return response()->json($data);

            }

            // TODO
            // - Notificar por correo electronico

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "La participación a sido editada en el calendario exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }
        public function eliminar_evento(Request $request){

            $evento = Calendario::find($request->id);
            $result = $evento->delete();

            if (!$result) {
                
                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Se ha presentado un problema al procesar su solicitud",
                    "type" => "error"
                ];

                return response()->json($data);

            }

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "La participación a sido eliminada del calendario exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }

        public function verificar_participacion(Request $request){

            $usuario = Usuario::find($request->id);

            $hoy = date('Y-m-d');

            $evento = app('db')->select("   SELECT *
                                            FROM calendario
                                            WHERE fecha = '$hoy'
                                            AND id_persona = $usuario->id_persona");

            // Si el rol es administrador, asesor o asistente puede generar bitácoras sin calendario
            $rol = Rol::find($usuario->id_rol);

            $acceso = $rol->acceso_no_programado ? true : false;

            $participacion = $evento ? true : false;

            $data = [

                'acceso' => $acceso,
                'participacion' => $participacion

            ];

            return response()->json($data);

        }

        public function personas_calendario(Request $request){

            $usuario = Usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->admin) {
                
                // Mostrar únicamente a los asesores y administradores

                $participantes = app('db')->select("    SELECT t1.*, CONCAT(t1.nombres, ' ', t1.apellidos) as nombre
                                                        FROM persona t1
                                                        INNER JOIN usuario t2
                                                        ON t1.id = t2.id_persona
                                                        AND t2.id_rol IN (
                                                            SELECT id_rol_acceso
                                                            FROM rol_permiso
                                                            WHERE id_rol = $usuario->id_rol
                                                        )
                                                        AND t1.deleted_at IS NULL");

                $participantes_s_rol = app('db')->select("  SELECT t1.*, CONCAT(t1.nombres, ' ', t1.apellidos) as nombre
                                                            FROM persona t1
                                                            WHERE id NOT IN (
                                                                SELECT id_persona
                                                                FROM usuario
                                                            )
                                                            AND t1.deleted_at IS NULL");

                foreach ($participantes_s_rol as $participante) {
                    
                    array_push($participantes, $participante);

                }

            }else{

                $participantes = app('db')->select("    SELECT t2.*, CONCAT(t2.nombres, ' ', t2.apellidos) as nombre
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

            }

            return response()->json($participantes);

        }

    }

?>