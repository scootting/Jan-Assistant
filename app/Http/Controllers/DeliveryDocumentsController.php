<?php

namespace App\Http\Controllers;

use App\DeliveryDocuments;

use Illuminate\Http\Request;

class DeliveryDocumentsController extends Controller
{
    /** 
     * Funcion que devolverá las entregas de documentos. 
     * @return 
     */
    public function getListDeliveryDocument($gestion)
    {
        $data = DeliveryDocuments::getListDocuments($gestion);
        return json_encode($data);
    }
    public function getDocumentsByInCharge(Request $request)
    {
        $result['listActivos'] = DeliveryDocuments::editActivo($request->nro_doc);
        $result['encargado'] = DeliveryDocuments::getInfoEncargado($request->nro_doc);
        //dd($result);
        return json_encode($result);
    }
    public function getListRecursos($gestion)
    {
        $result['destinos'] = DeliveryDocuments::getDestiny();
        $result['partidas'] = DeliveryDocuments::getPartidas($gestion);
        $result['gruposC'] = DeliveryDocuments::getContablesGroups();
        $result['unidMeds'] = DeliveryDocuments::getUni_meds();
        return json_encode($result);
    }
    public function updateStore(Request $request)
    {
        $data = DeliveryDocuments::updateAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        return json_encode($data);
    }

    public function storeEncargado(Request $request){
        $datos=$request->data;
        $data = DeliveryDocuments::addEncargado($datos['fecha_doc'], $datos['estado'], $datos['a'], $datos['responsable'],$datos['cargores'], $datos['gestion']);
        return json_encode($data);
    }
    public function storeAsset (Request $request)
    {
        //$data = DeliveryDocuments::addAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        
        return json_encode(['msn'=>'guardado del nuevo activo']);
    }
    public function searchResponsable ($keyword){
        $data = DeliveryDocuments::searchResponsable($keyword);
        return json_encode($data);
    }
    public function loadCargos ($responsable){
        $data = DeliveryDocuments::loadCargos($responsable);
        return json_encode($data);
    }
}
