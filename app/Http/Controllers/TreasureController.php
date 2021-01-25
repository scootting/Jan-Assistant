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

}
