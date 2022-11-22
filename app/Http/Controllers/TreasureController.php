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

    //  *  T2. Guardar los valores para la venta en linea
    //  * {cliente: informacion del cliente}
    //  * {valores: valores seleccionados}
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
        $id_sol = $id[0]->{'ff_nueva_solicitud'};

        \Log::info("este es el id de la nueva solicitud". $id);
        $tip_tra = '10';
        foreach ($acquired as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $des_val = $item['des_val'];
            $can_val = 1;
            $pre_uni = $item['pre_uni'];
            $data = Treasure::SetValuesAcquired($id_sol, $cod_val, $des_val, $can_val, $pre_uni);
        }
        return json_encode($id);
    }
}
