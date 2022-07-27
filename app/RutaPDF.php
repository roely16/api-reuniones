<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutaPDF extends Model{
        
    protected $table = "rh_ruta_pdf";
    protected $primaryKey = "idrutas";

    protected $connection = 'rrhh';

}

?>