<?php 

namespace App\Http\Controllers;

use App\Area;
use App\Empleado;

use Illuminate\Support\Facades\DB;

class AreaController extends Controller{

    public function obtener_areas(){

        try {
            
            $data = [];

            $areas = Area::where('estatus', 'A')->get();

            // Obtener los empleados por cada area
            foreach ($areas as &$area) {
                
                $header = [
                    'header' => $area->descripcion
                ];

                array_push($data, $header);

                $empleados = Empleado::select(
                                    DB::raw("CONCAT(NOMBRE, CONCAT(' ', APELLIDO)) as nombre, nit, codarea"),
                                )
                                ->where('status', 'A')
                                ->where('codarea', $area->codarea)
                                ->get();

                foreach ($empleados as &$empleado) {

                    $empleado->group = $area->descripcion;
                    $empleado->selected = false;

                    array_push($data, $empleado);
                }

                $divider = [
                    'divider' => true
                ];

                array_push($data, $divider);

                $area->empleados = $empleados;
                $area->participantes = [];
                $area->expand = false;

            }

            $response = [
                'areas' => $data
            ];

            return response()->json($response, 200);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage, 400);

        }

    }

}