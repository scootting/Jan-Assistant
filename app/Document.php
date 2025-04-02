<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model
{
    //  * EF1. Obtener la lista de estados financieros
    public static function GetFinancialStatements($year)
    {
        $query = "SELECT * FROM efe.estados e order by gestion desc";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * EF2. Obtener la lista de documentos
    public static function GetDocumentsbyFinalcialStatemet($id, $year)
    {
        $query = "SELECT id, idx, descripcion, estado FROM efe.estado_detalle d WHERE d.id_estado ='" . $id . "' order by idx asc";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * EF4. Obtener documentos digitalizados
    public static function getDigitalFinancialDocument($id, $year)
    {
        $query = "SELECT digitalizado as pdf_data FROM efe.estado_detalle d WHERE d.id = ?";
        \Log::info($id);
        \Log::info($query);
        $data = DB::select($query, [$id]);
        return $data;
    }

    //  *  D1. Obtener la lista de las solicitadas en linea por persona
    //  * {gestion: gestion activa}
    public static function GetRequests($id, $year, $typea)
    {
        $query = "select * from linea.solicitudes s where s.ci_per ='" . $id . "' and s.gestion <= '" . $year . "' and s.tipo = '" . $typea . "' order by fec_cre desc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    public static function StoreBoucherOfRequest($solicitud, $boucher, $fecha, $monto, $ruta, $archivo)
    {
        $query = "insert into linea.deposito_solicitud(id_sol, boucher, fecha, imp_bou, ruta, img) " .
            "values('" . $solicitud . "','" . $boucher . "','" . $fecha . "','" . $monto . "','" . $ruta . "', '{$archivo}') ";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  **. crear una nueva solicitud indicando el tipo, la gestion, y quien lo esta realizando
    //  * {gestion: gestion de la solicitud }
    //  * {marker: tipo de solicitud }
    //  * {nodip: carnet de identidad de la persona }
    //  * {descripcion: detalle de la persona }
    public static function setRequestByYear($gestion, $marker, $no_dip, $descripcion, $total, $tipo_pago)
    {
        $query = "select * from linea.ff_nueva_solicitud('" . $gestion . "','" . $marker . "','" . $no_dip . "','" . $descripcion . "','" . $total . "', " . $tipo_pago . ")";
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
    public static function getBoucherRequestById($id)
    {
        $query = "select id, id_sol, boucher, fecha, imp_bou, ruta from linea.deposito_solicitud d
                  where d.id_sol = '" . $id . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D4. Obtener el documento digitalizado de cada solicitud
    //  * {id: id del boucher digitalizado }
    public static function GetDigitalBoucher($id, $year)
    {
        $query = "SELECT img as pdf_data FROM linea.deposito_solicitud d WHERE d.id = ?";
        \Log::info($query);
        $data = DB::select($query, [$id]);
        return $data;
    }
    //  *  D4. Cambia el estado de cada solicitud
    //  * {id: id dela solicitud }
    //  * {state: estado de la solicitud }
    public static function StoreChangeStateRequest($id_request, $state)
    {
        $query = "UPDATE linea.solicitudes set estado = '" . $state . "' where id = '" . $id_request . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;

    }

    //  * M2. Lista las solicitudes de documentos por tipo de documento (MEMO, SOL, etc, etc)
    //  * {id: carnet de identidad de la persona}
    public static function GetDataDocument($id, $tipo)
    {
        $query = "select *, s.id as id_solvencia from bdoc.documentos s inner join bdoc.tipos t on s.id_tipo = t.id " .
            "where s.ci_per ='" . $id .
            "' and s.id_tipo in (select id from bdoc.tipos where abrv = '" . $tipo . "') order by fecha desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * M4. Obtiene la lista de de documentos, por tipo 'MEM' Memoriales, 'SOL' Solvencias
    //  * {gestion: gestion que se esta utilizando}
    //  * {gestion: tipo de documento que se esta solicitando}
    public static function GetTypesOfDocuments($gestion, $tipo)
    {
        $query = "select * from bdoc.tipos s where s.gestion ='" . $gestion . "' and s.abrv = '" . $tipo . "' order by s.sub asc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * M1. guarda las solicitudes de memoriales solicitadas
    public static function StoreRequestDataDocument($id, $abrv, $tipo, $ci_per, $des_per, $gestion)
    {
        $query = "select * from bdoc.ff_nueva_solicitud(" . $id . ",'" . $abrv . "','" . $tipo . "','" . $ci_per . "','" . $des_per . "','" . $gestion . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * M1. guarda las solicitudes de memoriales solicitadas
    public static function StoreRequestMemorial($id, $abrv, $tipo, $ci_per, $des_per, $gestion)
    {
        $query = "select * from bdoc.ff_nueva_solicitud(" . $id . ",'" . $abrv . "','" . $tipo . "','" . $ci_per . "','" . $des_per . "','" . $gestion . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***

    public static function getDescriptionByAbr($abr)
    {
        $query = "SELECT * FROM bdoc.des_doc dd WHERE dd.id_abr ='" . $abr . "' AND dd.estado = 'TRUE'";
        $data  = collect(DB::select(DB::raw($query)));
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

    //  *  M5. Guardar la solvencia escogida en linea
    public static function StoreDataSolvency($fecha, $cod_prg, $des_prg, $id_tipo, $ci_per, $des_per, $gestion, $direccion, $telefono, $correo)
    {
        $query = "select * from bdoc.ff_registrar_solvencia('" . $fecha . "','" . $cod_prg . "','" . $des_prg . "'," . $id_tipo . ",'" . $ci_per . "','" . $des_per . "','" . $gestion . "','" . $direccion . "','" . $telefono . "','" . $correo . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  M7. Edita la solvencia escogida en linea
    public static function GetDataSolvencyById($id, $year)
    {
        $query = "select * from bdoc.ff_obtener_solvencia('" . $id . "'," . $year . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  M5. Guardar la solvencia escogida en linea
    public static function UpdateDataSolvency($id_solvencia, $fecha, $cod_prg, $des_prg, $ci_per, $des_per, $gestion, $direccion, $telefono, $correo)
    {
        $query = "select * from bdoc.ff_editar_solvencia(" . $id_solvencia . ",'" . $fecha . "','" . $cod_prg . "','" . $des_prg . "','" . $ci_per . "','" . $des_per . "','" . $gestion . "','" . $direccion . "','" . $telefono . "','" . $correo . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
