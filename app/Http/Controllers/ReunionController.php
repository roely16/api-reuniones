<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Reunion;
    use App\Usuario;
    use App\Rol;

    class ReunionController extends Controller{

        public function registrar_reunion(Request $request){

            $reunion = new Reunion();

            $reunion->contenido = $request->content;
            $reunion->observaciones = $request->observaciones;
            $reunion->registrado_por = $request->registrado_por;
            $result = $reunion->save();

            // Registrar las personas para compartir
            foreach ($request->compartir as $id) {
                
                $result = app('db')->table('reunion_compartir')->insert([
                    'id_reunion' => $reunion->id,
                    'id_persona' => $id
                ]);

            }

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
                "message" => "Los datos de la reunión han sido registrados exitosamente",
                "type" => "success",
                "data" => $reunion
            ];

            return response()->json($data);

        }

        public function obtener_reuniones(Request $request){

            $usuario = Usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->admin) {
                
                // Si el rol es admin puede acceder a las reuniones de admin y asesores

                $reuniones = app('db')->select("SELECT 
                                                    t1.id, 
                                                    t2.nombres, 
                                                    t2.apellidos, 
                                                    t2.avatar, 
                                                    DATE_FORMAT(t1.created_at, '%d/%m/%Y %H:%m:%i') as created_at
                                                FROM reunion t1
                                                INNER JOIN persona t2
                                                ON t1.registrado_por = t2.id
                                                WHERE t1.deleted_at IS NULL
                                                AND t1.registrado_por IN (
                                                    SELECT t1.id
                                                    FROM persona t1
                                                    INNER JOIN usuario t2
                                                    ON t1.id = t2.id_persona
                                                    WHERE t2.id_rol IN (1,2)
                                                )
                                                ORDER BY t1.id DESC");

            }elseif($rol->subadmin){

                // Si el rol es subadmin puede acceder a las bitácoras de todo el grupo

                // Buscar el id del grupo
                $grupo = app('db')->select("    SELECT id_grupo
                                                FROM grupo_participante
                                                WHERE id_persona = $usuario->id_persona");

                $id_grupo = $grupo[0]->id_grupo;

                $reuniones = app('db')->select("SELECT 
                                                    t1.id, 
                                                    t2.nombres, 
                                                    t2.apellidos, 
                                                    t2.avatar, 
                                                    DATE_FORMAT(t1.created_at, '%d/%m/%Y %H:%m:%i') as created_at
                                                FROM reunion t1
                                                INNER JOIN persona t2
                                                ON t1.registrado_por = t2.id
                                                WHERE t1.deleted_at IS NULL
                                                AND t1.registrado_por IN (
                                                    SELECT id_persona
                                                    FROM grupo_participante
                                                    WHERE id_grupo = $id_grupo
                                                )
                                                ORDER BY t1.id DESC");

            }else{

                // Solo tiene acceso a sus bitácoras
                $reuniones = app('db')->select("SELECT 
                                                    t1.id, 
                                                    t2.nombres, 
                                                    t2.apellidos, 
                                                    t2.avatar, 
                                                    DATE_FORMAT(t1.created_at, '%d/%m/%Y %H:%m:%i') as created_at
                                                FROM reunion t1
                                                INNER JOIN persona t2
                                                ON t1.registrado_por = t2.id
                                                WHERE t1.deleted_at IS NULL
                                                AND t1.registrado_por = $usuario->id_persona
                                                ORDER BY t1.id DESC");

            }

            

            $headers = [
                [
                    "text" => "ID",
                    "value" => "id",
                    "sortable" => true,
                    "width" => "20%"
                ],
                [
                    "text" => "Fecha de registro",
                    "value" => "created_at",
                    "width" => "30%"
                ],
                [
                    "text" => "Registrado por",
                    "value" => "registrado_por",
                    "width" => "30%"
                ], 
                [
                    "text" => "Acción",
                    "value" => "action",
                    "align" => "end",
                    "width" => "20%"
                ]
            ];

            $data = [
                "items" => $reuniones,
                "headers" => $headers
            ];
            return response()->json($data);

        }

        public function obtener_detalle(Request $request){

            $reunion = Reunion::find($request->id);

            // Obtener las personas registradas como compartir
            $compartir = app('db')->select("    SELECT id_persona
                                                FROM reunion_compartir
                                                WHERE id_reunion = $reunion->id");

            $ids_compartir = [];

            foreach ($compartir as $id) {
                
                $ids_compartir [] = $id->id_persona;

            }

            $reunion->compartir = $ids_compartir;

            return response()->json($reunion);

        }

        public function eliminar_reunion(Request $request){

            $reunion = Reunion::find($request->id);

            $result = $reunion->delete();

            if ($result) {
                
                $data = [
                    "status" => 200,
                    "title" => "Excelente!",
                    "message" => "La reunión a sido eliminada exitosamente",
                    "type" => "success"
                ];

                return response()->json($data);

            }

        }

        public function editar_reunion(Request $request){

            $reunion = Reunion::find($request->id);

            $reunion->contenido = $request->content;
            $reunion->observaciones = $request->observaciones;

            $result = $reunion->save();

            if ($result) {
                
                $result = app('db')->table('reunion_compartir')->where('id_reunion', $request->id)->delete();

                foreach ($request->compartir as $id) {
                
                    $result = app('db')->table('reunion_compartir')->insert([
                        'id_reunion' => $reunion->id,
                        'id_persona' => $id
                    ]);
    
                }

            }

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "Los datos de la reunión han sido actualizados exitosamente",
                "type" => "success",
                "data" => $reunion
            ];

            return response()->json($data);

        }
        
    }