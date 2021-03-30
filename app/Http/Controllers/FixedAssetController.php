<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FixedAsset;

use Illuminate\Pagination\LengthAwarePaginator;

class FixedAssetController extends Controller
{
    //

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}    
    public function getDocumentFixedAssetByYear(Request $request){
        $year = $request->get('year');
        $type = 1;

        \Log::info("esta es una consulta: ".$request);
        $data = FixedAsset::GetDocumentFixedAssetByYear($year, $type);        
        $page = ($request->get('page')? $request->get('page'): 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentFixedAssets')]
        );
        return json_encode($paginate);
    }

}
