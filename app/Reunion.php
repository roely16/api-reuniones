<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Reunion extends Model{
        
        protected $table = "reunion";
        protected $primaryKey = "id";

        public function encargado_registro(){

            return $this->belongsTo('App\Persona', 'registrado_por');

        }

    }

?>