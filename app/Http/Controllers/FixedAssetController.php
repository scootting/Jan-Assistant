<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FixedAsset;
use JasperPHP\JasperPHP as JasperPHP;
use Illuminate\Support\Collection;

use Illuminate\Pagination\LengthAwarePaginator;

class FixedAssetController extends Controller
{
    //

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}    
    public function getDocumentFixedAssetByYear(Request $request){
        $year = $request->get('year');
        $type = 1;
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

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}    
    public function getFixedAssetsbyDocument(Request $request){
        $document = $request->get('id');
        $data = FixedAsset::getFixedAssetsbyDocument($document);        
        return json_encode($data);
    }    

    public function getReportSelectedFixedAssets(Request $request){
        \Log::info("vas a ingresar a este punto");
        \Log::info($request->get('lista'));
        $lista = $request->get('lista');
        $lista = implode(',', $lista);
        \Log::info('la lista 2 es: '.$lista);

        $jasper = new JasperPHP;
        $input = public_path() . '/reports/Blank_Letter.jrxml';
        $jasper->compile($input)->execute();

        $input = public_path() . '/reports/Blank_Letter.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf'),//array('pdf', 'rtf'), // Formatos de salida del reporte
            array(
                'p_lista' => $lista, 
                ),
            array(/*
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',*/
            )  
        )->execute();

        $pathToFile = public_path() . '/reports/Blank_Letter.pdf';
        $filename = 'Blank_Letter.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }
}
