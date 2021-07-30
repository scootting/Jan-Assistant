<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;


class FixedAsset extends Model
{
    //
    public static function GetDocumentFixedAssetByYear($year, $type){
        //$year = 2020;
        //$query = "select * from act.asignaciones aa where aa.tip_doc = '".$type."' and aa.gestion = '".$year."' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $query = "select * from act.asignaciones aa where aa.tip_doc in (1,3,2,4) and aa.gestion = '".$year."' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }    

    //
    public static function getFixedAssetsbyDocument($document){
        $query = "SELECT * FROM act.activos aa WHERE aa.codigo LIKE '".$document."%'";
        \Log::info("esta es una consulta mas: ".$query);
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }    


}
