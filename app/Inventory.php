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
                    inv.oficinas.gestion = '" . $gestion . "' 
                    and inv.oficinas.descripcion like '%" . $descripcion . "%'";
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
        where inv.sub_oficinas.id in (select sub_ofc_cod
        from union_activo where cod_soa = '" . $cod_soa . "' 
        group by sub_ofc_cod)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getActivosByCodSoaAndSubOffice($cod_soa, $idso)
    {
        $db = DB::table('inv.activos')->where('inv.activos.ofc_cod', $cod_soa);
        if ($idso) {
            $db->where('inv.activos.sub_ofc_cod', $idso);
        }
        return $db->get();
    }
    public static function getInventories($gestion, $descripcion)
    {
        $query = "select inv.doc_inv.no_cod, inv.doc_inv.ofc_cod,
        inv.oficinas.descripcion,inv.doc_inv.estado
        from inv.doc_inv , inv.oficinas where 
        inv.doc_inv.gestion = " . $gestion . "
        and inv.doc_inv.ofc_cod = inv.oficinas.cod_soa 
        and inv.oficinas.descripcion like '%" . $descripcion . "%'
        ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getUnidad($keyWord)
    {
        $query = "select inv.oficinas.descripcion, inv.oficinas.cod_soa
        from inv.oficinas 
        where inv.oficinas.descripcion like '%" . $keyWord . "%' or 
        inv.oficinas.cod_soa like '%" . $keyWord . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getSubUnidades($unidad)
    {

        $query = "select inv.sub_oficinas.descripcion,inv.sub_oficinas.id
        from inv.sub_oficinas, inv.activos,inv.oficinas
        WHERE
        inv.activos.sub_ofc_cod = inv.sub_oficinas.id
        and inv.oficinas.cod_soa = inv.activos.ofc_cod
        and inv.oficinas.cod_soa like '%" . $unidad . "%'
        group by (inv.sub_oficinas.descripcion,inv.sub_oficinas.id)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getCargos($unidad, $sub_unidades)
    {
        $arrString = "(";
        foreach ($sub_unidades as $k => $su)
            $arrString = $arrString . ($k > 0 ? ',' : '') . $su;
        $arrString = $arrString . ')';
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
    public static function getEncargados($nro_dip)
    {
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno
        from public.personas
        where public.personas.nro_dip like '%" . $nro_dip . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
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
}
