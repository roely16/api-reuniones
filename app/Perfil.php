<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model{
    
    protected $table = "rrhh_perfil";
    protected $primaryKey = "id";
    
    protected $connection = 'rrhh';

}