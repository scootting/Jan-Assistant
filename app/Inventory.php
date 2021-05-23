<?php

namespace App;

use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class Inventory extends Model
{
    //obtener las oficinas basandonos en la gestion y busqueda por la descripción. 
    public static function getOffices($gestion, $descripcion)
    {
        $query = "select inv.oficinas.cod_soa,inv.oficinas.descripcion 
                    from inv.oficinas where 
                    inv.oficinas.gestion = '" . $gestion . "' 
                    and inv.oficinas.descripcion like '%" . $descripcion . "%'
                    order by inv.oficinas.cod_soa ASC";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener las unidades por el codigo soa 
    public static function getOfficeByCodSoa($cod_soa)
    {
        $query = "select inv.oficinas.id, inv.oficinas.cod_soa,inv.oficinas.descripcion 
            from inv.oficinas where 
            inv.oficinas.cod_soa = '" . $cod_soa . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //obtener los cargos para poder filtrar los activos por los cargos ya sea para listar u obtener reporte 
    public static function getCargorsForActive($cod_soa)
    {
        $query = "select * from inv.cargos c
        where c.id in (
        select a.car_cod from inv.union_activos a
        where a.ofc_cod = '" . $cod_soa . "'
        group by a.car_cod)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener la tabla de sub oficina por el codigo soa para listar u obtener reportes
    public static function getSubOfficesByCodSoa($cod_soa)
    {
        $query = "select * from inv.sub_oficinas 
        where inv.sub_oficinas.id in (select sub_ofc_cod
        from inv.union_activos ".($cod_soa? ("where ofc_cod = '" . $cod_soa . "'"):"")." 
        group by sub_ofc_cod)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 
    //Obtener los responsables para filtrar los activos para realizar los listados o generar los reportes 
    public static function getResponsablesForActive($cod_soa)
    {
        $query = "select * from public.personas p
        where p.nro_dip in (select ci_resp
        from inv.union_activos where ofc_cod = '" . $cod_soa . "' 
        group by ci_resp)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Obtener activos cuando el filtro este en TODO (esta funcion se está reutilizando)
    public static function getActivosBySoaAndResp($tipo,$cod_soa, $ci_resp)
    {
        
        // $db = DB::table('inv.activos')->where('inv.activos.ofc_cod', $cod_soa);
        //  if ($ci_resp) {
        //  $db->where('inv.activos.sub_ofc_cod', $ci_resp);
        //  }
        // return $db->get();
        if ($tipo == 'general') {

            //$query = "select * from inv.ff_getactivosgeneralbycargo('" . $cod_soa . "', '" . $arrString . "')"; 
            $query = "select * from act.ff_activos_general('". $cod_soa ."')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallelbycargo('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('". $cod_soa ."')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    } 


    // parte de Inventarios 2 (renombrado como INVENTARIO)
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
    //obtener la unidad a la que se hará el inventario introduciendo la descripcion de la unidad o codigo soa
    public static function getUnidad($keyWord)
    {
        $query = "select inv.oficinas.descripcion, inv.oficinas.cod_soa, inv.oficinas.cod_ofc ,inv.oficinas.id
        from inv.oficinas 
        where inv.oficinas.descripcion like '%" . $keyWord . "%' or 
        inv.oficinas.cod_soa like '%" . $keyWord . "%' 
        ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener las sub unidades de la unidad a la que se hará el inventario
    public static function getSubUnidades($unidad, $idUnidad, $cod_ofc)
    {
        $query = "select sof.descripcion, sof.id
        from inv.sub_oficinas sof, inv.union_activos ua ,inv.oficinas of
        WHERE
        ua.sub_ofc_cod = sof.id
        and of.cod_soa = ua.ofc_cod ";
        if ($unidad)
            $query = $query . " and of.cod_soa like '%" . $unidad . "%' ";
        if ($idUnidad)
            $query = $query . " and of.id = " . $idUnidad . " ";
        if ($cod_ofc)
            $query = $query . " and of.cod_ofc = " . $cod_ofc . " ";
       $query = $query . "
       group by sof.id, sof.descripcion";
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
        $query = "select inv.cargos.id , inv.cargos.descripcion as cargo
        from inv.cargos, inv.activos,inv.sub_oficinas
        where 
        inv.cargos.id=inv.activos.car_cod
        and 
        inv.sub_oficinas.id=inv.activos.sub_ofc_cod
        and 
        inv.activos.ofc_cod like '%" . $unidad . "%'
        " . (count($sub_unidades) > 0 ? "and 
        inv.sub_oficinas.id in " . $arrString : "") . "
        group by (inv.cargos.id,inv.cargos.descripcion)
        order by (inv.cargos.id)";
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
        " . (count($cargos) > 0 ? "and inv.cargos.id in "
            . $arrString : "") . "
        group by public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno, inv.cargos.descripcion";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener los responsables de cada unidad sin contar con el cargo 
    public static function getResponsablesbyUnidad($unidad)
    {
        $query = "select public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno
        from inv.activos,public.personas
        where inv.activos.ofc_cod like '%" . $unidad . "%'and
        public.personas.nro_dip = inv.activos.ci_resp
        group by (public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno) order by (public.personas.nro_dip)";
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
    public static function getNewCodInv()
    {
        $idmax = DB::table('inv.doc_inv')->max('no_cod');
        $newId = ((int)$idmax) + 1;
        $cad = '0000' . $newId;
        return substr($cad, strlen($cad) - 4);
    }
    public static function getDatosByCodSoa($cod_soa)
    {
        $query = "select * from inv.ff_getdatosbycodsoa('". $cod_soa ."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //guardar datos del nuevo inventario
    public static function saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res, $estado, $gestion)
    {
        $no_doc = self::getNewCodInv();
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
        return ['data' => $data, 'no_doc' => $no_doc];
    }
    // peticiones de busqueda para activos segun id de oficina, de la subOficina o descripción  
    public static function SearchActive($cod_soa, $sub_ofc_ids, $descripcion)
    {
        $db = DB::table('inv.union_activos as ua')->select('ua.id', 'of.descripcion as oficina', 'sof.descripcion', 'ua.des', 'ua.estado')
            ->join('inv.oficinas as of', 'ua.ofc_cod', '=', 'of.cod_soa')->join('inv.sub_oficinas as sof', 'ua.sub_ofc_cod', '=', 'sof.id');
        if ($descripcion) {
            $db->where('ua.des', 'like', '%' . $descripcion . '%');
        }
        if ($cod_soa) {
            $db->where('of.cod_soa', $cod_soa);
        }
        if ($sub_ofc_ids) {
            $db->whereIn('ua.sub_ofc_cod', $sub_ofc_ids);
        }
        return $db;
    }
    // mostrar el activo por el ID 
    public static function showActiveById($id)
    {
        $query = "select ua.des,
        ua.des_det,
        ua.vida_util,
        ua.estado,
        ua.car_cod,
        ua.sub_ofc_cod,
        ua.ofc_cod,
        ua.ci_resp,
        ua.id,
        of.descripcion as oficina,
        sof.descripcion,
        c.descripcion as cargo,
        p.nombres,
        p.paterno,
        p.materno
        from inv.union_activos ua, inv.oficinas of,inv.sub_oficinas as sof,inv.cargos c,public.personas p
            where ua.id = " . $id . " 
                and of.cod_soa= ua.ofc_cod 
                and sof.id = ua.sub_ofc_cod
                and c.id = ua.car_cod
                and p.nro_dip = ua.ci_resp";
                // group by (ua.des,ua.des_det,ua.vida_util,ua.estado,ua.car_cod,ua.sub_ofc_cod,ua.ofc_cod,
                //     ua.ci_resp,ua.id, of.descripcion,sof.descripcion,c.descripcion,
                //     p.nombres,p.paterno,p.materno)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //mostrar el inventario por el ID 
    public static function showInventoryById($id)
    {
        $query = "select * from inv.doc_inv where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)))[0];
        $data->car_cod = array_map('intval', explode(',', str_replace('{', '', str_replace('}', '', $data->car_cod))));
        $data->res_enc = explode(',', str_replace('{', '', str_replace('}', '', $data->res_enc)));
        $data->sub_ofc_cod = array_map('intval', explode(',', str_replace('{', '', str_replace('}', '', $data->sub_ofc_cod))));
        $data->car_cod_resp = array_map('intval', explode(',', str_replace('{', '', str_replace('}', '', $data->car_cod_resp))));
        $data->ci_res =  explode(',', str_replace('{', '', str_replace('}', '', $data->ci_res)));

        return $data;
    }
    //Obtener la oficina por el Id 
    public static function getUnidadById($id)
    {
        $query = "select inv.oficinas.descripcion, inv.oficinas.cod_soa, inv.oficinas.cod_ofc , inv.oficinas.id
        from inv.oficinas 
        where inv.oficinas.id like '%" . $id . "%'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //ogtener las sub-oficinas por el Id
    public static function getSubUnidadById($id)
    {
        $query = "select inv.sub_oficinas.descripcion,inv.sub_oficinas.id
        from inv.sub_oficinas
        WHERE inv.sub_oficinas.id = " . $id . "
        group by (inv.sub_oficinas.descripcion,inv.sub_oficinas.id)";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //Obtener los cargos por el ID
    public static function getCargoById($id)
    {
        $query = "select inv.cargos.id , inv.cargos.descripcion
        from inv.cargos, inv.activos,inv.sub_oficinas
        where 
        inv.cargos.id=" . $id . "
        group by (inv.cargos.id,inv.cargos.descripcion)
        order by (inv.cargos.id)";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener el detalle del documento por el ID del activo
    public static function searchDocDetailByActiveId($id)
    {
        $query = " select * from inv.detalle_doc_act where id_act = " . $id . " ";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //Guardar cambios del Activo
    public static function saveChangeActive($cod_soa, $des, $des_det, $vida_util, $car_cod, $estado, $ofc_cod, $sub_ofc_cod, $ci_resp, $id)
    {
        $query = "select * from inv.f_guardar_activo('" . $cod_soa . "','" . $des . "', '" . $des_det . "','" . $vida_util . "','" . $car_cod . "','" . $estado . "','" . $ofc_cod . "','" . $sub_ofc_cod . "','" . $ci_resp . "','" . $id . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Buscar activos por el los datos del documento del inventario
    public static function SearchActiveForDocInvRegistered($no_cod)
    {
        $actIDsInDocDetail = DB::table('inv.detalle_doc_act')->where('inv.detalle_doc_act.doc_cod', $no_cod)->pluck('inv.detalle_doc_act.id_act');
        $query = DB::table('inv.union_activos')->select('inv.union_activos.*')
            ->whereIn('inv.union_activos.id', $actIDsInDocDetail);
        return $query->orderBy('inv.union_activos.id', 'asc');
    }
    public static function SearchActiveNotRegisteredInDocInv($ofc_cod, $sub_ofc_cods, $registereds)
    {
        $db = DB::table('inv.union_activos as ua')->select('ua.*')
            ->join('inv.oficinas as of', 'ua.ofc_cod', '=', 'of.cod_ofc')->join('inv.sub_oficinas as sof', 'ua.sub_ofc_cod', '=', 'sof.id');
        $db->whereNotIn('ua.id', $registereds);
        if ($ofc_cod) {
            $db->where('of.id', $ofc_cod);
        }
        if ($sub_ofc_cods) {
            $db->whereIn('ua.sub_ofc_cod', $sub_ofc_cods);
        }
        return $db->orderBy('ua.id', 'asc');
    }
    public static function SearchActiveForDocInv($no_cod, $ofc_cod, $sub_ofc_cods, $page = 1, $perPage = 10)
    {
        $l1 = static::SearchActiveForDocInvRegistered($no_cod);

        $arrayIds = $l1->pluck('id');
        $l2 = static::SearchActiveNotRegisteredInDocInv($ofc_cod, $sub_ofc_cods, $arrayIds);

        $lastPage = (int)ceil(($l1->count() + $l2->count()) / $perPage);
        $total = $l1->count() + $l2->count();

        $p1 = $l1->paginate($perPage, ['*'], 'page', $page);
        $data = [];
        if ($p1->count() == 0) {
            if ($l1->count() > 0) {
                $auxP1 = $l1->paginate($perPage, ['*'], 'page', $p1->lastPage());
                $l2page = $page - $p1->lastPage();
                $skip = $perPage - $auxP1->count() + ($l2page - 1) * $perPage;
                $data = $l2->skip($skip)->take($perPage)->get();
            } else {
                $data = $l2->paginate($perPage, ['*'], 'page', $page)->items();
            }
        } else {
            $data = $p1->items();
            foreach ($data as $act) {
                $act->detalle_doc_act = self::searchDocDetailByActiveId($act->id);
            }
            if ($p1->count() < $perPage) {
                $lack = $perPage - $p1->count();
                $data = array_merge($data, $l2->take($lack)->get()->toArray());
            }
        }

        $resp = [
            'current_page' => (int) $page,
            'data' => $data,
            'from' => ($page - 1) * $perPage + 1,
            'to' => $perPage * $page,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $total,
        ];
        return $resp;
    }
    //Guardar cambios del documento del Inventario boton de edit - > EditInventory2 
    public static function saveChangeDocInventory($id, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res)
    {
        /*$query = "select * from inv.f_guardar_cambios_doc( '1','{131033544}', '{3}', '0000001',
         '{1,3}', '{1,3}','{0}')";*/
        $query = " select * from inv.f_guardar_cambios_doc(
            '" . $id . "',
            '" . str_replace(']', '}', str_replace('[', '{', json_encode($res_enc))) . "',
            '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod))) . "',
            '" . $ofc_cod . "',
            '" . str_replace(']', '}', str_replace('[', '{', json_encode($sub_ofc_cod))) . "',
            '" . str_replace(']', '}', str_replace('[', '{', json_encode($car_cod_resp))) . "',
            '" . str_replace(']', '}', str_replace('[', '{', json_encode($ci_res))) . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Obtener los datos de la tabla de estados
    public static function getEstados()
    {
        $query = "select * from inv.estado";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Obtener los datos de la tabla de estados
    public static function getAllCargos()
    {
        $query = "select * from inv.cargos";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Guardar datos de los activos en Documento Detalle
    public static function saveActiveInDetailDoc($doc_cod, $cod_ges, $cod_act, $id_act, $id_des, $est_cod, $obs_est, $validacion, $id)
    {
        $fec_cre = Date('d-m-Y');
        $query = "Select * from inv.insertarActivoDocDetail('" . $doc_cod . "','" . $cod_ges . "','" . $cod_act . "','" . $id_act . "','" . $id_des . "','" . $fec_cre . "','" . $est_cod . "','" . $obs_est . "','" . $validacion . "','" . $id . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //elegir entre inventario general o detallado
    public static function selectInventory($tipo, $cod_soa, $resp)
    {
        if ($tipo == 1) {
            $query = "select * from inv.inventario_general('" . $cod_soa . "', '" . $resp . "')";
        } else {
            $query = "select * from inv.inventario_detalle('" . $cod_soa . "', '" . $resp . "')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //prueba de elegir por responsable activos de un inventario 
    public static function selectByCiResponsable($tipo, $cod_soa, $ci_resp)
    {
         $arrString = "{";
         foreach ($ci_resp as $k => $ci_resp)
             $arrString = $arrString . ($k > 0 ? ',' : '') . $ci_resp;
         $arrString = $arrString . '}';
        if ($tipo == 'general') {

            //$query = "select * from inv.ff_getactivosgeneralbyci('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_general('". $cod_soa ."') as t
            WHERE t.ci_resp in ('". $ci_resp ."')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallelbyci('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('". $cod_soa ."') as t
            WHERE t.ci_resp in ('". $ci_resp ."')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //prueba de seleccionar inventario por cargo
    public static function selectByCargo($tipo, $cod_soa, $cargo)
    {
         $arrString = "{";
         foreach ($cargo as $k => $cargo)
             $arrString = $arrString . ($k > 0 ? ',' : '') . $cargo;
         $arrString = $arrString . '}';
        if ($tipo == 'general') {

            //$query = "select * from inv.ff_getactivosgeneralbycargo('" . $cod_soa . "', '" . $arrString . "')"; 
            $query = "select * from act.ff_activos_general('". $cod_soa ."') as t
            WHERE t.car_cod in ('". $cargo ."')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallelbycargo('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('". $cod_soa ."') as t
            WHERE t.car_cod in ('". $cargo ."')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //
    public static function selectBysubUnidad($tipo, $cod_soa, $subUnidad)
    {
         $arrString = "";
         foreach ($subUnidad as $k => $subUnidad)
             $arrString = $arrString . ($k > 0 ? ',' : '') . $subUnidad;
         $arrString = $arrString . "";
        if ($tipo == 'general') {

            //$query = "select * from inv.ff_getactivosgeneralbysubunidad('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_general('". $cod_soa ."') as t
            WHERE t.sub_ofc_cod in ('". $subUnidad ."')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallebysubunidad('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('". $cod_soa ."') as t
            WHERE t.sub_ofc_cod in ('". $subUnidad ."')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
