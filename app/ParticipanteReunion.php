<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipanteReunion extends Model{
    
    use SoftDeletes;
    
    protected $table = "participante_reunion";
    protected $primaryKey = "id";

    public $timestamps = false;

}

?>