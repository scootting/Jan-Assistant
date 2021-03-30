<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;


class FixedAsset extends Model
{
    //
    public static function GetDocumentFixedAssetByYear($year, $type){
        $query = "select * from act.asignaciones aa where aa.tip_doc = '".$type."' and aa.gestion = '".$year."' order by aa.fec_cre desc";
        \Log::info("esta es una consulta: ".$query);
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    }    

}
