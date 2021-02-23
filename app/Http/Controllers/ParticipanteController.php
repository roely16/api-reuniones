<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Persona;
    use App\Usuario;
    use App\Rol;
    use App\Grupo;

    use Illuminate\Support\Facades\Crypt;
    
    class ParticipanteController extends Controller{
        
        public function registrar_participante(Request $request){

            $persona = Persona::where('telefono', $request->telefono)->first();

            if ($persona) {

                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Ya existe una persona registrada con el mismo número de teléfono",
                    "type" => "error"
                ];

                return response()->json($data);

            }
            
            $persona = new Persona();

            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->cargo = $request->cargo;

            $persona->grupo = $request->grupo ? 'S' :  null;
            
            $result = $persona->save();

            // Buscar el rol de la persona que esta registrando
            $usuario = Usuario::find($request->registrado_por);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->subadmin) {
                
                // Si es un subadministrador podrá agregar a las personas y quedarán en su grupo
                $grupo = app('db')->select("    SELECT id_grupo
                                                FROM grupo_participante
                                                WHERE id_persona = $usuario->id_persona");

                $result = app('db')->table('grupo_participante')->insert([
                    'id_grupo' => $grupo[0]->id_grupo,
                    'id_persona' => $persona->id
                ]);

            }

            if ($request->habilitar_usuario) {
                
                $usuario = new Usuario();

                try {
                    
                    $result = $usuario->fill([
                        'usuario' => $request->telefono,
                        'password' => Crypt::encrypt($request->password),
                        'id_persona' => $persona->id,
                        'id_rol' => $request->id_rol
                    ])->save();

                } catch (\Illuminate\Database\QueryException $ex) {
                    
                    $data = [
                        "status" => 100,
                        "title" => "Código " . $ex->errorInfo[0],
                        "message" => "Se ha presentado un problema al procesar su solicitud",
                        "type" => "error"
                    ];

                    return response()->json($data);

                }

                // Si esta marcada la opción de grupo personalizado
                if ($request->grupo) {
                    
                    $grupo = new Grupo();

                    $grupo->id_persona = $persona->id;
                    $result = $grupo->save();

                    // Ingresar como participante del grupo
                    $result = app('db')->table('grupo_participante')->insert([
                        'id_grupo' => $grupo->id,
                        'id_persona' => $persona->id
                    ]);

                }

            }

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "El participante a sido registrado exitosamente",
                "type" => "success",
                "data" => $persona
            ];

            return response()->json($data);

        }

        public function obtener_participantes(Request $request){

            $usuario = Usuario::find($request->id_usuario);

            $rol = Rol::find($usuario->id_rol);

            if ($rol->admin) {
                
                //$participantes = Persona::all();
                $participantes = app('db')->select("    SELECT t1.*
                                                        FROM persona t1
                                                        INNER JOIN usuario t2
                                                        ON t1.id = t2.id_persona
                                                        AND t2.id_rol IN (
                                                            SELECT id_rol_acceso
                                                            FROM rol_permiso
                                                            WHERE id_rol = $usuario->id_rol
                                                        )
                                                        AND t1.deleted_at IS NULL");

                $participantes_s_rol = app('db')->select("  SELECT t1.*
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

                $participantes = app('db')->select("    SELECT t2.*
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

            $id_persona = $usuario->id_persona;

            foreach ($participantes as &$participante) {
                
                // No permitir eliminar el registro del usuario logeado
                if ($participante->id == $id_persona) {

                    $participante->deletable = false;

                }else{

                    $participante->deletable = true;
                }

                $usuario = Usuario::where('id_persona', $participante->id)->first();

                if ($usuario) {
                
                    $rol = Rol::find($usuario->id_rol);

                    if ($rol) {
                        
                        $participante->rol = $rol;

                    }

                }else{

                    $participante->rol = null;

                }

                $participante->nombre = $participante->nombres . ' ' . $participante->apellidos;

            }

            $headers = [
                [
                    "text" => "ID",
                    "value" => "id",
                    "sortable" => true,
                    "width" => "5%"
                ],
                [
                    "text" => "",
                    "value" => "avatar",
                    "width" => "5%"
                ],
                [
                    "text" => "Nombre",
                    "value" => "nombre",
                    "width" => "20%"
                ], 
                [
                    "text" => "Teléfono",
                    "value" => "telefono",
                    "width" => "10%%"
                ],
                [
                    "text" => "Email",
                    "value" => "email",
                    "width" => "20%%"
                ],
                [
                    "text" => "Cargo",
                    "value" => "cargo",
                    "width" => "30%%"
                ],
                [
                    "text" => "Rol",
                    "value" => "rol",
                    "width" => "10%%"
                ],
                [
                    "text" => "Acción",
                    "value" => "action",
                    "align" => "end",
                    "width" => "15%"
                ]
            ];

            $data = [
                "items" => $participantes,
                "headers" => $headers
            ];
            return response()->json($data);

        }

        public function detalle_participante(Request $request){

            $persona = Persona::find($request->id);

            $usuario = Usuario::where('id_persona', $persona->id)->first();

            if ($usuario) {
                
                $persona->usuario = $usuario;

                $persona->id_rol = $usuario->id_rol;


            }
            
            return response()->json($persona);

        }

        public function editar_participante(Request $request){

            $check_persona = Persona::where('telefono', $request->telefono)->first();

            if ($check_persona) {

                if ($check_persona->id != $request->id) {
                    
                    $data = [
                        "status" => 100,
                        "title" => "Error",
                        "message" => "Ya existe una persona registrada con el mismo número de teléfono",
                        "type" => "error"
                    ];
    
                    return response()->json($data);

                }
                
            }

            $persona = Persona::find($request->id);

            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->cargo = $request->cargo;
            
            $result = $persona->save();

            if ($request->habilitar_usuario) {
                
                $usuario = new Usuario();

                try {
                    
                    $result = $usuario->fill([
                        'usuario' => $request->telefono,
                        'password' => Crypt::encrypt($request->password),
                        'id_persona' => $persona->id,
                        'id_rol' => $request->id_rol
                    ])->save();

                } catch (\Illuminate\Database\QueryException $ex) {
                    
                    $data = [
                        "status" => 100,
                        "title" => "Código " . $ex->errorInfo[0],
                        "message" => "Se ha presentado un problema al procesar su solicitud",
                        "type" => "error"
                    ];

                    return response()->json($data);

                }
                
            }

            // Si esta marcada la opción de grupo personalizado
            if ($request->grupo) {
                
                $grupo_existente = Grupo::where('id_persona', $persona->id)->first();

                if (!$grupo_existente) {
                    
                    $grupo = new Grupo();

                    $grupo->id_persona = $persona->id;
                    $result = $grupo->save();

                    // Ingresar como participante del grupo
                    $result = app('db')->table('grupo_participante')->insert([
                        'id_grupo' => $grupo->id,
                        'id_persona' => $persona->id
                    ]);
                    
                    $persona = Persona::find($request->id);
                    $persona->grupo = 'S';
                    $persona->save();

                }

            }

            if ($request->usuario) {
                
                $usuario = Usuario::find($request->usuario["id"]);

                $usuario->id_rol = $request->id_rol;
                $usuario->save();

            }

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "El participante a sido editado exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }

        public function eliminar_participante(Request $request){

            $persona = Persona::find($request->id);
            
            $result = $persona->delete();

            try {
                
                 // Eliminar el usuario de la persona
                app('db')->table('usuario')->where('id_persona', $request->id)->delete();

                // Eliminar su participación en el calendario
                app('db')->table('calendario')->where('id_persona', $request->id)->delete();

            } catch (\Throwable $th) {
                //throw $th;
            }
           

            if ($result) {
                
                $data = [
                    "status" => 200,
                    "title" => "Excelente!",
                    "message" => "El participante a sido eliminado exitosamente",
                    "type" => "success"
                ];

            }

            return response()->json($data);

        }

        public function subir_avatar(Request $request){

            if ($request->hasFile('files')) {
                
                $files = $request->file('files');
                $id = $request->id;

                $uploads_dir = base_path('public/avatar');
                $file_name = uniqid();

                $path = $request->file('files')->move($uploads_dir, $file_name . '.' . $files->getClientOriginalExtension());

                $persona = Persona::find($id);
                $persona->avatar = 'avatar/' . $file_name . '.' . $files->getClientOriginalExtension();
                $persona->save();

                return response()->json($persona);

            }


        }

        public function editar_avatar(Request $request){

            if ($request->hasFile('files')) {
                
                // Verificar si ya tenia un avatar
                $persona = Persona::find($request->id);

                if ($persona->avatar) {
                    
                    unlink(base_path() . '/public/' . $persona->avatar);

                }
                $files = $request->file('files');
                $id = $request->id;

                $uploads_dir = base_path('public/avatar');
                $file_name = uniqid();

                $path = $request->file('files')->move($uploads_dir, $file_name . '.' . $files->getClientOriginalExtension());

                $persona->avatar = 'avatar/' . $file_name . '.' . $files->getClientOriginalExtension();
                $persona->save();

                return response()->json($persona);

            }

        }

    }


?>