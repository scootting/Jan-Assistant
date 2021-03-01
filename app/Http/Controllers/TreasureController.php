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
                'database' => 'daf_help',
                'port' => '5432',
            )  
        )->execute();

        $pathToFile = public_path() . '/reports/test.pdf';
        $filename = 'test.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);
    }

    public function storeTransactionsByStudents(Request $request){
        $dataDayTransactions = $request->get('dayTransactions');
        $dataPostulations = $request->get('postulations');
        $dataValuesPostulations = $request->get('valuesPostulations');

        $id_dia = $dataDayTransactions['id_dia']; 
        $fec_tra = $dataDayTransactions['fec_tra'];
        $usr_cre = $dataDayTransactions['usr_cre'];
        
        $ci_per = strtoupper($dataPostulations['nro_dip']);
        $nombres = strtoupper($dataPostulations['nombres']);
        $paterno = strtoupper($dataPostulations['paterno']);
        $materno = strtoupper($dataPostulations['materno']);

        $nro_com = '000001';
        $tip_tra = '10';
        // mas pruebas para potosi
        //quiero mas pruebas

        if ($paterno != "")
            $des_per = $paterno ." ". $materno .",". $nombres;
        else
            $des_per = $materno .",". $nombres;
        foreach ($dataValuesPostulations as $item) {
            # code...
            $gestion = $item['gestion'];
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            //$imp_val = $can_val * $pre_uni;
            $imp_val = $can_val * $pre_uni;
            $imp_val = $can_val * $pre_uni;            
            $imp_val = $can_val * $pre_uni;            
            $data = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion); 

        }
            /*
                $data = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion); 
                return json_encode($data);
            
            */ 
        /*
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $data = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $gestion)   
                break;
            case 'editar':
            break;
            default:
                break;
        }
        */
        //return json_encode('Hola');
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
