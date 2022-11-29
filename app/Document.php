<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model
{
    //  *  D1. Obtener la lista de las solicitadas en linea por persona
    //  * {gestion: gestion activa}
    public static function GetRequests($id, $year)
    {
        $query = "select * from linea.solicitudes s where s.ci_per ='" . $id . "' and s.gestion = '" . $year . "' order by fec_cre desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    public static function StoreBoucherOfRequest($solicitud, $boucher, $fecha, $monto, $ruta)
    {
        $query = "insert into linea.deposito_solicitud(id_sol, boucher, fecha, imp_bou, ruta) " .
            "values('" . $solicitud . "','" . $boucher . "','" . $fecha . "','" . $monto . "','" . $ruta . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  **. crear una nueva solicitud indicando el tipo, la gestion, y quien lo esta realizando
    //  * {gestion: gestion de la solicitud }
    //  * {marker: tipo de solicitud }
    //  * {nodip: carnet de identidad de la persona }
    //  * {descripcion: detalle de la persona }
    public static function setRequestByYear($gestion, $marker, $no_dip, $descripcion, $total){
        $query = "select * from linea.ff_nueva_solicitud('" . $gestion . "','" . $marker . "','" . $no_dip . "','" . $descripcion . "','" . $total . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D3. Obtener la informacion por cada solicitud
    //  * {id: id de la solicitud }
    public static function getDataRequestById($id)
    {
        $query = "select * from linea.solicitudes a inner join linea.valores_solicitud b
                  on a.id = b.id_sol where a.id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  *  D3. Obtener los boucher de cada solicitud
    //  * {id: id de la solicitud }
    public static function getBoucherRequestById($id){
        $query = "select * from linea.deposito_solicitud d 
                  where d.id_sol = '" . $id . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }













    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***


    public static function getDescriptionByAbr($abr)
    {
        $query = "SELECT * FROM bdoc.des_doc dd WHERE dd.id_abr ='" . $abr . "' AND dd.estado = 'TRUE'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - añadir certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function AddGraduateCertficate($nro_doc, $fec_memo, $fec_prov, $no_prov, $ci_per, $des_per, $cod_val, $des_dip, $usr_cre, $gestion)
    {
        $query = "INSERT INTO bdoc.cer_dia(no_doc, fec_memo, fec_prov, no_prov, ci_per, des_per, cod_val, des_dip, usr_cre, gestion)" .
            "VALUES('" . $nro_doc . "','" . $fec_memo . "','" . $fec_prov . "','" . $no_prov . "','" . $ci_per . "','" . $des_per . "','" . $cod_val . "','" . $des_dip . "','" . $usr_cre . "','" . $des_per . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // *** - verificar certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function CheckGraduateCertficate($nro_doc, $ci_auth, $gestion)
    {

        $query = "UPDATE bdoc.cer_dia SET ci_auth = '" . $ci_auth . "',est_doc = 'Verificado'" .
            "WHERE nro_doc='848' AND gestion ='2021'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - obtener los cursos ofrecidos por postgrado - ***
    // *** - parametros [] - ***
    public static function GetPostGraduateCourses()
    {
        $query = "SELECT id, cod_val, des_dip, act_dip, fec_cre, usr_cre," .
            "(SELECT v.pre_uni FROM val.valores v WHERE v.cod_val = b.cod_val) AS imp_val" .
            "FROM bdoc.val_mat b WHERE b.act_dip = TRUE";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
