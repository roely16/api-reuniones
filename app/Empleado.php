<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model{
    
    protected $table = "rh_empleados";
    protected $primaryKey = "nit";

    protected $keyType = 'string';
    
    protected $connection = 'rrhh';

}