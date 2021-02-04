<?php 

    namespace App\Http\Controllers;

    use App\Persona;
    use App\Usuario;

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

        public function prueba(){

            return response()->json("Test");

        }

        //
    }

?>