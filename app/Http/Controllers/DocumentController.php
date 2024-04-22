<?php

namespace App\Http\Controllers;

use App\Document;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentController extends Controller
{

    //  *  D1. Obtener la lista de las solicitadas en linea por persona
    //  * {gestion: gestion activa}
    public function getRequests(Request $request)
    {
        $persona = $request->get('client');
        $id = strtoupper($persona['nodip']);
        $gestion = $request->get('year');


        
        $data = Document::GetRequests($id, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/request')]
        );
        return json_encode($paginate);
    }

    //  *  D2. Guardar los boucher generados por cada solicitud
    //  * {boucher: imagen del boucher }
    //  * {request: informacion del boucher }
    public function storeBoucherOfRequest(Request $request)
    {
        \Log::info($request);
        $solicitud = $request->get('tag');
        $boucher = $request->get('boucher');
        $fecha = $request->get('fecha');
        $monto = $request->get('monto');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        $ruta = '';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            //path
            $path = "documento";
            $file_name = 'documento-' . $solicitud . '-' . strval($boucher) . '.' . $fileExt; //$file->getClientOriginalName();
            $ruta = $path . "/" . $file_name;
            $file->storeAs($path, $file_name);
            //documento digital
            $data = file_get_contents($file);
            // Escapar el dato binario
            $escaped = pg_escape_bytea($data);
            // Insertarlo en la base de datos
            $data = Document::StoreBoucherOfRequest($solicitud, $boucher, $fecha, $monto, $ruta, $escaped);
            //$data = Archive::StoreDigitalDocument($id_document, $escaped, $ruta);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        $data = Document::storeChangeStateRequest($solicitud, 'Procesando');
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  *  D4. Obtener el documento digitalizado de cada solicitud
    //  * {id: id del boucher digitalizado }
    public function getDigitalBoucher(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $result = Document::GetDigitalBoucher($id, $year);
        \Log::info("Hola, ingresas aca!!!");

        if (!empty($result[0]->pdf_data)) {
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            \Log::info($my_bytea);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }
    }

    //  * M2. Lista las solicitudes de elaboracion de memorial universitario
    public function getDataDocument(Request $request)
    {
        $persona = $request->get('client');
        $id = strtoupper($persona['nodip']);
        $tipo = $request->get('typea');

        $data = Document::GetDataDocument($id, $tipo);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/request')]
        );
        return json_encode($paginate);
    }

    //  * M1. guarda las solicitudes de memoriales solicitadas
    public function storeRequestMemorial(Request $request)
    {
        $client = $request->get('client');
        $ci_per = strtoupper($client['nodip']);
        $des_per = strtoupper($client['descripcion']);
        $gestion = $client['gestion'];

        $adquirido = $request->get('acquired');
        \log::info($adquirido);
        foreach ($adquirido as $item) {
            # code...
            $id = $item['id'];
            $abrv = $item['abrv'];
            $tipo = $item['tipo'];
            $data = Document::StoreRequestMemorial($id, $abrv, $tipo, $ci_per, $des_per, $gestion);
        }
        return json_encode($id);
        //return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  * M3. Imprimir la solicitud de elaboracion de memorial universitario
    public function reportRequestMemorial(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion'); //$dataDays['gestion'];
        \Log::info("DATOS PARA LA IMPRESION DE BOUCHER");
        \Log::info($gestion);
        \Log::info($id);

        $nreport = 'CustomerRequestMemo';
        $controls = array(
            'p_id' => $id,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * M4. Obtiene la lista de de documentos, por tipo 'MEM' Memoriales, 'SOL' Solvencias 
    public function getTypesOfDocuments(Request $request)
    {
        $gestion = $request->get('year');
        $tipo = $request->get('typea');
        $data = Document::GetTypesOfDocuments($gestion, $tipo);
        return json_encode($data);
    }

}
