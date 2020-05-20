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
}
