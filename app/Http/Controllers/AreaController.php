<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Area;
use App\Empleado;
use App\RutaPDF;
use App\EmpleadoPerfil;
use App\Perfil;

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

    public function detalle_colaborador(Request $request){

        try {
            
            $colaborador = Empleado::find($request->nit);

            $colaborador->nombre_completo = $colaborador->nombre . ' ' . $colaborador->apellido;

            $area = Area::find($colaborador->codarea);
            $colaborador->area = $area->descripcion;

            // Obtener el puesto o perfil
            // $colaborador_perfil = EmpleadoPerfil::where('nit')->first();
            // $perfil = Perfil::find($colaborador_perfil->id_perfil);

            // Obtener la foto de cada colaborador
            $archivo = RutaPDF::where('nit', $colaborador->nit)->where('idcat', 11)->first();   

            $colaborador->archivo = $archivo;
            // $colaborador->perfil = $perfil->nombre;

            $response = [
                'colaborador' => $colaborador
            ];

            return response()->json($response);
            
        } catch (\Throwable $th) {
            
            return response()->json($th->getMessage(), 400);

        }

    }

}