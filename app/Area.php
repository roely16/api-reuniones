<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model{
    
    protected $table = "rh_areas";
    protected $primaryKey = "codarea";

    protected $connection = 'rrhh';

}