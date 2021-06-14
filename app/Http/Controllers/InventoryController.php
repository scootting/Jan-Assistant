<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryController extends Controller
{
    public function getOffices(Request $request, $gestion)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getOffices($gestion, $descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory')]
        );
        return json_encode($paginate);
    }
    public function getOfficeByCodSoa($cod_soa)
    {
        $data = Inventory::getOfficeByCodSoa($cod_soa);
        $data->so_cargos = Inventory::getDatosByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getSubOfficesByCodSoa($cod_soa)
    {
        $cod_soa = $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getSubOfficesByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getCargosByCodSoa($cod_soa)
    {
        $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getCargorsForActive($cod_soa);
        return json_encode($data);
    }
    public function getResponsablesByCodSoa($cod_soa)
    {
        $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getResponsablesForActive($cod_soa);
        return json_encode($data);
    }
    //obtener activos para la muestra en la página de Inventarios.
    public function getActivosByFilter(Request $request, $cod_soa)
    {
        //dd($request);
        $tipo_reporte = ($request->get('reporte'));
        $tipo_filtro = ($request->get('filtroTipo'));
        $valor = ($request->get('filtroValor'));
        $data = [];
        switch ($tipo_filtro) {
            case 'cargo':
                $data = Inventory::selectByCargo($tipo_reporte, $cod_soa, $valor);
                break;
            case 'subUnidad':
                $data = Inventory::selectBysubUnidad($tipo_reporte, $cod_soa, $valor);
                break;
            case 'responsable':
                $data = Inventory::selectByCiResponsable($tipo_reporte, $cod_soa, $valor);
                break;
            case 'todo':
                $data = Inventory::getActivosBySoaAndResp($tipo_reporte, $cod_soa, false);
                break;
        }

        $page = ($request->get('page')) ? $request->get('page') : null;
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory/activos/' . $cod_soa)]
        );
        return json_encode($paginate);
    }

    //reportes usando Jasper
    public function getReport(Request $request)
    {
        $tip_repo = $request->get('reporte');
        $ofc_cod = $request->get('ofc_cod');
        $tipo_filtro = $request->get('filtroTipo');
        $valor = $request->get('filtroValor');
        if ($tip_repo == 'general') {
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_general_1';//funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_general_1';//funciona
                    break;
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_general_1';//funciona
                    break;
                case 'responsable':
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_general';//funciona
                    break;
            }
            $report = JSRClient::GetReportWithParameters($reportName, $controls);
            return $report;
        } else { //detallado
            \Log::info('llegamos aca');
            \Log::info('tipo filtro: '.$tipo_filtro);
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_detallado_1';//funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_detallado_1';//funciona
                    break;
                case 'responsable':
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_detallado_1';//funciona
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_detallado_1';//funciona
                    break;
            }
            $report = JSRClient::GetReportWithParameters($reportName, $controls);
            return $report;
        }
    }

    //OBTENER LOS INVENTARIOS CREADOS (NUEVOS) BUSCADOR
    public function getInventories(Request $request, $gestion)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getInventories($gestion, $descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2')]
        );
        return json_encode($paginate);
    }
    public function getUnidad(Request $request)
    {
        $keyWord = ($request->get('keyWord') ? $request->get('keyWord') : '');
        $data = Inventory::getUnidad($keyWord);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2/unidad')]
        );
        return json_encode($paginate);
    }
    public function getSubUnidad(Request $request)
    {
        $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
        $idoffice = ($request->get('idoffice') ? $request->get('idoffice') : '');
        $cod_ofc = ($request->get('cod_ofc') ? $request->get('cod_ofc') : '');
        $data = Inventory::getSubUnidades($unidad, $idoffice, $cod_ofc);
        return json_encode($data);
    }
    public function getCargos(Request $request)
    {
        $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
        $sub_unidades = ($request->get('sub_unidades') ? $request->get('sub_unidades') : []);
        $data = Inventory::getCargos($unidad, $sub_unidades);
        return json_encode($data);
    }
    public function getResponsables(Request $request)
    {
        $unidad = ($request->get('unidad') ? $request->get('unidad') : '');
        $cargos = ($request->get('cargos') ? $request->get('cargos') : []);
        $data = Inventory::getResponsables($unidad, $cargos);
        return json_encode($data);
    }
    //esto es para inventarios (normal) que ya se tiene listado.
    public function getResponsablesByUnidad(Request $request)
    {
        $unidad = ($request->get('unidad') ? $request->get('unidad') : '');
        $data = Inventory::getResponsablesbyUnidad($unidad);
        return json_encode($data);
    }
    public function getEncargados(Request $request)
    {
        $nro_dip = ($request->get('nro_dip') ? $request->get('nro_dip') : '');
        $data = Inventory::getEncargados($nro_dip);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2/encargados')]
        );
        return json_encode($paginate);
    }
    public function saveNewInventory(Request $request)
    {
        //dd($request);
        $no_doc = $request->no_doc;
        $res_enc = $request->encargados;
        $car_cod_enc = [];
        for ($i = 0; $i < count([$res_enc]); $i++) {
            $car_cod_enc[] = 3;
        }
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->unidad;
        $sub_ofc_cod = $request->subUnidades;
        $car_cod_resp = $request->cargos;
        $ci_res = $request->responsables;
        $estado = 'ELABORADO';
        $gestion = '2020'; //$request->gestion;
        $data = Inventory::saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res, $estado, $gestion);
        return json_encode($data);
    }
    //
    public function SearchActivo(Request $request)
    {
        //dd($request);
        $descripcion = ($request->get('descripcion')) ? $request->get('descripcion') : null;
        $cod_soa = ($request->get('codSoa')) ? $request->get('codSoa') : null;
        $sub_ofc_ids = ($request->get('idSubOffice')) ? $request->get('idSubOffice') : null;
        //dd($descripcion,$ofc_id,$sub_ofc_ids);
        $data = Inventory::SearchActive($cod_soa, $sub_ofc_ids, $descripcion);
        $page = ($request->get('page')) ? $request->get('page') : null;
        $perPage = 10;
        $paginator = $data->paginate($perPage, ['*'], 'page', $page);
        $paginate = new LengthAwarePaginator(
            $paginator->items(),
            $paginator->total(),
            $perPage,
            $page,
            []
        );
        return json_encode($paginate);
    }
    public function getDocDetailByActivoId($id)
    {
        $data = Inventory::getDocDetailByActivoId($id);
        return json_encode($data);
    }
    public function getActive($id)
    {
        $data = Inventory::showActiveById($id);
        return json_encode($data);
    }
    public function getInventory($id)
    {
        $data = Inventory::showInventoryById($id);
        return json_encode($data);
    }
    public function saveChangeActive(Request $request)
    {
        //dd($request);
        $cod_soa = $request->cod_soa;
        $des = $request->des;
        $des_det = $request->des_det;
        $vida_util = $request->vida_util;
        $car_cod = $request->car_cod;
        $estado = $request->estado;
        $ofc_cod = $request->cod_soa;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $ci_resp = $request->ci_resp;
        $id = $request->id;
        $data = Inventory::saveChangeActive($cod_soa, $des, $des_det, $vida_util, $car_cod, $estado, $ofc_cod, $sub_ofc_cod, $ci_resp, $id);
        return json_encode($data);
    }
    public function saveChangeDocInventory(Request $request)
    {
        //dd($request);
        $id = $request->id;
        $res_enc = $request->res_enc;
        $car_cod_enc = [];
        for ($i = 0; $i < count([$res_enc]); $i++) {
            $car_cod_enc[] = 3;
        }
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $car_cod_resp = $request->car_cod_resp;
        $ci_res = $request->ci_res;
        $data = Inventory::saveChangeDocInventory($id, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res);
        return json_encode($data);
    }
    public function showDocInventory($no_cod)
    {
        $data = Inventory::showInventoryById($no_cod);
        $data->sub_oficinas = [];
        foreach ($data->sub_ofc_cod as $idso) {
            $data->sub_oficinas[] = Inventory::getSubUnidadById($idso);
        }
        $data->oficina = Inventory::getOfficeByCodSoa($data->ofc_cod);
        $data->cargos_encargados = [];
        foreach ($data->car_cod as $idc) {
            $data->cargos_encargados[] = Inventory::getCargoById($idc);
        }
        $data->encargados = [];
        foreach ($data->res_enc as $nd) {
            $data->encargados[] = Inventory::getEncargados($nd)[0];
        }
        $data->cargos_responsables = [];
        foreach ($data->car_cod_resp as $idc) {
            $data->cargos_responsables[] = Inventory::getCargoById($idc);
        }
        $data->responsables = [];
        foreach ($data->ci_res as $nd) {
            $data->responsables[] = Inventory::getEncargados($nd)[0];
        }
        return json_encode($data);
    }
    //reutilizar para traer los activos que no estan aun verificados
    public static function getActivesForDocInv(Request $request, $doc_cod)
    {
        //dd($request);
        $ofc_id = ($request->get('idOffice')) ? $request->get('idOffice') : null;
        $sub_ofc_ids = ($request->get('idSubOffices')) ? $request->get('idSubOffices') : null;
        $keyWord = ($request->get('keyWord')) ? $request->get('keyWord') : '';
        //dd($keyWord,$ofc_id,$sub_ofc_ids);
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $perPage = 10;
        $data = Inventory::SearchActiveForDocInv($doc_cod, $ofc_id, $sub_ofc_ids, $keyWord, $page, $perPage);
        return json_encode($data);
    }
    
    public function getEstados()
    {
        $data = Inventory::getEstados();
        return json_encode($data);
    }
    public function saveActiveInDetailDoc(Request $request)
    {
        // dd($request);
        if ($request->has('id')) {
            $id = $request->id;
        } else {
            $id = -1;
        }
        $nro_doc_inv = $request->doc_cod;
        $cod_ges = $request->cod_ges;
        $cod_act = $request->cod_act;
        $id_act = $request->id_act;
        $id_des = $request->id_des;
        $est_act = $request->est_act;
        $obs_est = $request->obs_est;
        $validacion = $request->validacion;
        $data = Inventory::saveActiveInDetailDoc($nro_doc_inv, $cod_ges,$cod_act,$id_act, $id_des, $est_act, $obs_est, $validacion, $id);
        return json_encode($data);
    }
    public function changeStateInventory( Request $request)
    {
       //dd($request);
        $estado=$request->estado;
        $observaciones=$request->observaciones;
        $id=$request->nro_cod;
       //dd($estado,$observaciones,$id);
        $data = Inventory::updateState($estado, $observaciones, $id);
        return json_encode($data);
    }
    public function getAllCargos()
    {
        $data = Inventory::getAllCargos();
        return json_encode($data);
    }
    //funcion cargar imagenes de activos para nuevo inventario
    public function index(){
        return Storage::files('upload');
    }
    public function uploadImage( Request $request){
        //dd($request);
        $dataSource = $request->get('datasource');
        $arrayData = json_decode($dataSource, true);
        $ofc_cod = $arrayData['ofc_cod'];
        $id = $arrayData['id'];    

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = 'upload/'.strval($id).'/'.strval($ofc_cod);
            $file->storeAs($path, $file_name);
        } else {
            return response()->json(['error'=>'File not exist!']);
        }
        return response()->json(['success'=>'Cargo exitoso.','path' => '/'.$path.'/'.$file_name]);
    } 
    public function saveImages(Request $request)
    {
        //dd($request);
        $cod_act = $request->cod_act;
        $img_fro = $request->img_fro;
        $img_der = $request->img_der;
        $img_izq = $request->img_izq;
        $img_post = $request->img_post;
        $img_sup = $request->img_sup;
        $data = Inventory::saveImage($cod_act, $img_fro, $img_izq, $img_der, $img_sup, $img_post);
        return json_encode($data);
    }

}
