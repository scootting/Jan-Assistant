<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;


class Document extends Model
{
    //

    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***

    public static function getDescriptionByAbr($abr){
        $query = "SELECT * FROM bdoc.des_doc dd WHERE dd.id_abr ='".$abr."' AND dd.estado = 'TRUE'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }        

}
