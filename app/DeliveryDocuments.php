<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryDocuments extends Model
{
    public static function getListDocuments()
    {        
        $query = "select * from act.ff_desc_encargado()";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    public static function editActivo($nro_doc){
        $query = "select * from act.asignaciones_detalles where nro_doc ='".$nro_doc."'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }   
    public static function getInfoEncargado($nro_doc){
        $query = "select a.cargores , a.estado from act.asignaciones a where nro_doc = '".$nro_doc."'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }   
    
}
