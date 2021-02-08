<?php

namespace App\Http\Controllers;

use App\Treasure;
use Illuminate\Http\Request;

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
        \Log::info($id_modalidad . " esta es la modalidad," . $year . " esta es la gestion.");
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
        \Log::info($description . " esta es la descripcion," . $year . " esta es la gestion.");
        $data = Treasure::getValuesProcedure($description, $year);
        return json_encode($data);
    }

}
