<?php

namespace App\Http\Controllers;

use App\FixedAsset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use JasperPHP\JasperPHP as JasperPHP;
use Jaspersoft\Client\Client;
use Illuminate\Support\Facades\Storage;
//$report = get_file_contents(JASPERREPORTS_URL . '/rest_v2/reports/reports/userHistory.pdf?user_id=123&j_username=' . JASPERREPORTS_USER . '&j_password=' . JASPERREPORTS_PASS);


class FixedAssetController extends Controller
{
    //

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}
    public function getDocumentFixedAssetByYear(Request $request)
    {
        $year = $request->get('year');
        $type = 1;
        $data = FixedAsset::GetDocumentFixedAssetByYear($year, $type);
        $page = ($request->get('page') ? $request->get('page') : 1);
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
    public function getFixedAssetsbyDocument(Request $request)
    {
        $document = $request->get('id');
        $data = FixedAsset::getFixedAssetsbyDocument($document);
        return json_encode($data);
    }

    public function getReportSelectedFixedAssets(Request $request)
    {
        $lista = $request->get('lista');
        $lista = implode(',', $lista);
        /*
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/FixedAssetsQr.jrxml'; //Blank_Letter.jrxml
        $jasper->compile($input)->execute();

        $input = public_path() . '/reports/FixedAssetsQr.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf'), //array('pdf', 'rtf'), // Formatos de salida del reporte
            array(
                'p_lista' => $lista,
            ),
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',
            )
        )->execute();

        $pathToFile = public_path() . '/reports/FixedAssetsQr.pdf';
        $filename = 'FixedAssetsQr.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
        */
        $client = new Client(
            "http://192.168.25.5:8080/jasperserver",
            "jasperadmin",
            "jasperadmin",
            ""
        );
        $controls = array('p_lista' => $lista);
        \Log::info($controls);
        $report = $client->reportService()->runReport('/reports/interactive/FixedAssetsQr', 'pdf', null, null, $controls);
        \Log::info("este es el reporte que usamos".$report);

        //Storage::put('/valores.pdf',$report) ;*/
        //$report = get_file_contents('http://localhost:8080/jasperserver/rest_v2/resources/reports/interactive/FixedAssetsQr.pdf?p_lista='.$lista.'&j_username=jasperadmin&j_password=jasperadmin');
        \Log::info($report);
        return $report;
    }

    public function getReportSelectedFixedAssets2(Request $request)
    {
        $client = new Client(
            "http://192.168.25.5:8080/jasperserver",
            "jasperadmin",
            "jasperadmin",
            ""
        );
        \Log::info($client->serverInfo());
        $report = $client->reportService()->runReport('/reports/interactive/valores', 'pdf');
        return $report;

    }
}
