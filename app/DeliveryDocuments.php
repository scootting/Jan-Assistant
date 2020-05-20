<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryDocuments extends Model
{
    public static function getListDocuments()
    {        
        $query = "select e.nro_doc , e.fecha , e.responsable from public.entregas e ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
