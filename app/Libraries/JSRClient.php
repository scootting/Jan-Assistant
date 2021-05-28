<?php 

namespace App\Libraries;

use \Jaspersoft\Client\Client;
//use JasperPHP\JasperPHP as JasperPHP;
//use Jaspersoft\Client\Client;

//$report = get_file_contents(JASPERREPORTS_URL . '/rest_v2/reports/reports/userHistory.pdf?user_id=123&j_username=' . JASPERREPORTS_USER . '&j_password=' . JASPERREPORTS_PASS);

class JSRClient
{
    private static $jasperUser = 'jasperadmin';
    private static $jasperPass = 'jasperadmin';
    private static $URLServer = 'http://192.168.25.5:8080/jasperserver';

    public function __construct()
    {
    }

    public static function GetReportWithParameters($nameReport, $controls){
        $client = new Client(
            self::$URLServer,
            self::$jasperUser,
            self::$jasperPass,
            ""
        );
        \Log::info($client->serverInfo());
        \Log::info($controls);
        $report = $client->reportService()->runReport('/reports/interactive/'.$nameReport, 'pdf', null, null, $controls);
        \Log::info("este es el reporte que usamos".$report);
        return $report; 
    }

    public static function GetReport($nameReport){
        $client = new Client(
            self::$URLServer,
            self::$jasperUser,
            self::$jasperPass,
            ""
        );
        \Log::info($client->serverInfo());
        $report = $client->reportService()->runReport('/reports/interactive/'.$nameReport, 'pdf');
        return $report; 
    }

    public static function GetReportWithPHPJasper(){
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
    }
}