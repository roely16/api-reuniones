<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Reunion;

    class ReunionController extends Controller{

        public function registrar_reunion(Request $request){

            $reunion = new Reunion();

            $reunion->contenido = $request->content;
            $reunion->observaciones = $request->observaciones;
            $reunion->registrado_por = $request->registrado_por;
            $reunion->save();

            return response()->json($reunion);

        }

        public function obtener_reuniones(Request $request){

            $reuniones = Reunion::all();

            $headers = [
                [
                    "text" => "ID",
                    "value" => "id",
                    "sortable" => true,
                    "width" => "20%"
                ],
                [
                    "text" => "Fecha",
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

    }