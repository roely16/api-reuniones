<?php 

    namespace App\Http\Controllers;

    use App\Persona;
    use App\Usuario;
    use App\Menu;
    use App\Rol;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Crypt;

    class UsuarioController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct(){
            //
        }

        public function registrar_usuario(Request $request){
            
            $persona = new Persona();

            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->cargo = $request->cargo;
            
            $result = $persona->save();

            // Crear el usuario de la persona

            $usuario = new Usuario();

            try {
                
                $result = $usuario->fill([
                    'usuario' => $request->telefono,
                    'password' => Crypt::encrypt($request->password),
                    'id_persona' => $persona->id
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
            
            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "El usuario a sido registrado exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }

        public function login(Request $request){

            $usuario = Usuario::where('usuario', $request->usuario)->first();

            if (!$usuario) {
                
                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Usuario o contraseña incorrectos",
                    "type" => "error"
                ];

                return response()->json($data);

            }

            // Si el usuario es correcto verificar la contraseña
            $password = Crypt::decrypt($usuario->password);

            if ($request->password != $password) {
                
                $data = [
                    "status" => 100,
                    "title" => "Error",
                    "message" => "Usuario o contraseña incorrectos",
                    "type" => "error"
                ];

                return response()->json($data);

            }

            // Si el usuario no es administrador
            if ($usuario->id_rol != 1) {
                
                $hoy = date('Y-m-d');

                $evento = app('db')->select("   SELECT *
                                                FROM calendario
                                                WHERE fecha = '$hoy'
                                                AND id_persona = $usuario->id_persona");

                if (!$evento) {
                    
                    $data = [
                        "status" => 100,
                        "title" => "Error",
                        "message" => "No tiene programado el acceso para el día de hoy",
                        "type" => "error"
                    ];
    
                    return response()->json($data);

                }

            }

            $data_usuario = [
                "id" => $usuario->id,
                "id_persona" => $usuario->id_persona
            ];

            // Contraseña ingresada correcta
            $data = [
                "status" => 200,
                "data" => $data_usuario
            ];

            return response()->json($data);

        }

        public function datos_usuario(Request $request){

            $persona = Persona::find($request->id_persona);

            $usuario = Usuario::where('id_persona', $request->id_persona)->first();


            $menu = app('db')->select(  "SELECT t2.*
                                        FROM menu_rol t1
                                        INNER JOIN menu t2
                                        ON t1.id_menu = t2.id
                                        WHERE t1.id_rol = $usuario->id_rol");
            $data = [
                "persona" => $persona,
                "menu" => $menu
            ];

            return response()->json($data);

        }

        public function obtener_roles(){

            $roles = Rol::all();

            return response()->json($roles);

        }

    }

?>