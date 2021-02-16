<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Persona;
    use App\Usuario;

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

            $participantes = Persona::all();

            foreach ($participantes as &$participante) {
                
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
                    "width" => "25%"
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