<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipanteReunion extends Model{
        
    protected $table = "participante_reunion";
    protected $primaryKey = "id";

    public $timestamps = false;

}

?>