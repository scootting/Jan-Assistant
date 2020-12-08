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
    public static function getUnidad($keyWord)
    {
        $query = "select inv.oficinas.descripcion, inv.oficinas.cod_soa
        from inv.oficinas 
        where inv.oficinas.descripcion like '%".$keyWord."%' or 
        inv.oficinas.cod_soa like '%".$keyWord."%'";
         $data = collect(DB::select(DB::raw($query)));
         return $data;
    }
    public static function getSubUnidades($unidad)
    {
        $query = "select inv.sub_oficinas.id ,inv.sub_oficinas.descripcion
        from inv.sub_oficinas,inv.oficinas ,inv.activos
        where inv.oficinas.cod_soa = inv.activos.ofc_cod
        and inv.oficinas.cod_soa like '%".$unidad."%' 
        and inv.sub_oficinas.id = inv.activos.sub_ofc_cod
        group by (inv.sub_oficinas.id,inv.sub_oficinas.descripcion)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getCargos($unidad,$sub_unidades)
    {
        $arrString="(";
        foreach($sub_unidades as $k=>$su)
            $arrString =$arrString.($k>0? ',':'' ).$su;
        $arrString = $arrString.')';
        $query = "select inv.cargos.id ,inv.cargos.descripcion
        from inv.cargos,inv.activos
        where inv.cargos.id = inv.activos.car_cod
        and inv.activos.sub_ofc_cod in ".$arrString."
        and inv.activos.ofc_cod like '%".$unidad."%'   
        group by ( inv.cargos.id, inv.cargos.descripcion)
        order by (inv.cargos.id)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getResponsables ($unidad)
    {
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno
        from inv.activos,public.personas
        where inv.activos.ofc_cod like '%".$unidad."%'and
        public.personas.nro_dip = inv.activos.ci_resp
        group by (public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getEncargados($nro_dip)      
    {
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno
        from public.personas
        where public.personas.nro_dip like '%".$nro_dip."%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
