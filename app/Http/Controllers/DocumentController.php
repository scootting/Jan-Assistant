<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;

class DocumentController extends Controller
{
    //
    //  * Obtener las descripciones de el recurso utilizado.
    //  * {abr: }    
    public function getDescriptionByAbr($abr){        
        $data = Document::getDescriptionByAbr($abr);
        return json_encode($data);
    } 

}
