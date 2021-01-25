<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treasure extends Model
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}      
    public static function getNewStudentByDNI($id, $year){
        $query = "select * from cluster.f_nuevos_datacenter('".$id."','".$year."','1')";
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }    

}
