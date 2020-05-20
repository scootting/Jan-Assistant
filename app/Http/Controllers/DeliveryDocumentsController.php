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
    public function getListDeliveryDocument()
    {
        $data = DeliveryDocuments::getListDocuments();
        return json_encode($data);
    }
    public function getDocumentsByInCharge(Request $request)
    {
        $result['listActivos']= DeliveryDocuments::editActivo($request->nro_doc);
        $result['encargado'] = DeliveryDocuments::getInfoEncargado($request->nro_doc);
        //dd($result);
        return json_encode($result);
    }
}
