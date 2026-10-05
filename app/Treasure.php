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
        $data  = collect(DB::select(DB::raw($query)));
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
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  T3. obtiene la informacion del comprobante de pago
    //  * {id: id de la transaccion }
    public static function GetDataTransactionById($transaction)
    {
        $query = "select * from val.tra_dia d inner join val.valores e on e.cod_val = d.cod_val where d.id_tran = '" . $transaction . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  D3. Obtener la informacion por cada solicitud
    //  * {id: id de la solicitud }
    public static function getDataRequestById($id)
    {
        $query = "select * from linea.solicitudes a where a.id = '" . $id . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getDataRequestByTransaction($transaction)
    {
        $query = "select * from linea.solicitudes a where a.id_cpt = '" . $transaction . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  T22. Realizar la recuperacion del Qr.
    public static function storeDataQrTransaction($codigoTransaccion, $idQr, $codigoQrBase64, $finVigencia)
    {
        $query = "SELECT * FROM ppe.ff_guardar_estado_qr(?, ?, decode(?, 'base64'), ?)";
        $data  = collect(DB::select($query, [
            $codigoTransaccion,
            $idQr,
            $codigoQrBase64,
            $finVigencia,
        ]));
        return $data;
    }
    //  *  T22. Realizar la recuperacion del Qr.
    public static function storeDataCptTransaction($codigoTransaccion, $idCpt, $codigoCpt, $finVigencia)
    {
        $query = "SELECT * FROM ppe.ff_guardar_estado_cpt(?, ?, ?, ?)";
        $data  = collect(DB::select($query, [
            $codigoTransaccion,
            $idCpt,
            $codigoCpt,
            $finVigencia,
        ]));
        return $data;
    }
    //  *  D3. Obtener los boucher de cada solicitud
    //  * {id: id de la solicitud }
    public static function GetRequestImageQr($codigoTransaccion)
    {
        //$query = "select codigo_qr from linea.solicitudes_qr where codigo_transaccion = '" . $codigoTransaccion . "' limit 1";
        $query = "select encode(binario_qr, 'base64') AS qr_base64 from linea.solicitudes_qr where codigo_transaccion = '" . $codigoTransaccion . "' limit 1";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // * Actualizar el estado real de la solicitud
    public static function SetRequestState($codigoTransaccion, $estado)
    {
        if ($estado == 'EN_PROCESO') {
            $estado = 'EN PROCESO';
        }
        $query = "UPDATE linea.solicitudes set estado ='" . $estado . "' where id_cpt = '" . $codigoTransaccion . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }
    public static function GetRequestDataCpt($codigoTransaccion)
    {
        //$query = "select codigo_qr from linea.solicitudes_qr where codigo_transaccion = '" . $codigoTransaccion . "' limit 1";
        $query = "select codigo_cpt AS cpt_base10 from linea.solicitudes_cpt where codigo_transaccion = '" . $codigoTransaccion . "' limit 1";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getDataValuesRequestById($id)
    {
        $query = "select * from ppe.ff_valores_verificados(" . $id . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetDataTransactionById4($id)
    {
        $query = "select * from actx.activosx a where a.codigo ='" . $id . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getDataValuesRequestById2($id)
    {
        $query = "select * from ppe.ff_valores_no_verificados(" . $id . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
