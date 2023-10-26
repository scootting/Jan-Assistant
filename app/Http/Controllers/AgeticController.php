<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class AgeticController extends Controller
{
    //
    public function storeOnlineSalesRequest(Request $request)
    {
        
        $string_json = '{
            "descripcion": "PAGO DE PRUEBA UNIVERSIDAD TOMAS FRIAS",
            "codigoOrden": "PAGO0060",
            "datosPago": {
              "nombresCliente": "JUAN",
              "apellidosCliente": "PEREZ",
              "numeroDocumentoCliente": "6543435",
              "fechaNacimientoCliente": "1988-06-08",
              "cuentaBancaria": "1000005678",
              "montoTotal": 8000,
              "moneda": "BOB",
              "tipoCambioMoneda": 1
            },
            "productos": [
              {
                      "actividadEconomica": "841001",
                      "codigo": "UMTRF-atc-202190",
                      "descripcion": "Registro de documentos varios",
                      "precioUnitario": 8000,
                      "unidadMedida": 58,
                      "cantidad": 1
                  }
            ]
          }';
        $postInput = json_decode($string_json, true);
        \Log::info($postInput);
        $apiURL = 'https://ppe.demo.agetic.gob.bo/transaccion/deuda';
        /*
        $postInput = [
            'first_name' => 'Hardik',
            'last_name' => 'Savani',
            'email' => 'example@gmail.com'
        ];*/
  
        $headers = [
            
            'x-cpt-authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE2OTgyNDUxMzQsImlkVXN1YXJpb0FwbGljYWNpb24iOjQ5LCJpZFRyYW1pdGUiOiIyMTQifQ.Ab_RzAtWTzBB3oAqA7dOTMBa5eEwQedq1cW_WFm8TNg',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI0ODU5NTIwNCIsImV4cCI6MTc1ODg1OTE5OSwiaXNzIjoiU0hpN2xSaG9ldVgwQU1vaFIwR2k5MnVPd1l0dGFNQUgifQ.rVdcO_gsAbYzXiaV0Y8Bwhu6x8hzkOawH7wycF8J5UM'
        ];
  
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        \Log::info($response);
        \Log::info($statusCode);
        \Log::info($responseBody);
        return json_encode($responseBody);
    }
    public function storeOnlineSalesRequest2(Request $request){
        
    }
}

/*
curl --location 'https://ppe.demo.agetic.gob.bo/transaccion/deuda' \
--header 'x-cpt-authorization:
eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBR0VUSUMiLCJpYXQiOjE2OTgxODYyNTgsImlkVXN1YXJpb0FwbGljYWNpb24iOjQ5LCJpZFRyYW1pdGUiOiIyMTcifQ.xBwL9mzzV9o2EA3xXMo-xvd2TW5NmFiGsE9ijRjj_BY
\
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI0ODU5NTIwNCIsImV4cCI6MTc1ODg1OTE5OSwiaXNzIjoiU0hpN2xSaG9ldVgwQU1vaFIwR2k5MnVPd1l0dGFNQUgifQ.rVdcO_gsAbYzXiaV0Y8Bwhu6x8hzkOawH7wycF8J5UM'
\
--data '{
  "descripcion": "PAGO DE PRUEBA UNIVERSIDAD TOMAS FRIAS",
  "codigoOrden": "PAGO0002",
  "datosPago": {
    "nombresCliente": "JUAN",
    "apellidosCliente": "PEREZ",
    "numeroDocumentoCliente": "6543435",
    "fechaNacimientoCliente": "1988-06-08",
    "cuentaBancaria": "1000005678",
    "montoTotal": 8000,
    "moneda": "BOB",
    "tipoCambioMoneda": 1
  },
  "productos": [
    {
            "actividadEconomica": "841001",
            "codigo": "UMTRF-atc-202190",
            "descripcion": "Registro de documentos varios",
            "precioUnitario": 8000,
            "unidadMedida": 58,
            "cantidad": 1
        }
  ]
}'
*/
