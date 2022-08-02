<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    use Illuminate\Database\Eloquent\SoftDeletes;

    class Reunion extends Model{
        
        protected $table = "reunion";
        protected $primaryKey = "id";

        use SoftDeletes;
        
        public function encargado_registro(){

            return $this->belongsTo('App\Persona', 'registrado_por');

        }

        // protected $casts = [
        //     'updated_at' => "datetime:Y-m-d H:m:i",
        // ];

    }

?>