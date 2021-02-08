<?php

namespace App\Http\Controllers;

use App\Treasure;
use Illuminate\Http\Request;
use JasperPHP\JasperPHP as JasperPHP;


class TreasureController extends Controller
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {id: numero de carnet de identidad}    
    //  * {year: año de ingreso}    
    public function getNewStudentByDNI(Request $request){        
        $id = $request->get('id');
        $year = $request->get('year');
        $data = Treasure::getNewStudentByDNI($id, $year);
        return json_encode($data);
    }    
    
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {description: descripcion del valor}    
    //  * {year: año de los valores}    
    public function getValuesProcedure(Request $request){
        $id_modalidad = $request->get('id');
        $year = $request->get('year');
        //\Log::info($id_modalidad . " esta es la modalidad," . $year . " esta es la gestion.");
        switch($id_modalidad) {
            case 1: //EXAMEN PSA          
            case 2: //CURSO PREUNIVERSITARIO          
                $description = 'NUEVOS';
            break;            
            case 6: //ADMISION POR EXCELENCIA ACADEMICA     
            case 7: //ADMISION EXTRAORDINARIA DEPORTIVA      
                $description = 'EXCELENCIA';            
            break;            
            default:            
                $description = 'SIN_TRAMITE';
        }    
        $data = Treasure::getValuesProcedure($description, $year);
        return json_encode($data);
    }

    //reportes usando Jasper
    public function getReportValuesQr(Request $request, $id)
    {
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/test.jrxml';
        $jasper->compile($input)->execute();

        $input = public_path() . '/reports/test.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf', 'rtf'), // Formatos de salida del reporte
            array(),//array('php_version' => phpversion()),// Parámetros del reporte
            array(
                'driver' => 'postgres',
                'username' => 'postgres',
                'password' => '123456',
                'host' => '192.168.25.54',
                'database' => 'daf',
                'port' => '5432',
            )  
        )->execute();

        $pathToFile = public_path() . '/reports/test.pdf';
        $filename = 'test.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }

    public function storeValuesforStudent(Request $request){
        $dataPostulations = $request->get('postulations');
        $dataValuesPostulations = $request->get('valuesPostulations');

        \Log::info("Estas son las pruebas ya reales");
        \Log::info($dataPostulations);
        \Log::info($dataValuesPostulations);
        /*
        $personal = $data['personal'];
        $nombres = strtoupper($data['nombres']);
        $paterno = strtoupper($data['paterno']);
        $materno = strtoupper($data['materno']);
        $sexo = strtoupper($data['sexo']);
        $nacimiento = $data['nacimiento'];

        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $data = General::AddPerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            case 'editar':
                $data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
            break;
            default:
                break;
        }
        return json_encode($data);    */
    }
}
