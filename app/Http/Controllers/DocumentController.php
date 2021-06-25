<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;

class DocumentController extends Controller
{
    //
    //  * Obtener las descripciones de el recurso utilizado.
    //  * {abr: }    
    public function getDescriptionByAbr($abr){        
        $data = Document::getDescriptionByAbr($abr);
        return json_encode($data);
    } 

    // *** - añadir certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function addGraduateCertficate(Request $request){
        $nro_doc = $request->get('nro_doc');
        $fec_memo = $request->get('fec_memo');
        $fec_prov = $request->get('fec_prov');
        $no_prov = $request->get('no_prov');
        $ci_per = $request->get('ci_per');
        $des_per = $request->get('des_per');
        $cod_val = $request->get('cod_val');
        $des_dip  = $request->get('des_dip');
        $usr_cre = $request->get('usr_cre');
        $gestion = $request->get('gestion');
        $data = Document::AddGraduateCertficate($nro_doc, $fec_memo, $fec_prov, $no_prov, $ci_per, $des_per, $cod_val, $des_dip, $usr_cre, $gestion);
        return $data;
    }
    // *** - verificar certificado de los diplomados - ***
    // *** - parametros [numero de certificado, gestion actual, carnet de identidad del verificante ] - ***
    public static function checkGraduateCertficate(Request $request){
        $nro_doc = $request->get('nro_doc');
        $gestion = $request->get('gestion');
        $ci_auth = $request->get('ci_auth');
        $data = Document::checkGraduateCertficate($nro_doc, $ci_auth, $gestion);
        return $data;         
    }

    // *** - obtener los cursos ofrecidos por postgrado - ***
    // *** - parametros [] - ***
    public static function getPostGraduateCourses(){
        $query = "SELECT id, cod_val, des_dip, act_dip, fec_cre, usr_cre,".
                 "(SELECT v.pre_uni FROM val.valores v WHERE v.cod_val = b.cod_val) AS imp_val".
                 "FROM bdoc.val_mat b WHERE b.act_dip = TRUE";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }





}
