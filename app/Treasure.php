<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Treasure extends Model
{
//  *  T1. Obtener los valores para la venta en linea
    //  * {gestion: gestion de los valores disponibles}
    public static function getValuesOffered($year, $typed)
    {
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $query = "select * from ppe.ff_valores_habilitados('" . $year . "','" . $typed . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  T2. Guardar los valores para la venta en linea
    //  * {cliente: informacion del cliente}
    //  * {valores: valores seleccionados}
    public static function SetValuesAcquired($id_sol, $cod_val, $des_val, $can_val, $pre_uni)
    {
        //insert into linea.valores_solicitud( ... ) values ( ... )
        $query = "INSERT INTO linea.valores_solicitud(id_sol, cod_val, des_val, can_val, imp_val) VALUES " .
            "('" . $id_sol . "','" . $cod_val . "','" . $des_val . "','" . $can_val . "','" . $pre_uni . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  T2. Actualiza el codigo cpt de una solicitud de venta de valores en linea
    //  * {codigoTransaccion: codigo de la transaccion}
    //  * {id_sol: id de la solicitud}
    public static function setIdCptRequest($codigoTransaccion, $id_sol)
    {
        $query = "update linea.solicitudes set id_cpt = '" . $codigoTransaccion . "', estado = 'EN PROCESO' where id = '" . $id_sol . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  T3. obtiene la informacion del comprobante de pago
    //  * {id: id de la transaccion }
    public static function GetDataTransactionById($transaction)
    {
        $query = "select * from val.tra_dia d inner join val.valores e on e.cod_val = d.cod_val where d.id_tran = '" . $transaction . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D3. Obtener la informacion por cada solicitud
    //  * {id: id de la solicitud }
    public static function getDataRequestById($id)
    {
        $query = "select * from linea.solicitudes a where a.id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  *  D3. Obtener los boucher de cada solicitud
    //  * {id: id de la solicitud }
    public static function getDataValuesRequestById($id){
        $query = "select * from ppe.ff_valores_verificados(" . $id . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getDataValuesRequestById2($id){
        $query = "select * from ppe.ff_valores_no_verificados(" . $id . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
