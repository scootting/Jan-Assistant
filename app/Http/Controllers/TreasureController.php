<?php
namespace App\Http\Controllers;

use App\Document;
use App\Libraries\JSRClient;
use App\Treasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreasureController extends Controller
{
//  *  T1. Obtener los valores para la venta en linea
    //  * {gestion: gestion de los valores disponibles}
    public function getValuesOffered(Request $request)
    {
        $year  = $request->get('year');
        $typea = $request->get('typea');
        $client = $request->get('client');
        $nodip = $client['nodip'];

        switch ($typea) {
            case "Course":
                    $typed = 'B';
                /*
                Gestion 2025. 
                if(trim($nodip) == '8623347' or trim($nodip) == '10475961'  or trim($nodip) == '8655639'  or trim($nodip) == '8598295'  or trim($nodip) == '13165699' or
                   trim($nodip) == '8653318' or trim($nodip) == '8598916'  or trim($nodip) == '8554569'  or trim($nodip) == '12653174'  or trim($nodip) == '8523607' or                
                   trim($nodip) == '8645699'
                  )
                    $typed = 'X';
                else
                */
                break;
            case "Sale":
                $typed = 'U';
                break;
            default:
                $typed = 'A';
        }
        $valuesOffered  = Treasure::getValuesOffered($year, $typed);
        $typed          = 'T';
        $valuesAcquired = Treasure::getValuesOffered($year, $typed);
        \Log::info($valuesOffered);
        \Log::info($valuesAcquired);

        return json_encode(['valuesOffered' => $valuesOffered, 'valuesAcquired' => $valuesAcquired]);

        return json_encode($data);
    }

    //  *  T2. Guardar los valores para la venta en linea
    //  * {cliente: informacion del cliente}
    //  * {valores: valores seleccionados}
    public function setValuesAcquired(Request $request)
    {
        $id_tran  = 0;
        $typea    = '';
        $typeb    = '';
        $client   = $request->get('client');
        $acquired = $request->get('acquired');
        $total    = $request->get('total');
        $pago    = $request->get('pago');
        $marker   = $request->get('marker');

        if ($pago == 1) {
            $tipo_pago = 'QR';
            $headers = [
                'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDU0NzkzMjMsImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDYxIn0.iFGuBmsIffgnJSLynYax3X87If-tFzgoJKmSltFhNWM',
                'Content-Type'        => 'application/json',
                'Authorization'       => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MDEwOTI0MCIsImV4cCI6MTc5NDk3NDM5OSwiaXNzIjoiS3ZzMzh4cU44Vk9ETm1DOEZQczM0NTdDMU02U05Xc1kifQ.oVrtCB-p4zHvtbxXI_b7o1hpNXD13JYiOqJ19URl39E',    
            ];
    
        } else {
            $tipo_pago = 'CPT';
            $headers = [
                'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3NDQ4MzczMTksImlkVXN1YXJpb0FwbGljYWNpb24iOjUxLCJpZFRyYW1pdGUiOiIxMTI3In0.GKXul_CEF71UYD8Yw6jqHn2R7FsaqOVtjugdV72MD90',
                'Content-Type'        => 'application/json',
                'Authorization'       => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MDEwOTI1NCIsImV4cCI6MTc3NTE4ODc5OSwiaXNzIjoieG8xNDFYTUR5ZnVxQjd0anZidktkc214TzQ2TkFqblYifQ.arz3HIQx2hqmruQzcbUA1JcnKASFjYJuRkftQ75Zs1I',    
            ];
        }

        $descripcion = $client['descripcion'];
        if ($client['paterno'] == "") {
            $apellidos = $client['materno'];
        } else {
            $apellidos = $client['paterno'] . " " . $client['materno'];
        }

        $nombres = $client['nombres'];
        $no_dip  = $client['nodip'];
        $gestion = $client['gestion'];

        $id     = Document::setRequestByYear($gestion, $marker, $no_dip, $descripcion, $total, $tipo_pago);
        $id_sol = $id[0]->{'ff_nueva_solicitud'};

        \Log::info("este es el id de la nueva solicitud" . $id);
        \Log::info($client);
        $tip_tra = '10';

        switch ($marker) {
            case "Course":
                $typea = 'B';
                $typeb = 'BU';
                break;
            case "Sale":
                $typea = 'V';
                $typeb = 'VU';
                break;
            default:
                $typea = 'A';
                $typeb = 'AU';
        }
        $array_products = [];
        $total_b = 0;
        foreach ($acquired as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $des_val = $item['des_val'];
            $can_val = 1;
            $pre_uni = $item['pre_uni'];
            
            $data    = Treasure::SetValuesAcquired($id_sol, $cod_val, $des_val, $can_val, $pre_uni);
            array_push($array_products, ['actividadEconomica' => "1",
                'codigo'                                          => $cod_val,
                'descripcion'                                     => $des_val,
                'precioUnitario'                                  => (float) $pre_uni,
                'unidadMedida'                                    => 1,
                'cantidad'                                        => $can_val]);
        }

        $array_b = ['descripcion' => $typeb . ' - ' . $no_dip . ' - ' . $apellidos . ', ' . $nombres,
            'codigoOrden'             => $typea . $id_sol,
            'datosPago'               => ['nombresCliente' => $nombres,
                'apellidosCliente'                             => $apellidos,
                'numeroDocumentoCliente'                       => $no_dip,
                'fechaNacimientoCliente'                       => '2000-01-01',
                //'cuentaBancaria' => '1000005678', /* pruebas */
                //'cuentaBancaria' => '10000006023167',/* preproduccion */
                'cuentaBancaria'                               => '10000006714592', /* produccion */
                'montoTotal'                                   => $total,
                'moneda'                                       => 'BOB',
                'tipoCambioMoneda'                             => 1,
            ],
            "productos"               => $array_products,
        ];
        \Log::info($array_b);

        //$apiURL = 'https://ppe.demo.agetic.gob.bo/transaccion/deuda';
        $apiURL = 'https://ppe.agetic.gob.bo/transaccion/deuda';

            // ----- LIONEL $headers = [
            //pruebas
            //'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE2OTgxODYyNTgsImlkVXN1YXJpb0FwbGljYWNpb24iOjQ5LCJpZFRyYW1pdGUiOiIyMTcifQ.xBwL9mzzV9o2EA3xXMo-xvd2TW5NmFiGsE9ijRjj_BY',
            //preproduccion matricula
            //'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE2OTgyNDUxMzQsImlkVXN1YXJpb0FwbGljYWNpb24iOjQ5LCJpZFRyYW1pdGUiOiIyMTQifQ.Ab_RzAtWTzBB3oAqA7dOTMBa5eEwQedq1cW_WFm8TNg',
            //produccion matricula
            //JWT eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDQyMTAxMzksImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDU3In0.LN0FDsgyaujxVorWwybx2H0GE1v6R8d2S4JUT8Fhg3A
            //'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDQyMTAxMzksImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDU3In0.LN0FDsgyaujxVorWwybx2H0GE1v6R8d2S4JUT8Fhg3A',
            //produccion pruebas
            //JWT eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDQyMjM4NjUsImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDU4In0.EmpojyjhZsaGPROb4i2j8CnSFYN3ajmfRk7JcydKgDM
            //'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDQyMjM4NjUsImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDU4In0.EmpojyjhZsaGPROb4i2j8CnSFYN3ajmfRk7JcydKgDM',
            //produccion valores
            //JWT eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDU0NzkzMjMsImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDYxIn0.iFGuBmsIffgnJSLynYax3X87If-tFzgoJKmSltFhNWM
            // ----- LIONEL 'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE3MDU0NzkzMjMsImlkVXN1YXJpb0FwbGljYWNpb24iOjM5LCJpZFRyYW1pdGUiOiIxMDYxIn0.iFGuBmsIffgnJSLynYax3X87If-tFzgoJKmSltFhNWM',
            // ----- LIONEL 'Content-Type'        => 'application/json',
            //pruebas
            //'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI0ODU5NTIwNCIsImV4cCI6MTc1ODg1OTE5OSwiaXNzIjoiU0hpN2xSaG9ldVgwQU1vaFIwR2k5MnVPd1l0dGFNQUgifQ.rVdcO_gsAbYzXiaV0Y8Bwhu6x8hzkOawH7wycF8J5UM',
            //produccion
            // ----- LIONEL 'Authorization'       => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MDEwOTI0MCIsImV4cCI6MTc2MzQzODM5OSwiaXNzIjoiQnF1ajRJc2xOQVFYNGYxUWxnVTc5WFlwTGFuYlNpR3EifQ.A4-dKXSu6MWsnZlxDomGb5a9qdY26Z5IaW5yyP8Z2x0',
            // ----- LIONEL ];

        $response = Http::withHeaders($headers)->post($apiURL, $array_b);

        $statusCode   = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        \Log::info($response);
        \Log::info($statusCode);
        \Log::info($responseBody);
        if ($statusCode == 202) {
            # Satisfactorio...
            $codigoTransaccion = $responseBody['datos']['codigoTransaccion'];
            \Log::info($responseBody['datos']['codigoTransaccion']);
            \Log::info($responseBody);
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
        $data           = treasure::GetDataTransactionById($id_transaction);
        return json_encode($data);
    }


    //  *  T3. obtiene la informacion del comprobante de pago
    //  * {id: id de la transaccion }
    public function getDataTransactionById4(Request $request)
    {
        $id_transaction = $request->get('id');
        \Log::info($id_transaction);
        $data           = treasure::GetDataTransactionById4($id_transaction);
        return json_encode($data);
    }


    //  *  T4. obtiene el estado de los valores solicitados para la impresion del comprobante
    //  * {id: id de la transaccion }
    public function getDataRequestById(Request $request)
    {
        $id          = $request->get('id');
        $dataRequest = Treasure::getDataRequestById($id);
        \Log::info($dataRequest[0]->estado);
        if ($dataRequest[0]->estado != 'PROCESADO') {
            $dataRequestDetails = Treasure::getDataValuesRequestById2($id);
        } else {
            $dataRequestDetails = Treasure::getDataValuesRequestById($id);
        }

        return json_encode(['dataRequest' => $dataRequest, 'dataRequestDetails' => $dataRequestDetails]);
    }

    public function printComprobate(Request $request)
    {
        $id      = $request->get('id');
        $cod_val = $request->get('cod');
        if ($cod_val == '9351') {
            $nreport = 'Treasure_Values_Physical';
        } else {
            $nreport = 'Treasure_Values';
        }

        $controls = [
            'id_tran' => $id,
        ];
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
}
