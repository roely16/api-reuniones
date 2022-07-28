<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pendiente extends Model{
    
    protected $table = "pendiente";
    protected $primaryKey = "id";

    public $timestamps = false;
    
}

?>