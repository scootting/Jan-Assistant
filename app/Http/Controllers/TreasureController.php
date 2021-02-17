<?php

namespace App\Http\Controllers;

use App\Treasure;
use Illuminate\Http\Request;
use JasperPHP\JasperPHP as JasperPHP;

use Illuminate\Pagination\LengthAwarePaginator;

class TreasureController extends Controller
{
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {id: numero de carnet de identidad}    
    //  * {year: año de ingreso}    
    public function getDataOfStudentById(Request $request){          
        $id = $request->get('id');
        $year = $request->get('year');
        $data = Treasure::getDataOfStudentById($id, $year);
        return json_encode($data);
    }    
    
    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {description: descripcion del valor}    
    //  * {year: año de los valores}    
    public function getValuesProcedure(Request $request){
        $id_modalidad = $request->get('id');
        $year = $request->get('year');
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
        //$dataDayTransactions = $request->get('dayTransactions');
        $dataPostulations = $request->get('postulations');
        $dataValuesPostulations = $request->get('valuesPostulations');

        $id_dia = '6999'; //se debe definir un dia para el usuario

        $personal = strtoupper($dataPostulations['nro_dip']);
        $nombres = strtoupper($dataPostulations['nombres']);
        $paterno = strtoupper($dataPostulations['paterno']);
        $materno = strtoupper($dataPostulations['materno']);
        if ($paterno != "")
            $descripcion = $paterno ." ". $materno .",". $nombres;
        else
            $descripcion = $materno .",". $nombres;
        foreach ($dataValuesPostulations as $item) {
            # code...
            $gestion = $item['gestion'];
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            $imp_val = $can_val * $pre_uni;
        }
        /*

        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $data = Treasure::AddTransactions($id_dia, $cod_val, $can_val, $pre_uni, $imp_val, $fec_tra, $usr_cre,
                                                  $nro_com, $ci_per, $des_per, $tip_tra, $tra_imp, $gestion);
                break;
            case 'editar':
                $data = Treasure::UpdateTransactions($id_dia, $cod_val, $can_val, $pre_uni, $imp_val, $fec_tra, $usr_cre,
                                                  $nro_com, $ci_per, $des_per, $tip_tra, $tra_imp, $gestion);
            break;
            default:
                break;
        }
        return json_encode($data);    */
    }

    public function getSaleOfDaysByDescription(Request $request){
        $descripcion = $request->get('description');// '' cadena vacia
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::getSaleOfDaysByDescription($descripcion, $usuario, $gestion);
        
        $page = ($request->get('page')? $request->get('page'): 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDaysOfSale')]
        );
        return json_encode($paginate);
    }

    public function getSaleOfDayById(Request $request){
        $id = $request->get('id');// '' cadena vacia
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::getSaleOfDayById($id, $usuario, $gestion);
        return json_encode($data);
    }
}
