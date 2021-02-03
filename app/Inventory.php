<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{   
    //obtener las oficinas basandonos en la gestion y busqueda por la descripción. 
    public static function getOffices($gestion, $descripcion)
    {
        $query = "select inv.oficinas.cod_soa,inv.oficinas.descripcion 
                    from inv.oficinas where 
                    inv.oficinas.gestion = '" . $gestion . "' 
                    and inv.oficinas.descripcion like '%" . $descripcion . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener las unidades por el codigo soa 
    public static function getOfficeByCodSoa($cod_soa)
    {
        $query = "select inv.oficinas.cod_soa,inv.oficinas.descripcion 
        from inv.oficinas where 
        inv.oficinas.cod_soa = '" . $cod_soa . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //obtener la tabla de sub oficina por el codigo soa
    public static function getSubOfficesByCodSoa($cod_soa)
    {
        $query = "select * from inv.sub_oficinas 
        where inv.sub_oficinas.id in (select sub_ofc_cod
        from inv.activos where ofc_cod = '" . $cod_soa . "' 
        group by sub_ofc_cod)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 

    // prueba para obtener los activos segun el codigo soa y el id de sub oficina
    public static function getActivosByCodSoaAndSubOffice($cod_soa, $idso)
    {
        $db = DB::table('inv.activos')->where('inv.activos.ofc_cod', $cod_soa);
        if ($idso) {
            $db->where('inv.activos.sub_ofc_cod', $idso);
        }
        return $db->get();
    } 
    //obtener inventarios creados
    public static function getInventories($gestion, $descripcion)
    {
        $query = "select inv.doc_inv.id, inv.doc_inv.no_cod, inv.doc_inv.ofc_cod,
        inv.oficinas.descripcion,inv.doc_inv.estado
        from inv.doc_inv , inv.oficinas where 
        inv.doc_inv.gestion = " . $gestion . "
        and inv.doc_inv.ofc_cod = inv.oficinas.cod_soa 
        and inv.oficinas.descripcion like '%" . $descripcion . "%'
        ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    //ontener la unidad a la que se hará el inventario
    public static function getUnidad($keyWord)
    {
        $query = "select inv.oficinas.descripcion, inv.oficinas.cod_soa, inv.oficinas.id
        from inv.oficinas 
        where inv.oficinas.descripcion like '%" . $keyWord . "%' or 
        inv.oficinas.cod_soa like '%" . $keyWord . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener las sub unidades de la unidad a la que se hará el inventario
    public static function getSubUnidades($unidad,$idUnidad)
    {
        $query = "select inv.sub_oficinas.descripcion,inv.sub_oficinas.id
        from inv.sub_oficinas, inv.activos,inv.oficinas
        WHERE
        inv.activos.sub_ofc_cod = inv.sub_oficinas.id
        and inv.oficinas.cod_soa = inv.activos.ofc_cod ";
        if($unidad)
            $query=$query." and inv.oficinas.cod_soa like '%" . $unidad . "%' ";
        if($idUnidad)
            $query=$query." and inv.oficinas.id = ". $idUnidad ." ";
        $query=$query."group by (inv.sub_oficinas.descripcion,inv.sub_oficinas.id)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    //obtener los cargos de los encargados de las subunidades para crear nuevo inventario
    public static function getCargos($unidad, $sub_unidades)
    {
        $arrString = "(";
        foreach ($sub_unidades as $k => $su)
            $arrString = $arrString . ($k > 0 ? ',' : '') . $su;
        $arrString = $arrString . ")";
        $query = "select inv.cargos.id , inv.cargos.descripcion
        from inv.cargos, inv.activos,inv.sub_oficinas
        where 
        inv.cargos.id=inv.activos.car_cod
        and 
        inv.sub_oficinas.id=inv.activos.sub_ofc_cod
        and 
        inv.activos.ofc_cod like '%" . $unidad . "%'
        and 
        inv.sub_oficinas.id in " . $arrString . "
        group by (inv.cargos.id,inv.cargos.descripcion)
        order by (inv.cargos.id)";

        /*
        $query = "select inv.cargos.id ,inv.cargos.descripcion
        from inv.cargos,inv.activos
        where inv.cargos.id = inv.activos.car_cod
        and inv.activos.sub_ofc_cod in ".$arrString."
        and inv.activos.ofc_cod like '%".$unidad."%'   
        group by ( inv.cargos.id, inv.cargos.descripcion)
        order by (inv.cargos.id)";
        */
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener los responsables que realizaran el nuevo inventario
    public static function getResponsables($unidad, $cargos)
    {
        $arrString = "(";
        foreach ($cargos as $k => $cargo)
            $arrString = $arrString . ($k > 0 ? ',' : '') . $cargo;
        $arrString = $arrString . ')';
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno, inv.cargos.descripcion 
        from inv.activos,public.personas,inv.cargos
        where inv.activos.ofc_cod like '%" . $unidad . "%'and
        public.personas.nro_dip = inv.activos.ci_resp
        and inv.activos.car_cod = inv.cargos.id
        and inv.cargos.id in " . $arrString . "
        group by (public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno, inv.cargos.descripcion)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener los cargos para crear nuevo inventario
    public static function getEncargados($nro_dip)
    {
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno
        from public.personas
        where public.personas.nro_dip like '%" . $nro_dip . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    //guardar datos del nuevo inventario
    public static function saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res,$estado,$gestion)
    {
        $date = Date('d-m-Y');
        $query = " insert into 
                inv.doc_inv
                ( 
                no_cod,
                res_enc,
                car_cod,
                ofc_cod,
                sub_ofc_cod,
                car_cod_resp,
                ci_res,
                fec_cre,
                estado,
                gestion
                )
                values
                 (
                '" . $no_doc . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($res_enc))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod))) . "',
                '" . $ofc_cod . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($sub_ofc_cod))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod_resp))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($ci_res))) . "',
                '" . $date . "',
                '" . $estado . "',
                '" . $gestion . "'
                );";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // peticiones de busqueda para activos segun id de oficina, de la subOficina y descripción  
    public static function SearchActive($ofc_id,$sub_ofc_ids,$descripcion)
    {
        $db = DB::table('inv.union_activos as ua')->select('ua.id', 'of.descripcion as oficina','sof.descripcion','ua.des','ua.estado')
        ->join('inv.oficinas as of','ua.ofc_cod','=','of.cod_ofc')->join('inv.sub_oficinas as sof','ua.sub_ofc_cod','=','sof.id');
        if ($descripcion) {
            $db->where('ua.des', 'like', '%'.$descripcion.'%');
        }
        if($ofc_id){
            $db->where('of.id',$ofc_id);
        }
        if($sub_ofc_ids){
            $db->whereIn('ua.sub_ofc_cod',$sub_ofc_ids);
        }
        return $db->get();
    } 

    public static function showActiveById($id)
    {
        $query = "select * from inv.union_activos
        where id = ".$id."";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    public static function showInventoryById($id)
    {
        $query = "select * from inv.doc_inv
        where id = ".$id."";
        $data = collect(DB::select(DB::raw($query)))[0];
        $data->car_cod = array_map('intval', explode(',',str_replace('{','',str_replace('}','',$data->car_cod))));
        $data->res_enc = explode(',',str_replace('{','',str_replace('}','',$data->res_enc)));
        $data->sub_ofc_cod = array_map('intval', explode(',',str_replace('{','',str_replace('}','',$data->sub_ofc_cod))));
        $data->car_cod_resp = array_map('intval', explode(',',str_replace('{','',str_replace('}','',$data->car_cod_resp))));
        $data->ci_res =  explode(',',str_replace('{','',str_replace('}','',$data->ci_res)));
        return $data;
    }
    public static function saveChangeActive($des, $des_det, $vida_util,$estado,$ofc_cod,$sub_ofc_cod,$ci_resp,$id)
    { 
        $query = "select * from inv.f_guardar_activo('".$des."', '".$des_det."','".$vida_util."','".$estado."','".$ofc_cod."','".$sub_ofc_cod."','".$ci_resp."','".$id."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function saveChangeDocInventory($id, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res)
    {
        $query = " Select * from inv.f_guardar_cambios doc(
                '" . $id . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($res_enc))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod))) . "',
                '" . $ofc_cod . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($sub_ofc_cod))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod_resp))) . "',
                '" . str_replace(']', '}', str_replace('[', '{', json_encode($ci_res))) . "',
                );";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
