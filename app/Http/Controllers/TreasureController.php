<?php

namespace App\Http\Controllers;

use App\Treasure;
use Illuminate\Http\Request;
use JasperPHP\JasperPHP as JasperPHP;
use Illuminate\Support\Collection;

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
    public function getReportValuesQr($id_dia, $ci_per, $gestion, $usr_cre)
    {        
        \Log::info('estos son datos: '. $id_dia.' '.trim($ci_per).' '.$gestion.' '.trim($usr_cre));
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/test.jrxml';
        $jasper->compile($input)->execute();

        $input = public_path() . '/reports/test.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf'),//array('pdf', 'rtf'), // Formatos de salida del reporte
            array(
                'p_id_dia' => $id_dia, 
                'p_ci_per' => $ci_per, 
                'p_gestion' => $gestion, 
                'p_usr_cre' => $usr_cre
                ),//array('php_version' => phpversion()),// Parámetros del reporte
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

    public function getReportDetailStudents($id){
        $jasper = new JasperPHP;
        $input = public_path() . '/reports/testDetail.jrxml';
        $jasper->compile($input)->execute();

        $input = public_path() . '/reports/testDetail.jasper'; //ReportValuesQr
        $output = public_path() . '/reports';
        $jasper->process(
            $input,
            false, //$output,
            array('pdf'),//array('pdf', 'rtf'), // Formatos de salida del reporte
            array(
                'p_id' => $id, 
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

        $pathToFile = public_path() . '/reports/testDetail.pdf';
        $filename = 'testDetail.pdf';
        $headers = ['Content-Type' => 'application/pdf'];
        return response()->download($pathToFile, $filename, $headers);

    }

    public function storeTransactionsByStudents(Request $request){
        $id_tran = 0;
        $dataDayTransactions = $request->get('dayTransactions');
        $dataPostulations = $request->get('postulations');
        $dataValuesPostulations = $request->get('valuesPostulations');

        $id_dia = $dataDayTransactions['id_dia']; 
        $fec_tra = $dataDayTransactions['fec_tra'];
        $usr_cre = $dataDayTransactions['usr_cre'];
        $gestion = $dataDayTransactions['gestion'];
        
        $ci_per = strtoupper($dataPostulations['nro_dip']);
        $nombres = strtoupper($dataPostulations['nombres']);
        $paterno = strtoupper($dataPostulations['paterno']);
        $materno = strtoupper($dataPostulations['materno']);

        $idx = Treasure::getIdTransactionsByYear($gestion);
        $idx = $idx[0]->{'ff_id_tramite'};
        $nro_com = str_pad($idx, 6, "0", STR_PAD_LEFT);
        $tip_tra = '10';

        if ($paterno != "")
            $des_per = $paterno ." ". $materno .",". $nombres;
        else
            $des_per = $materno .",". $nombres;
        foreach ($dataValuesPostulations as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            $imp_val = $item['imp_val'];
            //$imp_val = $can_val * $pre_uni;
            if ($imp_val == 1){
                $marker = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, '-1', $ci_per, $des_per, $tip_tra, $gestion); 
                $id_tran = $marker[0]->{'id_tran'};
            }
            $data = Treasure::addProcedureByStudents($id_dia, $id_tran, $nro_com, $cod_val, $ci_per, $des_per, $idx, $gestion, $imp_val); 
            $id_tran = 0;
        }
        //return json_encode($data);
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

    public function addSaleOfDay(Request $request){
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::addSaleOfDay($usuario, $gestion);
        return json_encode($data);
    }

    public function getValueById(Request $request){
        $valor = $request->get('id');
        $gestion = $request->get('year');
        $data = Treasure::getValueById($valor, $gestion);
        return json_encode($data);
    }


    //  * Buscar transacciones hechas por una persona a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}    
    public function getTransactionsByPerson(Request $request){
        $id = $request->get('id');// '' cadena vacia
        $data = Treasure::getTransactionsByPerson($id);
        return json_encode($data);
    }

    //  * Buscar transacciones realizadas en la gestion buscada.
    //  * {year: gestion}    
    public function getAllTransactionsByYear(Request $request){
        \Log::info($request);         
        
        $year = $request->get('year');// '' cadena vacia
        $description = strtoupper($request->get('description'));// '' cadena vacia
        $data = Treasure::GetAllTransactionsByYear($description, $year);
        $page = ($request->get('page')? $request->get('page'): 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getAllTransactionsByYear')]
        );
        return json_encode($paginate);
         
    }

    public function cancelTransactionById(Request $request){

        $transaccion = $request->get('transaccion');
        $id = $transaccion['id_tran'];
        $day = $transaccion['id_dia'];
        $year = $request->get('gestion');
        $user = $request->get('usuario');// '' cadena vacia
        $type = $request->get('tipo');
        \Log::info($transaccion);
        \Log::info($id);
        \Log::info($day);
        \Log::info($year);
        \Log::info($user);
        \Log::info($type);
        $data = Treasure::CancelTransactionById($id, $day, $year, $user, $type);
        return null;
        return json_encode($data);
    }
}
