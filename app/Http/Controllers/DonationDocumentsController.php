<?php

namespace App\Http\Controllers;

use App\DonationDocuments;

use Illuminate\Http\Request;

class DonationDocumentsController extends Controller
{
    public function getListDonationDocument($gestion)
    {
        $data = DonationDocuments::getListDonationDocuments($gestion);
        return json_encode($data);
    }
    public function getDonationDocumentsByInCharge (Request $request)
    {
        $result['listActivos'] = DonationDocuments::editActivo($request->nro_doc);
        $result['encargado'] = DonationDocuments::getInfoEncargado($request->nro_doc);
        //dd($result);
        return json_encode($result);
    }
    public function getListRecursos($gestion)
    {
        $result['destinos'] = DonationDocuments::getDestiny();
        $result['partidas'] = DonationDocuments::getPartidas($gestion);
        $result['gruposC'] = DonationDocuments::getContablesGroups();
        $result['unidMeds'] = DonationDocuments::getUni_meds();
        //dd($result);
        return json_encode($result);
    }
    public function updateStore(Request $request)
    {
        $data = DonationDocuments::updateAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        return json_encode($data);
    }

    public function storeEncargado(Request $request){
        $datos=$request->data;
        $data = DonationDocuments::addEncargado($datos['fecha_doc'], $datos['estado'], $datos['a'], $datos['responsable'],$datos['cargores'], $datos['gestion']);
        return json_encode($data);
    }
    public function storeAsset (Request $request)
    {
        //$data = DonationDocuments::addAsset($request->cantidad, $request->descripcion, $request->des_det, $request->uni_med, $request->id_partida, $request->id_contable, $request->vida_util, $request->pre_uni, $request->nro_fac, $request->id);
        
        return json_encode(['msn'=>'guardado del nuevo activo']);
    }
}
