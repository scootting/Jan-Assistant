<?php

namespace App;

use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
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
        from inv.union_activos " . ($cod_soa ? ("where ofc_cod = '" . $cod_soa . "'") : "") . " 
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
    public static function getActivosBySoa($tipo, $cod_soa)
    {
        if ($tipo == 'general') {
            $query = "select * from act.ff_activos_general('" . $cod_soa . "')";
        } else {
            $query = "select * from act.ff_activos_detallado('" . $cod_soa . "')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // parte de Inventarios 2 (renombrado como INVENTARIO)
    //obtener inventarios creados y listarlos para EDITAR, VER LISTA O IMPRIMIR 
    public static function getInventories($gestion, $descripcion)
    {
        $query = "select inv.doc_inv.id, inv.doc_inv.no_cod, inv.doc_inv.ofc_cod,inv.doc_inv.sub_ofc_cod,
        inv.oficinas.descripcion,inv.doc_inv.estado,inv.doc_inv.verificado
        from inv.doc_inv , inv.oficinas where 
        inv.doc_inv.gestion = " . $gestion . "
        and inv.doc_inv.ofc_cod = inv.oficinas.cod_soa 
        and inv.oficinas.descripcion like '%" . $descripcion . "%'
        order by inv.doc_inv.no_cod desc
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
        $query = $query . "group by sof.id, sof.descripcion";
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
        group by inv.cargos.id,inv.cargos.descripcion
        ";
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
        public.personas.paterno,public.personas.materno 
        from inv.activos,public.personas,inv.cargos
        where inv.activos.ofc_cod like '%" . $unidad . "%'and
        public.personas.nro_dip = inv.activos.ci_resp
        and inv.activos.car_cod = inv.cargos.id
        " . (count($cargos) > 0 ? "and inv.cargos.id in "
            . $arrString : "") . "
        group by public.personas.nro_dip,public.personas.nombres,
        public.personas.paterno,public.personas.materno";
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
    //PARA AGARRAR CARGOS Y SUB UNIDADES E INSERTAR A LA TABLA DOC INV
    public static function getDatosByCodSoa($cod_soa)
    {
        $query = "select * from inv.ff_getdatosbycodsoa('" . $cod_soa . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener los encargados para crear nuevo inventario NAN NAN NAN
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
        $cad = '000000' . $newId;
        return substr($cad, strlen($cad) - 6);
    }
    //guardar datos del nuevo inventario
    public static function saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res, $estado, $gestion)
    {
        $no_doc = self::getNewCodInv();
        //$n = static::saveActivesToNewInventory($no_doc);
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
        WHERE inv.sub_oficinas.id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //Obtener los cargos por el ID
    public static function getCargoById($id)
    {
        $query = "select inv.cargos.id , inv.cargos.descripcion
        from inv.cargos
        where 
        inv.cargos.id='" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtener el detalle del documento por el ID del activo
    public static function searchDocDetailByActiveId($id,$no_doc)
    {
        $query = " select * from inv.detalle_doc_act where id_act = " . $id . " and nro_doc_inv = '". $no_doc ."'";
        $data = collect(DB::select(DB::raw($query)));
        return $data[0];
    }
    //Guardar cambios del Activo
    public static function saveChangeActive($cod_soa, $des, $des_det, $vida_util, $car_cod, $estado, $ofc_cod, $sub_ofc_cod, $ci_resp, $id)
    {
        $query = "select * from inv.f_guardar_activo('" . $des . "', '" . $des_det . "','" . $vida_util . "','" . $car_cod . "','" . $estado . "','" . $ofc_cod . "','" . $sub_ofc_cod . "','" . $ci_resp . "','" . $id . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function saveNewActive($cod_soa, $des, $des_det, $par_cod,$cod_con,$car_cod, $estado, $sub_ofc_cod, $ci_resp,$nro_doc,$idc,$imp_act,$des_act,$gestion)
    {
        $query = "select * 
        from inv.multi_guardar_activo('" . $cod_soa . "', '" . $des . "','" . $des_det . "','" . $par_cod . "','" . $cod_con . "','" . $car_cod . "','" . $estado . "','" . $sub_ofc_cod . "','" . $ci_resp . "','".$nro_doc."','".$idc."','".$imp_act."','".$des_act."','".$gestion."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Insertar activos cuando se registra un nuevo inventario
    public static function saveActivesToNewInventory($no_doc, $ofc_cod, $sub_ofc_cod, $gestion)
    {
        $arrString = "(";
        foreach ($sub_ofc_cod as $k => $su)
            $arrString = $arrString . ($k > 0 ? ',' : '') . $su;
        $arrString = $arrString . ")";
        $query = "insert into inv.detalle_doc_act (cod_ges,id_act,id_des,nro_doc_inv) 
        (select '" . $gestion . "',vd.id,vd.per_tab,
        (select no_cod from inv.doc_inv   where ofc_cod = '" . $ofc_cod . "' and no_cod = '" . $no_doc . "') nro_cod_inv
        from act.vv_act_detallado vd 
        where vd.ofc_cod = '" . $ofc_cod . "' 
        )";
        //$query = "select * from inv.insert_by_no_doc ( '".$no_doc."','".$ofc_cod."','".$sub_ofc_cod."','". $gestion ."' )";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //Buscar activos por el los datos del documento del inventario
    public static function SearchActiveForDocInvRegistered($no_cod ,$keyWord)
    {
        $query = "select v.id, d.id_detalle,d.nro_doc_inv,d.cod_ges,d.cod_act,d.cod_nue,
        d.id_act,d.id_des,d.validacion,d.est_act,d.obs_est,v.act_des,d.guardado,v.per_tab
        from inv.detalle_doc_act d inner join act.vv_act_detallado v
                ON d.id_act = v.id
                and d.nro_doc_inv = '".$no_cod."'
                and v.act_des like '%".$keyWord."%'
                order by v.id asc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function controlTrue($no_cod)
    {
        $query = "select count(d.guardado)guardado from 
        inv.detalle_doc_act d where 
        d.guardado = true and d.nro_doc_inv like '%". $no_cod ."%'";
        $data = DB::select($query);
        return $data;
    }
    //Guardar cambios del documento del Inventario boton de edit - > EditInventory2 
    public static function saveChangeDocInventory($id, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res)
    {
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
    public static function getPartidas()
    {
        $query = "select * from act.partida where gestion = 2021";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getContable()
    {
        $query = "select * from act.act_con";
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
    public static function saveActiveInDetailDoc($nro_doc_inv, $cod_ges, $cod_act, $id_act, $id_des, $est_act, $obs_est, $validacion, $guardado, $id)
    {
        $query = "Select * from inv.insertarActivoDocDetail('" . $nro_doc_inv . "','" . $cod_ges . "','" . $cod_act . "','" . $id_act . "','" . $id_des . "','" . $validacion . "','" . $est_act . "','" . $obs_est . "','" . $guardado . "','" . $id . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function updateState($estado, $observacion,$verificado ,$nro_cod)
    {
        $fecha_fin = Date('d-m-Y');
        $query = "select * from inv.f_guardar_update_doc_inv('" . $estado . "','" . $observacion . "','" . $fecha_fin . "','". $verificado ."','" . $nro_cod . "')";
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
            $query = "select * from act.ff_activos_general('" . $cod_soa . "') as t
            WHERE t.ci_resp in ('" . $ci_resp . "')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallelbyci('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('" . $cod_soa . "') as t
            WHERE t.ci_resp in ('" . $ci_resp . "')";
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
            $query = "select * from act.ff_activos_general('" . $cod_soa . "') as t
            WHERE t.car_cod in ('" . $cargo . "')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallelbycargo('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('" . $cod_soa . "') as t
            WHERE t.car_cod in ('" . $cargo . "')";
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
            $query = "select * from act.ff_activos_general('" . $cod_soa . "') as t
            WHERE t.sub_ofc_cod in ('" . $subUnidad . "')";
        } else {
            //$query = "select * from inv.ff_getactivosdetallebysubunidad('" . $cod_soa . "', '" . $arrString . "')";
            $query = "select * from act.ff_activos_detallado('" . $cod_soa . "') as t
            WHERE t.sub_ofc_cod in ('" . $subUnidad . "')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function saveImage($cod_act, $img_fro, $img_izq, $img_der, $img_sup, $img_post)
    {
        $query = "Select * from act.f_guardar_imagen('" . $cod_act . "','" . $img_fro . "','" . $img_izq . "','" . $img_der . "','" . $img_sup . "','" . $img_post . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getImagesByIdAct($cod_act)
    {
        $query = "Select * from act.act_img where cod_act = '" . $cod_act . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getListActivesbyNroDoc($cod_soa, $sub_ofc_cod, $nro_doc_inv)
    {
        $query = "select * from inv.ff_getlistactivosnotdetail('" . $cod_soa . "','" . $sub_ofc_cod . "','" . $nro_doc_inv . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getListNroDoc($descripcion)
    {
        $query = "select nro_doc,des,des_det from inv.activos 
        where des like '%". $descripcion ."%'
        GROUP BY nro_doc,des,des_det  order by nro_doc DESC";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getActivesbyDocument($document){
        $query = "select nro_doc as codigo,act_des from act.vv_act_detallado a where a.nro_doc like '".$document."%'";
        $data = collect(DB::select(DB::raw($query)));    
        return $data;
    } 

public static function getLastNroDoc()
{
    $query="
    select distinct 
    split_part(nro_doc, '/', 1)::integer + 1 as numero
    from inv.activos
    where split_part(nro_doc, '/', 2) = '98' and nro_doc is not null and split_part(nro_doc, '/', 1) <> '' and split_part(nro_doc, '/', 2) <> '' and nro_doc not ilike '%A%'
    order by numero desc
    fetch first 1 row only";
    $data = collect(DB::select(DB::raw($query)));    
        return $data;
}
    
}
