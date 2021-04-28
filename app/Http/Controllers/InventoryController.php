<?php

namespace App\Http\Controllers;

use App\Inventory;
use Dotenv\Result\Result;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use JasperPHP\JasperPHP as JasperPHP;
use SebastianBergmann\Environment\Console;

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
        return json_encode($data);
    }
    public function getSubOfficesByCodSoa($cod_soa)
    {
        $cod_soa=$cod_soa=='null'? null : $cod_soa;
        $data = Inventory::getSubOfficesByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getRespToCargosByCodSoa($cod_soa)
    {
        $cod_soa = $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getCargosByCodSoa($cod_soa);
        return json_encode($data);
    }
    //obtener activos para la muestra en la página de Inventarios. 
    public function getActivosBySoaAndResp(Request $request, $cod_soa)
    {
        $tipo_reporte = ($request->get('reporte'));
        $tipo_filtro = ($request->get('filtroTipo'));
        $valor= ($request->get('filtroValor'));
        $data = [];
        switch($tipo_filtro){
            case 'cargo':
                $data = Inventory::selectByCargo($tipo_reporte,$cod_soa,$valor);
                break;
            case 'subUnidad':
                $data = Inventory::selectBysubUnidad($tipo_reporte,$cod_soa,$valor);
                break;
            case 'responsable':
                $data = Inventory::selectByCiResponsable($tipo_reporte,$cod_soa,$valor);
                break;         
            case'todo':
                $data = Inventory::getActivosBySoaAndResp($cod_soa, false);
                break; 
        }
    
        //$data = Inventory::getActivosBySoaAndResp($cod_soa,$ci_resp);
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
    public function getActivosByCodSoaAndSubOffice(Request $request, $cod_soa)
    {
        $idso = ($request->get('idSubOffice')) ? $request->get('idSubOffice') : null;
        $data = Inventory::getActivosByCodSoaAndSubOffice($cod_soa, $idso);
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
    public function getReport(Request $request, $cod_soa)
    {
        // $jasper = new JasperPHP;
        // $input = public_path() . '/reports/assets.jrxml';
        // $jasper->compile($input)->execute();
        // $input = public_path() . '/reports/assets.jasper'; //ReportValuesQr
        // $output = public_path() . '/reports';
        // $jasper->process(
        //     $input,
        //     false, //$output,
        //     array('pdf', 'rtf'), // Formatos de salida del reporte
        //     array('p_ofc_cod' => $cod_soa),//array('php_version' => phpversion()),// Parámetros del reporte
        //     array(
        //         'driver' => 'postgres',
        //         'username' => 'postgres',
        //         'password' => '12345678',
        //         'host' => '192.168.25.64',
        //         'database' => 'daf_help',
        //         'port' => '5432',
        //     )  
        // )->execute();
        $pathToFile = $this->generarReporte('assets',array('p_ofc_cod' => $cod_soa));//public_path() . '/reports/assets.pdf';
        $filename = 'assets.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }
    //Funcion para generar reportes desde la vista inventarioDetail
    public function getGenerarReporte(Request $request)
    {
        //dd($request);
        $cod_ofc = $request->ofc_cod;
        $tipo_reporte = ($request->get('reporte'));
        $tipo_filtro = ($request->get('filtroTipo'));
        $valor= ($request->get('filtroValor'));
        if($tipo_reporte=='general'){
            switch($tipo_filtro){
                case 'cargo':
                    $param = array('cargo'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('cargoGeneral',$param);
                    $filename = 'cargoGeneral.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;
                case 'subUnidad':
                    $param = array('subUnidad'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('subUnidadGeneral1',$param);
                    $filename = 'subUnidadGeneral1.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;
                case 'responsable':
                    $param = array('ci_list'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('responsableGeneral',$param);
                    $filename = 'responsableGeneral.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;         
                case'todo':
                    return $this->getReport($request, $cod_ofc);
                    break; 
            }
    
        }
        if($tipo_reporte=='detallado'){
            switch($tipo_filtro){
                case 'cargo':
                    $param = array('cargo'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('cargoDetalle',$param);
                    $filename = 'cargoDetalle.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;
                case 'subUnidad':
                    $param = array('subUnidad'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('subUnidadDetalle',$param);
                    $filename = 'subUnidadDetalle.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;
                case 'responsable':
                    $param = array('ci_resp'=>implode(',',$valor),'unidad'=> $cod_ofc);
                    $pathToFile = $this->generarReporte('reporteDetallado',$param);
                    $filename = 'reporteDetallado.pdf';
                    $headers = ['Content-Type' => 'application/pdf'];
                    return response()->download($pathToFile, $filename, $headers);
                    break;         
                case'todo':
                    return $this->getReport($request, $cod_ofc);
                    break; 
            }
    
        }
    }
//generador de reporte (comun denominador)
    public static function generarReporte($reportName,$parametros)
    {
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/'.$reportName.'.jrxml';
        $jasper->compile($input)->execute();
        $input = public_path() . '/reports/'.$reportName.'.jasper'; //ReportValuesQr
        $output = public_path() . '/reports/';
        $jasper->process(
            $input,
            $output,
            array('pdf', 'rtf'), // Formatos de salida del reporte
            $parametros,//array('php_version' => phpversion()),// Parámetros del reporte
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '12345678',
                'host' => '192.168.25.64',
                'database' => 'daf_help',
                'port' => '5432',
            )
        )->execute();
        $pathToFile = public_path() . '/reports/'.$reportName.'.pdf';
        return $pathToFile;
    }

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
        $data = Inventory::getSubUnidades($unidad,$idoffice,$cod_ofc);
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
    //esto es para inventarios (nolmal) que ya se tiene listado. 
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
        for ($i = 0; $i < count([$res_enc]); $i++)
            $car_cod_enc[] = 3;
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->unidad;
        $sub_ofc_cod = $request->subUnidades;
        $car_cod_resp = $request->cargos;
        $ci_res = $request->responsables;
        $estado = 'ELABORADO';
        $gestion = '2020'; //$request->gestion;
        $data = Inventory::saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res,$estado, $gestion);
        return json_encode($data);
    }
    public function SearchActivo(Request $request)
    {
        $descripcion = ($request->get('descripcion')) ? $request->get('descripcion') : null;
        $ofc_ids = ($request->get('idOffice')) ? $request->get('idOffice') : null;
        $sub_ofc_ids = ($request->get('idSubOffice')) ? $request->get('idSubOffice') : null;
        //dd($descripcion,$ofc_ids,$sub_ofc_ids);
        $data = Inventory::SearchActive($ofc_ids, $sub_ofc_ids,$descripcion);
        $page = ($request->get('page')) ? $request->get('page') : null;
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            []
        );
        return json_encode($paginate);
    }
    public function getDocDetailByActivoId( $id )
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
        $des = $request->des;
        $des_det = $request->des_det;
        $vida_util= $request->vida_util;
        $car_cod = $request -> car_cod;
        $estado= $request->estado;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $ci_resp = $request->ci_resp;
        $id = $request->id;
        $data = Inventory::saveChangeActive($des, $des_det,$vida_util,$car_cod,$estado,$ofc_cod,$sub_ofc_cod,$ci_resp,$id);
        return json_encode($data);
    } 
    public function saveChangeDocInventory(Request $request)
    {
        //dd($request);
        $id = $request->id;
        $res_enc = $request->res_enc;
        $car_cod_enc = [];
        for ($i = 0; $i < count([$res_enc]); $i++)
            $car_cod_enc[] = 3;
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $car_cod_resp = $request->car_cod_resp;
        $ci_res = $request->ci_res;
        $data = Inventory::saveChangeDocInventory($id,$res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res);
        return json_encode($data);
    }
    public function showDocInventory($no_cod)
    {
        $data = Inventory::showInventoryById($no_cod);
        $data->sub_oficinas=[];
        foreach($data->sub_ofc_cod as $idso){
            $data->sub_oficinas[]= Inventory::getSubUnidadById($idso);
        }
        $data->oficina= Inventory::getOfficeByCodSoa($data->ofc_cod);
        $data->cargos_encargados=[];
        foreach($data->car_cod as $idc){
            $data->cargos_encargados[]= Inventory::getCargoById($idc);
        }
        $data->encargados=[];
        foreach($data->res_enc as $nd){
            $data->encargados[]= Inventory::getEncargados($nd)[0];
        }
        $data->cargos_responsables=[];
        foreach($data->car_cod_resp as $idc){
            $data->cargos_responsables[]= Inventory::getCargoById($idc);
        }
        $data->responsables=[];
        foreach($data->ci_res as $nd){
            $data->responsables[]= Inventory::getEncargados($nd)[0];
        }
        return json_encode($data);
    }
    public static function getActivesForDocInv(Request $request, $doc_cod)
    {
        $ofc_id = ($request->get('idOffice')) ? $request->get('idOffice') : null;
        $sub_ofc_ids = ($request->get('idSubOffices')) ? $request->get('idSubOffices') : null;
        //dd($descripcion,$ofc_ids,$sub_ofc_ids);
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $perPage = 10;
        $data = Inventory::SearchActiveForDocInv($doc_cod,$ofc_id, $sub_ofc_ids,$page,$perPage);
        
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
        if($request->has('id'))
            $id= $request->id;
        else
        $id = -1;
        $doc_cod = $request->doc_cod;
        $cod_ges = $request->cod_ges;
        $cod_act = $request->cod_act;
        $id_act = $request->id_act;
        $id_des = $request->id_des;
        $est_cod = $request->est_cod;
        $obs_est = $request->obs_est;
        $validacion = $request->validacion;
        $data = Inventory::saveActiveInDetailDoc($doc_cod,$cod_ges,$cod_act,$id_act,$id_des,$est_cod,$obs_est,$validacion,$id);
        return json_encode($data);
    }
    public function getAllCargos()
    {
        $data = Inventory::getAllCargos();
        return json_encode($data);
    }
}
