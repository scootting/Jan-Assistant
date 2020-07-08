<?php

namespace App\Http\Controllers;

use App\CarpentryDocuments;

use Illuminate\Http\Request;

class CarpentryDocumentsController extends Controller
{
    public function getListCarpentryDocument($gestion)
    {
        $data = CarpentryDocuments::getListCarpentryDocuments($gestion);
        return json_encode($data);
    }
    public function getCarpentryDocumentsByInCharge (Request $request)
    {
        $result['listActivos'] = CarpentryDocuments::editActivo($request->nro_doc);
        $result['encargado'] = CarpentryDocuments::getInfoEncargado($request->nro_doc);
        //dd($result);
        return json_encode($result);
    }
    public function getListRecursos($gestion)
    {
        $result['destinos'] = CarpentryDocuments::getDestiny();
        $result['partidas'] = CarpentryDocuments::getPartidas($gestion);
        $result['gruposC'] = CarpentryDocuments::getContablesGroups();
        $result['unidMeds'] = CarpentryDocuments::getUni_meds();
        //dd($result);
        return json_encode($result);
    }
    public function updateStore(Request $request)
    {
        $data = CarpentryDocuments::updateAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        return json_encode($data);
    }

    public function storeEncargado(Request $request){
        $datos=$request->data;
        $data = CarpentryDocuments::addEncargado($datos['fecha_doc'], $datos['estado'], $datos['a'], $datos['responsable'],$datos['cargores'], $datos['gestion']);
        return json_encode($data);
    }
    public function storeAsset (Request $request)
    {
        //$data = CarpentryDocuments::addAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        
        return json_encode(['msn'=>'guardado del nuevo activo']);
    }
}
