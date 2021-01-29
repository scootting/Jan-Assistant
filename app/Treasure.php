<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Treasure extends Model
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}      
    //  * {year: gestion}      
    public static function getNewStudentByDNI($id, $year){
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $query = "select * from cluster.f_nuevos_datacenter('".$id."','".$year."','1')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {description: descripcion del tramite}      
    //  * {year: gestion}      
    public static function getValuesProcedure($description, $year){
        //select * from trap.ff_valores_tramite('EXCELENCIA', '2020')
        $query = "select * from trap.ff_valores_tramite('".$description."','".$year."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

}
