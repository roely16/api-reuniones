<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Calendario;
    use App\Persona;
    use App\Usuario;

    class CalendarioController extends Controller{
        
        public function obtener_calendario(){

            $calendarios = Calendario::all();

            foreach ($calendarios as $calendario) {
                
                $persona = Persona::find($calendario->id_persona);

                $calendario->name = $persona->nombres . ' ' . $persona->apellidos;
                $calendario->start = $calendario->fecha;
                $calendario->start = $calendario->fecha;

            }

            return response()->json($calendarios);

        }
        public function registrar_calendario(Request $request){

            $calendario = new Calendario();

            $calendario->fecha = $request->fecha;
            $calendario->id_persona = $request->id_persona;
            $calendario->color = $request->color;

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

        public function verificar_permiso(Request $request){

            $usuario = Usuario::find($request->id_usuario);

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

            return response()->json();

        }

    }

?>