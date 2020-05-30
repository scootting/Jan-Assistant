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
        $result['destinos'] = DeliveryDocuments::getDestiny();
        $result['partidas'] = DeliveryDocuments::getPartidas($request->gestion);
        $result['gruposC'] = DeliveryDocuments::getContablesGroups();
        $result['unidMeds'] = DeliveryDocuments::getUni_meds();
        //dd($result);
        return json_encode($result);
    }
    public function storeActivo(Request $request)
    {
        //dd($request);
        $data = DeliveryDocuments::saveAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        return json_decode($data);
    }
}
