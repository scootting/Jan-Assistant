<?php

namespace App\Http\Controllers;

use App\Document;
use App\Treasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreasureController extends Controller
{
//  *  T1. Obtener los valores para la venta en linea
    //  * {gestion: gestion de los valores disponibles}
    public function getValuesOffered(Request $request)
    {
        $year = $request->get('year');
        $typed = 'U';
        $valuesOffered = Treasure::getValuesOffered($year, $typed);
        $typed = 'T';
        $valuesAcquired = Treasure::getValuesOffered($year, $typed);
        return json_encode(['valuesOffered' => $valuesOffered, 'valuesAcquired' => $valuesAcquired]);

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
        $total = $request->get('total');
        $marker = $request->get('marker');

        $descripcion = $client['descripcion'];
        if ($client['paterno'] == "") {
            $apellidos = $client['materno'];
        } else {
            $apellidos = $client['paterno'] . " " . $client['materno'];
        }

        $nombres = $client['nombres'];
        $no_dip = $client['nodip'];
        $gestion = $client['gestion'];

        $id = Document::setRequestByYear($gestion, $marker, $no_dip, $descripcion, $total);
        $id_sol = $id[0]->{'ff_nueva_solicitud'};

        \Log::info("este es el id de la nueva solicitud" . $id);
        \Log::info($client);
        $tip_tra = '10';

        $array_products = array();
        foreach ($acquired as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $des_val = $item['des_val'];
            $can_val = 1;
            $pre_uni = $item['pre_uni'];
            $data = Treasure::SetValuesAcquired($id_sol, $cod_val, $des_val, $can_val, $pre_uni);
            array_push($array_products, array('actividadEconomica' => "1",
                'codigo' => $cod_val,
                'descripcion' => $des_val,
                'precioUnitario' => (float) $pre_uni,
                'unidadMedida' => 1,
                'cantidad' => $can_val));
        }

        $array_b = array('descripcion' => 'Pago por valores universitarios',
            'codigoOrden' => 'VA' . $id_sol,
            'datosPago' => array('nombresCliente' => $nombres,
                'apellidosCliente' => $apellidos,
                'numeroDocumentoCliente' => $no_dip,
                'fechaNacimientoCliente' => '2000-01-01',
                'cuentaBancaria' => '1000005678',
                'montoTotal' => $total,
                'moneda' => 'BOB',
                'tipoCambioMoneda' => 1,
            ),
            "productos" => $array_products,
        );
        \Log::info($array_b);

        $apiURL = 'https://ppe.demo.agetic.gob.bo/transaccion/deuda';

        $headers = [

            'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE2OTgyNDUxMzQsImlkVXN1YXJpb0FwbGljYWNpb24iOjQ5LCJpZFRyYW1pdGUiOiIyMTQifQ.Ab_RzAtWTzBB3oAqA7dOTMBa5eEwQedq1cW_WFm8TNg',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI0ODU5NTIwNCIsImV4cCI6MTc1ODg1OTE5OSwiaXNzIjoiU0hpN2xSaG9ldVgwQU1vaFIwR2k5MnVPd1l0dGFNQUgifQ.rVdcO_gsAbYzXiaV0Y8Bwhu6x8hzkOawH7wycF8J5UM',
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $array_b);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        \Log::info($response);
        \Log::info($statusCode);
        \Log::info($responseBody);
        if ($statusCode == 202) {
            # Satisfactorio...
            $codigoTransaccion = $responseBody['datos']['codigoTransaccion'];
            \Log::info($responseBody['datos']['codigoTransaccion']);
            $id = treasure::setIdCptRequest($codigoTransaccion, $id_sol);
        }
        return json_encode($responseBody);
        //return json_encode($id);
    }

    //  *  T3. obtiene la informacion del comprobante de pago
    //  * {id: id de la transaccion }
    public function getDataTransactionById(Request $request)
    {
        $id_transaction = $request->get('id');
        $data = treasure::GetDataTransactionById($id_transaction);
        return json_encode($data);
    }

    //  *  T4. obtiene el estado de los valores solicitados para la impresion del comprobante
    //  * {id: id de la transaccion }
    public function getDataRequestById(Request $request)
    {
        $id = $request->get('id');
        $dataRequest = Treasure::getDataRequestById($id);
        $dataRequestDetails = Treasure::getDataValuesRequestById($id);
        return json_encode(['dataRequest' => $dataRequest, 'dataRequestDetails' => $dataRequestDetails]);
    }

}
