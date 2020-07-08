<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class CarpentryDocuments extends Model
{
    public static function getListCarpentryDocuments($gestion)
    {        
        $query = "select * from act.ff_desc_carpinteria('".$gestion."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    public static function editActivo($nro_doc){
        $query = "select * from act.asignaciones_detalles where nro_doc ='".$nro_doc."'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }   
    public static function getInfoEncargado($nro_doc){
        $query = "select a.nro_doc,a.fecha, a.responsable, a.cargores , a.estado , of.ofc_des
                    from act.asignaciones a , act.oficina of
                    where a.id_ofc = of.id_oficina and a.tip_doc = 4 and a.nro_doc = '".$nro_doc."'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }   
    public static function getDestiny()
    {        
        $query = "select id_oficina, ofc_des from public.oficinas";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    public static function getPartidas($gestion)
    {
        $query = "select * from act.ff_obtener_partida ('".$gestion."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getContablesGroups()
    {
        $query = "select c.con_cod, c.con_des, c.con_vida_util
                from act.act_con c";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getUni_meds(){
        $query = "select u.id_uni_med,u.uni_des_det,u.uni_des_cor
                from public.sis_uni_med u";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function updateAsset($cantidad,$descripcion,$des_det,$uni_med,$id_partida,$id_contable,$vida_util,$pre_uni,$nro_fac,$id)
    {        
        $query = "select * from act.ff_guardar_activo('".$cantidad."','".$descripcion."','".$des_det."','".$uni_med."','".$id_partida."','".$id_contable."','".$vida_util."','".$pre_uni."','".$nro_fac."','".$id."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    public static function addEncargado($fecha,$estado, $destino, $responsable, $cargores, $gestion )
    {
        $may=0;
        foreach(CarpentryDocuments::getListDocuments($gestion) as $doc){
            $num=explode("/",$doc->nro_doc)[0];
            if($num>$may)
                $may=$num;
        }
        $nro_doc= ++$may."/".$gestion;
        $query = "select * from act.ff_registrar_encargado('".$nro_doc."','".$fecha."','".$estado."','".$destino."','".$responsable."','".$cargores."','".$gestion."')";
        $data = collect(DB::select(DB::raw($query)));
        $data["nro_doc"]=$nro_doc;
        return $data;
    }
    public static function addAsset ($cantidad,$descripcion,$des_det,$uni_med,$id_partida,$id_contable,$vida_util,$pre_uni,$nro_fac,$id){
        $query = "select * from act.ff_guardar_activo('".$cantidad."','".$descripcion."','".$des_det."','".$uni_med."','".$id_partida."','".$id_contable."','".$vida_util."','".$pre_uni."','".$nro_fac."','".$id."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
