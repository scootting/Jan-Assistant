<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    public static function getOffices($gestion, $descripcion)
    {
        $query = "select inv.oficinas.cod_soa,inv.oficinas.descripcion 
                    from inv.oficinas where 
                    inv.oficinas.gestion = '" . $gestion . "' and inv.oficinas.descripcion like '%" . $descripcion . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getOfficeByCodSoa($cod_soa)
    {
        $query = "select inv.oficinas.cod_soa,inv.oficinas.descripcion 
        from inv.oficinas where 
        inv.oficinas.cod_soa = '" . $cod_soa . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }

    public static function getSubOfficesByCodSoa($cod_soa)
    {
        $query = "select * from inv.sub_oficinas 
        where inv.sub_oficinas.id in (select inv.activos.sub_ofc_cod
        from inv.activos where inv.activos.ofc_cod = '".$cod_soa."' 
        group by inv.activos.sub_ofc_cod)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getActivosByCodSoaAndSubOffice($cod_soa,$idso)
    {
        $db = DB::table('inv.activos')->where('inv.activos.ofc_cod',$cod_soa);
        if($idso){
            $db->where('inv.activos.sub_ofc_cod',$idso);
        }
        return $db->get();
    } 
    public static function getInventories($gestion,$descripcion)
    {
        $query = "select inv.doc_inv.no_cod, inv.doc_inv.ofc_cod,
        inv.oficinas.descripcion,inv.doc_inv.estado
        from inv.doc_inv , inv.oficinas where 
        inv.doc_inv.gestion = ".$gestion."
        and inv.doc_inv.ofc_cod = inv.oficinas.cod_soa 
        and inv.oficinas.descripcion like '%".$descripcion."%'
        ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
