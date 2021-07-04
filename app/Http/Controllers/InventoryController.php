<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use JasperPHP\JasperPHP as JasperPHP;

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
    //obtener activos para la lista en la página de Inventarios (ACTIVOS POR UNIDAD).
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
                $data = Inventory::getActivosBySoa($tipo_reporte, $cod_soa);
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
    //reportes usando Jasper (REPORTES GENERAL Y DETALLADO)
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
                    $reportName = 'car_general_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_general_1'; //funciona
                    break;
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_general_1'; //funciona
                    break;
                case 'responsable':
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_general'; //funciona
                    break;
            }
            $report = JSRClient::GetReportWithParameters($reportName, $controls);
            return $report;
        } else { //detallado
            \Log::info('llegamos aca');
            \Log::info('tipo filtro: ' . $tipo_filtro);
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_detallado_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_detallado_1'; //funciona
                    break;
                case 'responsable':
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_detallado_1'; //funciona
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_detallado_1'; //funciona
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
    //USADO EN ACTIVOS,EDIT ACTIVOS Y EDIT-INVENTORY
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
        $gestion = '2021'; // $request->gestion;
        $data = Inventory::saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res, $estado, $gestion);
        return json_encode($data);
    }
    
    public function saveDatasDetail(Request $request)
    {
       // dd($request);
        $no_doc = $request->no_doc;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        //dd($no_doc,$ofc_cod,$sub_ofc_cod);
        $gestion = '2021';
        $data = Inventory::saveActivesToNewInventory($no_doc,$ofc_cod,$sub_ofc_cod,$gestion);
        return json_encode($data);
    }
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
    public function saveNewActive(Request $request)
    {
        //dd($request);
        $cod_soa = $request->cod_soa;
        $des = $request->des;
        $des_det = $request->des_det;
        $par_cod = $request->partida;
        $cod_con = $request->contable;
        $car_cod = $request->car_cod;
        $estado = $request->estado;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $ci_resp = $request->ci_resp;
        $nro_doc = $request->nro_doc;
        $idc = $request->cantidad;
        $imp_act = '2';
        $des_act = '1';
        $gestion = '2021';
        //dd($cod_soa, $des, $des_det, $par_cod,$cod_con,$car_cod, $estado, $sub_ofc_cod, $ci_resp,$nro_doc,$idc,$imp_act,$des_act,$gestion);
        $data = Inventory::saveNewActive($cod_soa, $des, $des_det, $par_cod,$cod_con,$car_cod, $estado, $sub_ofc_cod, $ci_resp,$nro_doc,$idc,$imp_act,$des_act,$gestion);
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
    //traer la lista de activos dentro del detalle
    public function getActivesForDocInv(Request $request, $doc_cod)
    {
        
            //dd($request);
            //$ofc_cod = ($request->get('idOffice')) ? $request->get('idOffice') : null;
            $keyWord = ($request->get('keyWord')) ? $request->get('keyWord') : '';
            //dd($keyWord,$ofc_id,$sub_ofc_ids);
            $data = Inventory::SearchActiveForDocInvRegistered($doc_cod,$keyWord);
            $page = ($request->get('page') ? $request->get('page') : 1);
            $perPage = 10;
            $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentqr')]
        );
        return json_encode($paginate);        
    }

    public function controlTrue(Request $request)
    {
        $no_cod = ($request->get('no_cod')) ? $request->get('no_cod') : '';
        //dd($no_cod);
        $data = Inventory::controlTrue($no_cod);
        return json_encode($data);
    }
    public function getEstados()
    {
        $data = Inventory::getEstados();
        return json_encode($data);
    }
    public function getPartidas()
    {
        $data = Inventory::getPartidas();
        return json_encode($data);
    }
    public function getContable()
    {
        $data = Inventory::getContable();
        return json_encode($data);
    }
    public function getlastNroDoc()
    {
        $data = Inventory::getLastNroDoc();
        return json_encode($data);
    }
    public function saveActiveInDetailDoc(Request $request)
    {
       //dd($request);
       if ($request->has('id_detalle')) {
        $id = $request->id_detalle;
    } else {
        $id = -1;
    }
        //$id = $request->id_detalle_doc;
        $nro_doc_inv = $request->nro_doc_inv;

        $cod_ges = $request->cod_ges;
        $cod_act = $request->cod_act;
        $id_act = $request->id_act;
        $id_des = $request->id_des;
        $est_act = $request->est_act;
        $obs_est = $request->obs_est;
        $validacion = ($request->post('validacion')) ? $request->post('validacion') : 'false';
        $guardado = $request->guardado;
        $data = Inventory::saveActiveInDetailDoc($nro_doc_inv, $cod_ges, $cod_act, $id_act, $id_des, $est_act, $obs_est, $validacion, $guardado, $id);
        return json_encode($data);
    }
    public function changeStateInventory(Request $request)
    {
        //dd($request);
        $estado = $request->estado;
        $observaciones = $request->observaciones;
        $id = $request->nro_cod;
        $verificado = $request->verificado;
        //dd($estado,$observaciones,$id);
        $data = Inventory::updateState($estado, $observaciones,$verificado ,$id);
        return json_encode($data);
    }
    public function getAllCargos()
    {
        $data = Inventory::getAllCargos();
        return json_encode($data);
    }
    //funcion cargar imagenes de activos para nuevo inventario
    public function uploadImage(Request $request)
    {
        $dataSource = $request->get('datasource');
        $arrayData = json_decode($dataSource, true);
        $ofc_cod = $arrayData['ofc_cod'];
        $id = $arrayData['id'];
        $sub_ofc_cod = $arrayData['sub_ofc_cod'];
        if ($request->hasFile('file')) 
        {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = 'upload/' . strval($ofc_cod) . '/' . strval($sub_ofc_cod). '/' . strval($id);
            $file->storeAs($path, $file_name);
        } 
        else 
        {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Cargo exitoso.', 'path' => '/' . $path . '/' . $file_name]);
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
    public static function getImagesById($cod_act)
    {
        $data = Inventory::getImagesByIdAct($cod_act);
        return json_encode($data);
    }
    public function getListNroDoc(Request $request)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getListNroDoc($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentqr')]
        );
        return json_encode($paginate);
    }
    public function getActivesbyDocument(Request $request)
    {
        $document = $request->get('id');
        $data = Inventory::getActivesbyDocument($document);
        return json_encode($data);
    }
    public function getReportSelectedActive(Request $request)
    {
        //dd($request);
        $lista = $request->lista;
        $lista2 = implode(",", $lista);
        // dd($lista2);
        //$l = '9999/98.2';

        $jasper = new JasperPHP;
        $input = public_path() . '/reports/ticketActiveQR.jrxml';
        $jasper->compile($input)->execute();
        $input = public_path() . '/reports/ticketActiveQR.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf', 'rtf'), // Formatos de salida del reporte
            array('p_lista' => $lista2),//array('php_version' => phpversion()),// Parámetros del reporte
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',
            )  
        )->execute();
        $pathToFile = public_path() . '/reports/ticketActiveQR.pdf';
        $filename = 'ticketActiveQR.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }
    public function informeGeneral(Request $request)
    {
        //dd($request);
        $no_doc = $request->no_doc;
        //dd($no_doc);
        $lista2 = "";
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/inventarioGeneral.jrxml';
        $jasper->compile($input)->execute();
        $input = public_path() . '/reports/inventarioGeneral.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf', 'rtf'), // Formatos de salida del reporte
            array('no_doc' => $no_doc),//array('php_version' => phpversion()),// Parámetros del reporte
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',
            )  
        )->execute();
        $pathToFile = public_path() . '/reports/inventarioGeneral.pdf';
        $filename = 'inventarioGeneral.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }
    public function inventarioTrue(Request $request)
    {
        //dd($request);
        $no_doc = $request->get('no_doc');
        $ofc_cod = $request->get('ofc_cod');
        $sub = $request->get('sub_ofc_cod');
       //$sub_ofc_cod = implode(",", $sub);
       $l = '000051';
       $l2 = '12345678';
       $l3 = '1';
       //dd($no_doc,$sub,$ofc_cod);
        $lista2 = '1';
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/inventarioDetalleTrue.jrxml';
        $jasper->compile($input)->execute();
        $input = public_path() . '/reports/inventarioDetalleTrue.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf', 'rtf'), // Formatos de salida del reporte
            array('p_no_doc' => $no_doc,'p_unidad' => $ofc_cod,'p_subUnidad' => $sub),//array('php_version' => phpversion()),// Parámetros del reporte
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',
            )  
        )->execute();
        $pathToFile = public_path() . '/reports/inventarioDetalleTrue.pdf';
        $filename = 'inventarioDetalleTrue.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }
}
