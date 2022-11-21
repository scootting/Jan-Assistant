<?php

namespace App\Http\Controllers;

use App\Treasure;
use App\Document;
use Illuminate\Http\Request;

class TreasureController extends Controller
{
//  *  T1. Obtener los valores para la venta en linea
    //  * {gestion: gestion de los valores disponibles}
    public function getValuesOffered(Request $request)
    {
        $year = $request->get('year');
        $data = Treasure::getValuesOffered($year);
        return json_encode($data);
    }

    public function setValuesAcquired(Request $request)
    {
        $id_tran = 0;
        $client = $request->get('client');
        $acquired = $request->get('acquired');
        $marker = $request->get('marker');

        $descripcion = $client['descripcion'];
        $no_dip = $client['nodip'];
        $gestion = $client['gestion'];

        $id = Document::setRequestByYear($gestion, $marker, $no_dip, $descripcion);
        $id = $id[0]->{'ff_nueva_solicitud'};
        \Log::info("este es el id de la nueva solicitud". $id);
        //$nro_com = str_pad($idx, 6, "0", STR_PAD_LEFT);
        $tip_tra = '10';
        /*
        foreach ($dataValuesPostulations as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            $imp_val = $item['imp_val'];
            //$imp_val = $can_val * $pre_uni;
            if ($imp_val == 1) {
                $marker = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, '-1', $ci_per, $des_per, $tip_tra, $gestion);
                $id_tran = $marker[0]->{'id_tran'};
            }
            $data = Treasure::addProcedureByStudents($id_dia, $id_tran, $nro_com, $cod_val, $ci_per, $des_per, $idx, $gestion, $imp_val);
            $id_tran = 0;
        }
        */
        return $id;
    }
}
